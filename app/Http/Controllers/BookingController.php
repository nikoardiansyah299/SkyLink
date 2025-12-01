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
        // Fetch bookings from database for the authenticated user
        $pemesanan = Pemesanan::where('id_users', Auth::id())
            ->with(['penerbangan.bandaraAsal', 'penerbangan.bandaraTujuan', 'tiket'])
            ->get();

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
