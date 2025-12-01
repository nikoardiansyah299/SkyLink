<?php

namespace App\Http\Controllers;

use App\Models\Pemesanan;
use App\Models\Penerbangan;
use Illuminate\Support\Facades\DB;
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

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'status' => $map[$statusInput]]);
        }

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
     * Cancel booking (client only)
     */
    public function cancel(Request $request, $id)
    {
        $pemesanan = Pemesanan::findOrFail($id);

        // Authorization: user can only cancel their own booking
        if ($pemesanan->id_users !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        // Only pending bookings can be cancelled
        if (strtolower($pemesanan->status) !== 'pending') {
            return redirect()->route('bookings.index')
                ->with('error', 'Only pending bookings can be cancelled.');
        }

        $pemesanan->status = 'Cancelled';
        $pemesanan->save();

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'status' => 'Cancelled']);
        }

        return redirect()->route('bookings.index')
            ->with('success', 'Booking cancelled successfully.');
    }

    /**
     * Get alternative flights with same route but different dates (for client modify)
     */
    public function getAlternativeFlights($id)
    {
        $pemesanan = Pemesanan::findOrFail($id);

        // Authorization
        if ($pemesanan->id_users !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        // Get the original flight's route
        $originalFlight = $pemesanan->penerbangan;
        
        // Find other flights with same route (same origin and destination airports)
        $alternatives = Penerbangan::where('id_bandara_asal', $originalFlight->id_bandara_asal)
            ->where('id_bandara_tujuan', $originalFlight->id_bandara_tujuan)
            ->where('id', '!=', $originalFlight->id) // Exclude the current booking
            ->with(['bandaraAsal', 'bandaraTujuan'])
            ->get()
            ->map(function ($flight) use ($pemesanan) {
                return [
                    'id' => $flight->id,
                    'airline' => $flight->nama_maskapai,
                    'date' => \Carbon\Carbon::parse($flight->tanggal)->format('D, j M Y'),
                    'time' => \Carbon\Carbon::parse($flight->jam_berangkat)->format('h:i A'),
                    'departure_time' => $flight->jam_berangkat,
                    'arrival_time' => \Carbon\Carbon::parse($flight->jam_tiba)->format('h:i A'),
                    'price' => $flight->harga,
                    'total_price' => $flight->harga * $pemesanan->jumlah_tiket,
                ];
            });

        return response()->json($alternatives);
    }

    /**
     * Change booking to a different flight (same route, different date)
     */
    public function changeToFlight(Request $request, $id)
    {
        $pemesanan = Pemesanan::findOrFail($id);

        // Authorization
        if ($pemesanan->id_users !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $request->validate([
            'flight_id' => 'required|integer|exists:penerbangan,id',
        ]);

        $newFlight = Penerbangan::findOrFail($request->flight_id);
        $originalFlight = $pemesanan->penerbangan;

        // Verify new flight is same route as original
        if ($newFlight->id_bandara_asal !== $originalFlight->id_bandara_asal ||
            $newFlight->id_bandara_tujuan !== $originalFlight->id_bandara_tujuan) {
            return redirect()->back()->with('error', 'Selected flight is not on the same route.');
        }

        $pemesanan->id_penerbangan = $newFlight->id;
        $pemesanan->save();

        $message = 'Booking updated to ' . $newFlight->nama_maskapai . ' on ' . \Carbon\Carbon::parse($newFlight->tanggal)->format('D, j M Y') . '.';

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => $message, 'status' => $pemesanan->status]);
        }

        return redirect()->route('bookings.index')
            ->with('success', $message);
    }

    /**
     * Delete a booking (owner or admin).
     */
    public function destroy(Request $request, $id)
    {
        $pemesanan = Pemesanan::with('tiket')->findOrFail($id);

        $user = Auth::user();

        // Allow owner or admin to delete
        if (! $user || ($user->roles !== 'admin' && $pemesanan->id_users !== $user->id)) {
            abort(403, 'Unauthorized');
        }

        DB::transaction(function () use ($pemesanan) {
            // Delete related tickets first (if relation exists)
            if (method_exists($pemesanan, 'tiket')) {
                $pemesanan->tiket()->delete();
            }

            $pemesanan->delete();
        });

        if ($request->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('bookings.index')->with('success', 'Booking deleted successfully.');
    }
}
