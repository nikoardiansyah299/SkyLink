<?php

namespace App\Http\Controllers;

use App\Models\Pemesanan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    /**
     * Display the user's bookings
     */
    public function index()
    {
        // If admin, fetch all bookings; otherwise fetch only the authenticated user's bookings
        $query = Pemesanan::with(['penerbangan.bandaraAsal', 'penerbangan.bandaraTujuan', 'tiket']);
        if (Auth::check() && Auth::user()->roles === 'admin') {
            $pemesanan = $query->get();
        } else {
            $pemesanan = $query->where('id_users', Auth::id())->get();
        }

        // Transform database records to match view format
        $bookings = $pemesanan->map(function ($p) {
            $seats = $p->tiket->pluck('seat')->implode(', ');
            
            return [
                'id' => $p->id,
                'airline' => $p->penerbangan->nama_maskapai,
                'airline_logo' => $p->penerbangan->gambar,
                'flight_number' => $p->id . '-' . str_pad($p->id_penerbangan, 4, '0', STR_PAD_LEFT),
                'from' => $p->penerbangan->bandaraAsal->nama_bandara . ' (' . $p->penerbangan->bandaraAsal->kode_iata . ')',
                'to' => $p->penerbangan->bandaraTujuan->nama_bandara . ' (' . $p->penerbangan->bandaraTujuan->kode_iata . ')',
                'departure_date' => \Carbon\Carbon::parse($p->penerbangan->tanggal)->format('D, j M Y'),
                'departure_time' => \Carbon\Carbon::parse($p->penerbangan->jam_berangkat)->format('h:i A'),
                'passengers' => $p->jumlah_tiket,
                'seats' => $seats ?: 'N/A',
                'reference_code' => $p->kode,
                'total_price' => $p->penerbangan->harga * $p->jumlah_tiket,
                'status' => strtolower($p->status),
                'status_badge' => $this->getStatusBadge(strtolower($p->status)),
            ];
        });

        return view('bookings.booking', [
            'bookings' => $bookings,
        ]);
    }

    /**
     * Update booking status (admin only)
     */
    public function updateStatus(Request $request, $id)
    {
        $user = Auth::user();

        if (! $user || $user->roles !== 'admin') {
            abort(403, 'Forbidden');
        }

        $request->validate([
            'status' => 'required|string',
        ]);

        $statusInput = strtolower($request->input('status'));

        // map friendly inputs to enum values stored in DB
        $map = [
            'confirmed' => 'Confirmed',
            'pending' => 'Pending',
            'cancelled' => 'Cancelled',
            'accepted' => 'Confirmed',
        ];

        if (! array_key_exists($statusInput, $map)) {
            return redirect()->back()->with('error', 'Invalid status');
        }

        $p = Pemesanan::findOrFail($id);
        $p->status = $map[$statusInput];
        $p->save();

        return redirect()->back()->with('success', 'Booking status updated.');
    }

    /**
     * Get status badge color based on status
     */
    private function getStatusBadge($status)
    {
        $badges = [
            'confirmed' => 'success',
            'pending' => 'warning',
            'completed' => 'info',
            'cancelled' => 'danger',
        ];

        return $badges[$status] ?? 'secondary';
    }


    /**
     * Show booking details
     */
    public function show($id)
    {
        // Fetch booking from database or dummy data
        // For now, redirect to bookings list
        return redirect()->route('bookings.index');
    }

    /**
     * Cancel booking
     */
    public function cancel($id)
    {
        // Handle cancellation logic
        return redirect()->route('bookings.index')->with('success', 'Booking cancelled successfully.');
    }

    /**
     * Modify booking
     */
    public function modify($id)
    {
        // Handle modification logic
        return redirect()->route('bookings.index')->with('info', 'Booking modification in progress.');
    }
}
