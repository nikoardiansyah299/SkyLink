<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    /**
     * Display the user's bookings
     */
    public function index()
    {
        // Dummy bookings data - Replace with database queries when ready
        $bookings = [
            [
                'id' => 1,
                'airline' => 'Scoot',
                'airline_logo' => '/images/plane1.png',
                'flight_number' => 'TR-2025-001',
                'from' => 'Jakarta (CGK)',
                'to' => 'Singapore (SIN)',
                'departure_date' => 'Wed, 3 Dec 2025',
                'departure_time' => '08:00 AM',
                'passengers' => 2,
                'seats' => '12A, 12B',
                'reference_code' => 'BK123456',
                'total_price' => 928700,
                'status' => 'confirmed',
                'status_badge' => 'confirmed',
            ],
            [
                'id' => 2,
                'airline' => 'AirAsia Indonesia',
                'airline_logo' => '/images/plane1.png',
                'flight_number' => 'TR-2025-002',
                'from' => 'Jakarta (CGK)',
                'to' => 'Bangkok (DMK)',
                'departure_date' => 'Sat, 29 Nov 2025',
                'departure_time' => '02:30 PM',
                'passengers' => 1,
                'seats' => '5C',
                'reference_code' => 'BK654321',
                'total_price' => 1332700,
                'status' => 'pending',
                'status_badge' => 'pending',
            ],
            [
                'id' => 3,
                'airline' => 'Citilink',
                'airline_logo' => '/images/plane1.png',
                'flight_number' => 'TR-2025-003',
                'from' => 'Singapore (SIN)',
                'to' => 'Jakarta (CGK)',
                'departure_date' => 'Wed, 26 Nov 2025',
                'departure_time' => '10:45 AM',
                'passengers' => 4,
                'seats' => '8A, 8B, 8C, 8D',
                'reference_code' => 'BK789123',
                'total_price' => 1432700,
                'status' => 'completed',
                'status_badge' => 'completed',
            ],
            [
                'id' => 4,
                'airline' => 'AirAsia (Malaysia)',
                'airline_logo' => '/images/plane1.png',
                'flight_number' => 'TR-2025-004',
                'from' => 'Kuala Lumpur (KUL)',
                'to' => 'Jakarta (CGK)',
                'departure_date' => 'Wed, 10 Dec 2025',
                'departure_time' => '06:15 PM',
                'passengers' => 1,
                'seats' => '15F',
                'reference_code' => 'BK456789',
                'total_price' => 819100,
                'status' => 'cancelled',
                'status_badge' => 'cancelled',
            ],
        ];

        return view('bookings.booking', [
            'bookings' => collect($bookings),
        ]);
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
