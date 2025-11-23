@extends('layout.master')
@section('title', 'My Bookings | SkyLink')

@section('content')

<!-- HERO SECTION -->
<section class="py-5 text-light position-relative">
  <div class="hero-bg" aria-hidden="true"></div>

  <div class="container position-relative" style="z-index:2;">
    <div class="row align-items-center">
      <div class="col-lg-8">
        <h1 class="display-5 fw-bold">My Bookings</h1>
        <p class="lead">View and manage all your flight bookings in one place.</p>
      </div>
    </div>
  </div>
  <style>
    .hero-bg {
      position: absolute;
      inset: -2% 0;
      background: linear-gradient(rgba(13,110,253,0.28), rgba(13,110,253,0.28)),
                  url('https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?q=80&w=1470&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D') center/cover no-repeat;
      z-index: 1;
      filter: saturate(0.95) brightness(0.95);
    }

    @media (max-width: 767px) {
      .hero-bg { background-position: center top; }
    }
  </style>
</section>

<div class="container my-5">

    <!-- NO BOOKINGS MESSAGE -->
    @if($bookings->isEmpty())
    <div class="alert alert-info text-center py-5" role="alert">
        <h5 class="fw-bold mb-2">No bookings yet</h5>
        <p class="text-muted mb-3">You haven't booked any flights yet. Start exploring and book your next adventure!</p>
        <a href="{{ url('/travels') }}" class="btn btn-primary">Browse Flights</a>
    </div>
    @else

    <!-- BOOKINGS LIST -->
    <div class="row g-4">
        @foreach ($bookings as $booking)
        <div class="col-md-6 booking-item" data-status="{{ strtolower($booking['status']) }}">
            <div class="booking-card p-4 rounded-4 shadow-sm border">
                
                <!-- HEADER: Airline & Status -->
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div class="d-flex align-items-center gap-2">
                        <img src="{{ $booking['airline_logo'] }}" width="50" alt="{{ $booking['airline'] }}">
                        <div>
                            <div class="fw-semibold">{{ $booking['airline'] }}</div>
                            <div class="small text-muted">Flight {{ $booking['flight_number'] }}</div>
                        </div>
                    </div>
                    
                    <!-- STATUS BADGE -->
                    <span class="badge bg-{{ $booking['status_badge'] }}">
                        {{ ucfirst($booking['status']) }}
                    </span>
                </div>

                <hr>

                <!-- ROUTE & DATE INFO -->
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div>
                            <div class="fw-bold" style="font-size: 1.1rem;">{{ $booking['from'] }}</div>
                            <div class="text-muted small">Departure</div>
                        </div>
                        <div class="text-center">
                            <i class="bi bi-arrow-right"></i>
                        </div>
                        <div class="text-end">
                            <div class="fw-bold" style="font-size: 1.1rem;">{{ $booking['to'] }}</div>
                            <div class="text-muted small">Arrival</div>
                        </div>
                    </div>
                    
                    <div class="small text-muted">
                        <strong>Date:</strong> {{ $booking['departure_date'] }} at {{ $booking['departure_time'] }}
                    </div>
                </div>

                <hr>

                <!-- PASSENGERS & SEATS -->
                <div class="row g-2 mb-3 text-center">
                    <div class="col-6">
                        <div class="small text-muted">Passengers</div>
                        <div class="fw-bold">{{ $booking['passengers'] }}</div>
                    </div>
                    <div class="col-6">
                        <div class="small text-muted">Seat(s)</div>
                        <div class="fw-bold">{{ $booking['seats'] }}</div>
                    </div>
                </div>

                <hr>

                <!-- BOOKING REFERENCE & PRICE -->
                <div class="d-flex justify-content-between align-items-end mb-3">
                    <div>
                        <div class="small text-muted">Booking Reference</div>
                        <div class="fw-bold text-primary">{{ $booking['reference_code'] }}</div>
                    </div>
                    <div class="text-end">
                        <div class="small text-muted">Total Price</div>
                        <div class="fw-bold text-primary" style="font-size: 1.3rem;">Rp {{ number_format($booking['total_price'], 0, ',', '.') }}</div>
                    </div>
                </div>

                <hr>

                <!-- ACTIONS -->
                <div class="d-flex gap-2">
                    <a href="{{ url('/bookings/' . $booking['id']) }}" class="btn btn-sm btn-primary flex-grow-1">View Details</a>
                    
                    @if($booking['status'] === 'pending')
                        <a href="{{ url('/bookings/' . $booking['id'] . '/cancel') }}" class="btn btn-sm btn-outline-danger">Cancel</a>
                    @elseif($booking['status'] === 'confirmed')
                        <a href="{{ url('/bookings/' . $booking['id'] . '/modify') }}" class="btn btn-sm btn-outline-warning">Modify</a>
                    @endif
                </div>

            </div>
        </div>
        @endforeach
    </div>

    @endif

</div>

<style>
    .booking-card {
        background: #ffffff;
        border: 1px solid #e8eef7 !important;
        transition: all 0.3s ease;
    }

    .booking-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.1) !important;
    }

    .filter-btn {
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .filter-btn.active {
        background: #e8f1ff;
        color: #0d6efd;
        border-color: #bcd7ff !important;
    }

    .filter-btn:hover {
        border-color: #0d6efd !important;
    }

    .badge.bg-confirmed {
        background-color: #198754 !important;
    }

    .badge.bg-pending {
        background-color: #ffc107 !important;
        color: #000 !important;
    }

    .badge.bg-completed {
        background-color: #0dcaf0 !important;
    }

    .badge.bg-cancelled {
        background-color: #dc3545 !important;
    }

    @media (max-width: 768px) {
        .d-flex.justify-content-between {
            flex-direction: column;
            gap: 1rem;
        }
    }
</style>

<script>
    document.querySelectorAll('.filter-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            // Update active button
            document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');

            const status = this.getAttribute('data-status');
            const bookings = document.querySelectorAll('.booking-item');

            bookings.forEach(booking => {
                if (status === 'all' || booking.getAttribute('data-status') === status) {
                    booking.style.display = 'block';
                    setTimeout(() => booking.style.opacity = '1', 10);
                } else {
                    booking.style.opacity = '0';
                    setTimeout(() => booking.style.display = 'none', 300);
                }
            });
        });
    });
</script>

@endsection
