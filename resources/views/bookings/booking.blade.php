@extends('layout.master')
@section('title', 'Pesanan Saya | SkyLink')

@section('content')

<!-- HERO SECTION -->
<section class="py-5 text-light position-relative">
  <div class="hero-bg" aria-hidden="true"></div>

  <div class="container position-relative" style="z-index:2;">
    <div class="row align-items-center">
      <div class="col-lg-8">
        <h1 class="display-5 fw-bold">Pesanan Saya</h1>
        <p class="lead">Lihat dan kelola semua pemesanan penerbangan Anda di satu tempat.</p>
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
        <h5 class="fw-bold mb-2">Belum ada pemesanan</h5>
        <p class="text-muted mb-3">Anda belum memesan penerbangan. Jelajahi penerbangan dan pesan perjalanan Anda sekarang!</p>
        <a href="{{ url('/travels') }}" class="btn btn-primary">Jelajahi Penerbangan</a>
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
                        @php
                            $logo = $booking['airline_logo'] ?? 'images/default-logo.png';
                            $logoUrl = \Illuminate\Support\Str::startsWith($logo, ['http://', 'https://'])
                                ? $logo
                                : asset(ltrim($logo, '/'));
                        @endphp
                        <img src="{{ $logoUrl }}" width="50" alt="{{ $booking['airline'] }}">
                        <div>
                            <div class="fw-semibold">{{ $booking['airline'] }}</div>
                            <div class="small text-muted">Flight {{ $booking['flight_number'] }}</div>
                        </div>
                    </div>
                    
                    <!-- STATUS BADGE -->
                    <span class="badge bg-{{ $booking['status_badge'] }}">
                        {{ $booking['status_label'] ?? ucfirst($booking['status']) }}
                    </span>
                </div>

                <hr>

                <!-- ROUTE & DATE INFO -->
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div>
                            <div class="fw-bold" style="font-size: 1.1rem;">{{ $booking['from'] }}</div>
                            <div class="text-muted small">Keberangkatan</div>
                        </div>
                        <div class="text-center">
                            <i class="bi bi-arrow-right"></i>
                        </div>
                        <div class="text-end">
                            <div class="fw-bold" style="font-size: 1.1rem;">{{ $booking['to'] }}</div>
                            <div class="text-muted small">Kedatangan</div>
                        </div>
                    </div>
                    
                    <div class="small text-muted">
                        <strong>Tanggal:</strong> {{ $booking['departure_date'] }} pukul {{ $booking['departure_time'] }}
                    </div>
                </div>

                <hr>

                <!-- PASSENGERS, TIPE KURSI, & SEATS -->
                <div class="row g-2 mb-3 text-center">
                    <div class="col-12 col-md-4">
                        <div class="small text-muted">Penumpang</div>
                        <div class="fw-bold">{{ $booking['passengers'] }}</div>
                    </div>

                    <div class="col-12 col-md-4">
                        <div class="small text-muted">Tipe Kursi</div>
                        <div class="fw-bold d-flex justify-content-center">
                            <div class="d-inline-block">
                                <span class="seat-badge text-capitalize">{{ $booking['tipe_kelas'] ?? 'ekonomi' }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-md-4">
                        <div class="small text-muted">Kursi</div>
                        <div class="fw-bold d-flex justify-content-center">
                            <span class="seat-number">{{ $booking['seats'] }}</span>
                        </div>
                    </div>
                </div>

                <hr>

                <!-- BOOKING REFERENCE & PRICE -->
                <div class="d-flex justify-content-between align-items-end mb-3">
                    <div>
                        <div class="small text-muted">Kode Pemesanan</div>
                        <div class="fw-bold text-primary">{{ $booking['reference_code'] }}</div>
                    </div>
                    <div class="text-end">
                        <div class="small text-muted">Total Harga</div>
                        <div class="fw-bold text-primary" style="font-size: 1.3rem;">Rp {{ number_format($booking['total_price'], 0, ',', '.') }}</div>
                    </div>
                </div>

                <hr>

                <!-- ACTIONS -->
                <div class="d-flex gap-2">

                    @if(Auth::check() && Auth::user()->roles === 'admin')
                        <!-- Admin: status change form -->
                        <form action="/skylink/public/bookings/{{ $booking['id'] }}/status" method="POST" class="d-flex ms-2">
                            @csrf
                            <select name="status" class="form-select form-select-sm me-2" style="min-width:130px;">
                                <option value="pending" {{ $booking['status'] === 'pending' ? 'selected' : '' }}>Menunggu</option>
                                <option value="confirmed" {{ $booking['status'] === 'confirmed' ? 'selected' : '' }}>Terkonfirmasi</option>
                                <option value="cancelled" {{ $booking['status'] === 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                            </select>
                            <button type="submit" class="btn btn-sm btn-success">Save</button>
                        </form>
                    @else
                        @if($booking['status'] === 'pending')
                            <div class="dropdown">
                                <button class="btn btn-sm btn-outline-danger dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                    Batalkan
                                </button>
                                <ul class="dropdown-menu">
                                    <li><span class="dropdown-item-text small">Batalkan pemesanan ini?</span></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form action="/skylink/public/bookings/{{ $booking['id'] }}/cancel" method="POST" class="w-100">
                                            @csrf
                                            <button type="submit" class="dropdown-item text-danger">Ya, Batalkan</button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        @elseif($booking['status'] === 'confirmed')
                            <div class="dropdown">
                                <button class="btn btn-sm btn-outline-warning dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                    Ubah
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end p-2" style="min-width: 280px; max-height: 400px; overflow-y: auto;">
                                    <li class="dropdown-header">Pilih Penerbangan Lain</li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li id="altFlightsContainer{{ $booking['id'] }}">
                                        <div class="text-center text-muted py-2">
                                            <small>Memuat...</small>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            <script>
                                document.addEventListener('shown.bs.dropdown', function(e) {
                                    if (e.target.classList.contains('dropdown-toggle') && e.target.textContent.includes('Ubah')) {
                                        const container = document.getElementById('altFlightsContainer{{ $booking['id'] }}');
                                        if (container.dataset.loaded) return;
                                        
                                        fetch(`/skylink/public/bookings/{{ $booking['id'] }}/alternatives`)
                                            .then(r => r.json())
                                            .then(flights => {
                                                if (!flights || flights.length === 0) {
                                                    container.innerHTML = '<div class="small text-muted p-2">Tidak ada alternatif</div>';
                                                    return;
                                                }
                                                let html = '';
                                                flights.forEach(f => {
                                                    html += `
                                                        <form action="/skylink/public/bookings/{{ $booking['id'] }}/change-flight" method="POST" style="display:inline;">
                                                            @csrf
                                                            <input type="hidden" name="flight_id" value="${f.id}">
                                                            <button type="submit" class="dropdown-item" style="text-align: left;">
                                                                <div class="fw-semibold small">${f.airline}</div>
                                                                <small class="text-muted">${f.date} - ${f.time}</small><br>
                                                                <small class="text-primary">Rp ${Number(f.total_price).toLocaleString('id-ID')}</small>
                                                            </button>
                                                        </form>
                                                    `;
                                                });
                                                container.innerHTML = html;
                                                container.dataset.loaded = 'true';
                                            })
                                            .catch(e => {
                                                container.innerHTML = '<div class="small text-danger p-2">Gagal memuat</div>';
                                            });
                                    }
                                });
                            </script>
                        @endif

                        @if($booking['status'] === 'cancelled')
                            <div class="dropdown">
                                <button class="btn btn-sm btn-danger dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                    Hapus
                                </button>
                                <ul class="dropdown-menu">
                                    <li><span class="dropdown-item-text small">Hapus pemesanan ini?</span></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form action="/skylink/public/bookings/{{ $booking['id'] }}/delete" method="POST" class="w-100">
                                            @csrf
                                            <button type="submit" class="dropdown-item text-danger">Ya, Hapus</button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
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

<style>
    /* Seat class badge styling */
    .seat-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        height: 28px;
        min-width: 72px;
        padding: 0 .6rem;
        border-radius: 14px;
        background: #f1f5f9;
        color: #0d6efd;
        font-weight: 600;
        font-size: 0.85rem;
        text-transform: capitalize;
        border: 1px solid #e6eef9;
    }

    .seat-number {
        font-size: 1rem;
    }

    @media (max-width: 767px) {
        .seat-badge { min-width: 56px; height: 24px; font-size: 0.78rem; }
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
</script>

@endsection
