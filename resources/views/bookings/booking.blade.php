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
        <div class="col-md-6 booking-item" data-status="{{ strtolower($booking['status']) }}" data-booking-id="{{ $booking['id'] }}">
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
                    <a href="{{ url('/bookings/' . $booking['id']) }}" class="btn btn-sm btn-primary">View Details</a>

                    @if(Auth::check() && Auth::user()->roles === 'admin')
                        <!-- Admin: status change form -->
                        <form action="{{ route('bookings.updateStatus', $booking['id']) }}" method="POST" class="d-flex ms-2">
                            @csrf
                            <select name="status" class="form-select form-select-sm me-2" style="min-width:130px;">
                                <option value="pending" {{ $booking['status'] === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="confirmed" {{ $booking['status'] === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                <option value="cancelled" {{ $booking['status'] === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                            <button type="submit" class="btn btn-sm btn-success">Save</button>
                        </form>
                    @else
                        @if($booking['status'] === 'pending')
                            <div class="dropdown">
                                <button class="btn btn-sm btn-outline-danger dropdown-toggle" type="button" id="actionsDropdown{{ $booking['id'] }}" data-bs-toggle="dropdown" aria-expanded="false">
                                    Actions
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="actionsDropdown{{ $booking['id'] }}">
                                    <li><a class="dropdown-item text-danger" href="#" onclick="ajaxCancel({{ $booking['id'] }}, this); return false;">Cancel Booking</a></li>
                                </ul>
                            </div>
                        @elseif($booking['status'] === 'confirmed')
                            <div class="dropdown">
                                <button class="btn btn-sm btn-outline-warning dropdown-toggle" type="button" id="modifyDropdown{{ $booking['id'] }}" data-bs-toggle="dropdown" aria-expanded="false">
                                    Modify
                                </button>
                                <ul class="dropdown-menu p-2" style="min-width:320px;" aria-labelledby="modifyDropdown{{ $booking['id'] }}" id="alternativeDropdown{{ $booking['id'] }}">
                                    <li class="text-center small text-muted py-2">Open to load alternatives</li>
                                </ul>
                            </div>
                        @endif

                        @if($booking['status'] === 'cancelled')
                            <button type="button" class="btn btn-sm btn-danger" onclick="ajaxDelete({{ $booking['id'] }}, this)">Delete</button>
                        @endif
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
    // Display flash messages from session
    document.addEventListener('DOMContentLoaded', function() {
        @if ($message = Session::get('success'))
            showAlert('{{ $message }}', 'success');
        @endif

        @if ($message = Session::get('error'))
            showAlert('{{ $message }}', 'danger');
        @endif

        @if ($message = Session::get('info'))
            showAlert('{{ $message }}', 'info');
        @endif
    });

    // showAlert is provided globally by the master layout (`showAlert(message, type)`).

    // Load alternative flights into dropdown menu when user opens it
    function loadAlternativesIntoDropdown(bookingId, menuEl) {
        if (!menuEl) return;
        if (menuEl.dataset.loaded) return; // only load once

        menuEl.innerHTML = '<li class="text-center py-2"><div class="spinner-border spinner-border-sm" role="status"><span class="visually-hidden">Loading...</span></div> Loading...</li>';

        fetch(`/bookings/${bookingId}/alternatives`)
            .then(response => response.json())
            .then(flights => {
                if (!flights || flights.length === 0) {
                    menuEl.innerHTML = '<li class="px-3"><div class="text-muted small">No alternative flights available for this route.</div></li>';
                    menuEl.dataset.loaded = 'true';
                    return;
                }

                let html = '';
                flights.forEach(flight => {
                    html += `
                        <li class="dropdown-item">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <div class="fw-semibold">${flight.airline}</div>
                                    <div class="small text-muted">${flight.date} at ${flight.time} — ${flight.arrival_time} arrival</div>
                                    <div class="small text-primary fw-bold">Rp ${Number(flight.total_price).toLocaleString('id-ID')}</div>
                                </div>
                                <div>
                                    <button class="btn btn-sm btn-success ms-2" onclick="ajaxChangeFlight(${bookingId}, ${flight.id}, this)">Select</button>
                                </div>
                            </div>
                        </li>
                    `;
                });

                menuEl.innerHTML = html;
                menuEl.dataset.loaded = 'true';
            })
            .catch(error => {
                console.error('Error:', error);
                menuEl.innerHTML = '<li class="px-3"><div class="alert alert-danger mb-0">Failed to load flights.</div></li>';
            });
    }

    // AJAX: Cancel booking
    function ajaxCancel(bookingId, btn) {
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const url = `/bookings/${bookingId}/cancel`;

        // Disable button to prevent duplicate clicks
        btn.disabled = true;

        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token,
                'Accept': 'application/json'
            },
            body: JSON.stringify({})
        }).then(r => r.json())
        .then(data => {
            if (data && data.success) {
                // Update UI in-place: mark booking cancelled and show Delete button
                const card = document.querySelector(`.booking-item[data-booking-id='${bookingId}']`);
                if (card) {
                    card.setAttribute('data-status', 'cancelled');
                    // update badge
                    const badge = card.querySelector('.badge');
                    if (badge) {
                        badge.classList.remove('bg-pending', 'bg-confirmed', 'bg-completed', 'bg-cancelled');
                        badge.classList.add('bg-cancelled');
                        badge.textContent = 'Cancelled';
                    }

                    // replace action area: remove dropdowns and add Delete button
                    const actions = card.querySelector('.d-flex.gap-2');
                    if (actions) {
                        // keep View Details button if present
                        const viewBtn = actions.querySelector('a.btn');
                        actions.innerHTML = '';
                        if (viewBtn) actions.appendChild(viewBtn);
                        const del = document.createElement('button');
                        del.className = 'btn btn-sm btn-danger ms-2';
                        del.textContent = 'Delete';
                        del.onclick = function(e){ ajaxDelete(bookingId, del); };
                        actions.appendChild(del);
                    }
                }

                showAlert('Booking cancelled', 'success');
            } else {
                showAlert((data && data.message) ? data.message : 'Failed to cancel booking', 'danger');
                btn.disabled = false;
            }
        }).catch(err => {
            console.error(err);
            showAlert('Failed to cancel booking', 'danger');
            btn.disabled = false;
        });
    }

    // AJAX: Change booking to selected flight
    function ajaxChangeFlight(bookingId, flightId, btn) {
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const url = `/bookings/${bookingId}/change-flight`;
        btn.disabled = true;

        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ flight_id: flightId })
        }).then(r => r.json())
        .then(data => {
            if (data && data.success) {
                showAlert(data.message || 'Booking updated', 'success');
                // reload to reflect updated flight details
                setTimeout(() => location.reload(), 700);
            } else {
                showAlert((data && data.message) ? data.message : 'Failed to change flight', 'danger');
                btn.disabled = false;
            }
        }).catch(err => {
            console.error(err);
            showAlert('Failed to change flight', 'danger');
            btn.disabled = false;
        });
    }

    // AJAX: Delete cancelled booking
    function ajaxDelete(bookingId, btn) {
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const url = `/bookings/${bookingId}/delete`;
        if (btn) btn.disabled = true;

        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token,
                'Accept': 'application/json'
            },
            body: JSON.stringify({})
        }).then(r => r.json())
        .then(data => {
            if (data && data.success) {
                const card = document.querySelector(`.booking-item[data-booking-id='${bookingId}']`);
                if (card && card.parentElement) {
                    card.parentElement.removeChild(card);
                }
                showAlert('Booking deleted', 'success');
            } else {
                showAlert((data && data.message) ? data.message : 'Failed to delete booking', 'danger');
                if (btn) btn.disabled = false;
            }
        }).catch(err => {
            console.error(err);
            showAlert('Failed to delete booking', 'danger');
            if (btn) btn.disabled = false;
        });
    }

    // AJAX: Admin status update handler - intercept forms
    document.addEventListener('submit', function(e) {
        const form = e.target;
        if (form && form.action && form.action.indexOf('/bookings/') !== -1 && form.action.indexOf('/status') !== -1) {
            e.preventDefault();
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const url = form.action;
            const formData = new FormData(form);
            const body = {};
            formData.forEach((v,k) => body[k]=v);

            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json'
                },
                body: JSON.stringify(body)
            }).then(r => r.json())
            .then(data => {
                if (data && data.success) {
                    showAlert('Status updated', 'success');
                    setTimeout(() => location.reload(), 700);
                } else {
                    showAlert('Failed to update status', 'danger');
                }
            }).catch(err => {
                console.error(err);
                showAlert('Failed to update status', 'danger');
            });
        }
    });

    // Listen for Bootstrap dropdown show events to lazy-load alternatives
    document.addEventListener('show.bs.dropdown', function (e) {
        const trigger = e.target; // .dropdown element
        // find the dropdown toggle id like modifyDropdown{ID}
        const toggle = e.relatedTarget || e.target.querySelector('[data-bs-toggle="dropdown"]');
        if (!toggle) return;
        const idAttr = toggle.id || '';
        if (idAttr.indexOf('modifyDropdown') === 0) {
            const bookingId = idAttr.replace('modifyDropdown', '');
            const menuEl = document.getElementById('alternativeDropdown' + bookingId);
            loadAlternativesIntoDropdown(bookingId, menuEl);
        }
    });

    // Filter functionality
    document.querySelectorAll('.filter-btn').forEach(btn => {
        btn.addEventListener('click', function() {
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
