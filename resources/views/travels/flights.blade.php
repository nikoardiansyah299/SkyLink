@extends('layout.master')
@section('title', 'TravelID | Explore the World')

@section('content')

<!-- HERO SECTION -->
<section class="py-5 text-light position-relative">
  <div class="hero-bg" aria-hidden="true"></div>

  <div class="container position-relative" style="z-index:2;">
    <div class="row align-items-center">
      <div class="col-lg-6">
        <h1 class="display-5 fw-bold">Discover the World with Us</h1>
        <p class="lead">Explore breathtaking destinations and create unforgettable memories with our service.</p>
        <p>
          <a href="{{ url('/travels') }}" class="btn btn-light btn-lg me-2">Start Exploring</a>
          <a href="{{ url('/booking') }}" class="btn btn-outline-light btn-lg">View Booking</a>
        </p>
      </div>
      <div class="col-lg-6 mt-4 mt-lg-0">
        <div class="card shadow-sm">
          <div class="card-body">
            <h5 class="card-title">Cari Penerbangan</h5>
            <form class="row g-2" action="#" method="post">
              <div class="col-12">
                <input type="text" name="destination" class="form-control" placeholder="Destinasi" required>
                <div class="small text-muted mt-1">Tanggal berangkat</div>
              </div>
              <div class="col-md-6">
                <input type="date" name="checkin" class="form-control" required>
                <div class="small text-muted mt-1">Penumpang Dewasa</div>
              </div>
              <div class="col-md-6">
                <input type="date" name="checkout" class="form-control" required>
                <div class="small text-muted mt-1">Anak-anak</div>
              </div>
              <div class="col-md-6">
                <input type="number" name="adults" class="form-control" min="1" value="2" required>
              </div>
              <div class="col-md-6">
                <input type="number" name="children" class="form-control" min="0" value="0">
              </div>
              <div class="col-12">
                <button class="btn btn-primary w-100">Cari</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
  <style>
    .hero-bg {
      position: absolute;
      inset: 0;
      background: linear-gradient(rgba(13,110,253,0.28), rgba(13,110,253,0.28)),
                  url('https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?q=80&w=1470&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D') center/cover no-repeat;
      z-index: 1;
      filter: saturate(0.95) brightness(0.95);
    }

    .hero-bg + .container .card {
      background-color: rgba(255,255,255,0.96);
    }

    @media (max-width: 767px) {
      .hero-bg { background-position: center top; }
    }
  </style>
</section>

<div class="container my-4">

    <!-- FILTER TAB -->
    <div class="d-flex gap-2 mb-4 border-bottom pb-2">
        <button class="btn btn-light border rounded-pill px-4 active">Sekali Jalan</button>
        <button class="btn btn-light border rounded-pill px-4">Domestik</button>
        <button class="btn btn-light border rounded-pill px-4">Internasional</button>
    </div>

    <!-- JIKA ADMIN → TOMBOL TAMBAH -->
    @if(Auth::check() && Auth::user()->roles === 'admin')
    <div class="mb-3">
        <a href="/travel/create" class="btn btn-primary">+ Tambah Data Travel</a>
    </div>
    @endif

    <!-- GRID LIST FLIGHTS -->
    <div class="row g-4">

        @php
            // Dummy data
            $flights = [
                [
                    "airline" => "Scoot",
                    "logo" => "/images/plane1.png",
                    "from" => "Jakarta (CGK)",
                    "to" => "Singapore (SIN)",
                    "date" => "Rab, 3 Des 2025",
                    "price" => "928.700"
                ],
                [
                    "airline" => "AirAsia (Malaysia)",
                    "logo" => "/images/plane1.png",
                    "from" => "Kuala Lumpur (KUL)",
                    "to" => "Jakarta (CGK)",
                    "date" => "Rab, 10 Des 2025",
                    "price" => "819.100"
                ],
                [
                    "airline" => "Citilink",
                    "logo" => "/images/plane1.png",
                    "from" => "Singapore (SIN)",
                    "to" => "Jakarta (CGK)",
                    "date" => "Rab, 26 Nov 2025",
                    "price" => "1.432.700"
                ],
                [
                    "airline" => "AirAsia Indonesia",
                    "logo" => "/images/plane1.png",
                    "from" => "Jakarta (CGK)",
                    "to" => "Bangkok (DMK)",
                    "date" => "Sab, 29 Nov 2025",
                    "price" => "1.332.700"
                ],
            ];
        @endphp


        @foreach ($flights as $flight)
        <div class="col-md-6">
            <div class="flight-card p-3 rounded-4 shadow-sm d-flex justify-content-between align-items-center">
                <a href="/schedule" style="text-decoration: none; color: black;">
                    <div class="d-flex align-items-start gap-3">
                        <img src="{{ $flight['logo'] }}" width="55">

                        <div>
                            <div class="fw-semibold">{{ $flight['airline'] }}</div>
                                <div class="fw-bold mt-1" style="font-size: 1.05rem;">
                                    {{ $flight['from'] }} ↔ {{ $flight['to'] }}
                                </div>
                            <div class="text-muted small">{{ $flight['date'] }}</div>
                        </div>
                    </div>

                    <div class="text-end">
                        <div class="fw-bold text-primary" style="font-size: 1.2rem;">
                            Rp {{ $flight['price'] }}
                        </div>
                        <div class="text-muted small">Sekali Jalan</div>

                        <a href="/schedule" class="text-primary fw-bold text-decoration-none ms-2">
                            <span style="font-size: 1.5rem;">›</span>
                        </a>
                    </div>
                </a>
            </div>
        </div>
        @endforeach

    </div>

</div>


<style>
    .flight-card {
        background: #ffffff;
        border: 1px solid #e8eef7;
        transition: 0.2s;
    }
    .flight-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 15px rgba(0,0,0,0.08);
    }
    .btn-light.active {
        background: #e8f1ff;
        color: #0d6efd;
        border-color: #bcd7ff;
    }
</style>

<!-- Include Custom CSS & JS -->
<link rel="stylesheet" href="{{ asset('css/home.css') }}">
<script src="{{ asset('js/home.js') }}"></script>

@endsection
