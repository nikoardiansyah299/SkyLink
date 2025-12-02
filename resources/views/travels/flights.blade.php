@extends('layout.master')
@section('title', 'TravelID | Explore the World')

@section('content')

<!-- HERO SECTION -->
<section class="py-5 text-light position-relative">
  <div class="hero-bg" aria-hidden="true"></div>

  <div class="container position-relative" style="z-index:2;">
    <div class="row align-items-center">
      <div class="col-lg-6">
        <h1 class="display-5 fw-bold">Check & Buy Ticket Prices Here</h1>
        <p class="lead">Temukan destinasi memukau dan ciptakan kenangan tak terlupakan melalui layanan kami.</p>
        <p>
          <a href="{{ url('/travels') }}" class="btn btn-light btn-lg me-2">Start Exploring</a>
          <a href="{{ url('/bookings') }}" class="btn btn-outline-light btn-lg">View Booking</a>
        </p>
      </div>
            <div class="col-lg-6 mt-4 mt-lg-0">
        <div class="card shadow-sm">
          <div class="card-body">
            <h5 class="card-title">Cari Penerbangan</h5>
<<<<<<< HEAD
            <form class="row g-2" action="{{ route('travels.store') }}" method="post">
              @csrf
=======
            <form class="row g-2" action="{{ url('/travels') }}" method="get">
>>>>>>> e5b7a928db64dbd57fde9fef74848eaa19a1908a
              <div class="col-12">
                <input type="text" name="destination" class="form-control" placeholder="Destinasi" value="{{ request('destination') }}">
                <div class="small text-muted mt-1">Tanggal berangkat</div>
              </div>
              <div class="col-md-6">
                <input type="date" name="checkin" class="form-control" value="{{ request('checkin') }}">
                <div class="small text-muted mt-1">Penumpang Dewasa</div>
              </div>
              <div class="col-md-6">
                <input type="date" name="checkout" class="form-control" value="{{ request('checkout') }}">
                <div class="small text-muted mt-1">Anak-anak</div>
              </div>
              <div class="col-md-6">
                <input type="number" name="adults" class="form-control" min="1" value="{{ request('adults', 2) }}">
              </div>
              <div class="col-md-6">
                <input type="number" name="children" class="form-control" min="0" value="{{ request('children', 0) }}">
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

    <a href="{{ route('travels.index') }}"
        class="btn btn-light border rounded-pill px-4 {{ $kategori === null ? 'active' : '' }}">
        Sekali Jalan
    </a>

    <a href="{{ route('travels.index', ['kategori' => 'domestik']) }}"
        class="btn btn-light border rounded-pill px-4 {{ $kategori === 'domestik' ? 'active' : '' }}">
        Domestik
    </a>

    <a href="{{ route('travels.index', ['kategori' => 'internasional']) }}"
        class="btn btn-light border rounded-pill px-4 {{ $kategori === 'internasional' ? 'active' : '' }}">
        Internasional
    </a>

</div>


    <!-- JIKA ADMIN → TOMBOL TAMBAH -->
    @if(Auth::check() && Auth::user()->roles === 'admin')
    <div class="mb-3">
        <a href="create/flights" class="btn btn-primary">+ Tambah Data Travel</a>
    </div>
    @endif

    <!-- GRID LIST FLIGHTS -->
    <div class="row g-4">

        @foreach ($flights as $f)
          <div style="
              width: 100%;
              background: #fff;
              border-radius: 15px;
              padding: 20px;
              margin-bottom: 20px;
              box-shadow: 0 2px 10px rgba(0,0,0,0.08);
              display: flex;
              justify-content: space-between;
              align-items: center;
          ">

              <!-- LOGO MASKAPAI -->
              <div style="display:flex; align-items:center; gap:15px;">
                  <img src="{{ asset(ltrim($f->maskapai->logo, '/')) }}"
                    alt="logo"
                    style="width:55px; height:auto; border-radius:8px;">

                  <div>
                      <div style="font-size: 18px; font-weight: 600;">
                          {{ $f->maskapai->nama_maskapai }}
                      </div>

                      <div style="margin-top: 4px; font-size: 17px; font-weight: bold;">
                          {{ $f->bandaraAsal->kota }} ({{ $f->bandaraAsal->kode_iata }})
                          ↔
                          {{ $f->bandaraTujuan->kota }} ({{ $f->bandaraTujuan->kode_iata }})
                      </div>

                      <div style="margin-top: 4px; color: #555;">
                          {{ \Carbon\Carbon::parse($f->tanggal)->locale('id')->isoFormat('dddd, D MMM YYYY') }}
                      </div>
                  </div>
              </div>

              <div style="text-align: right;">
                <div style="font-size: 20px; font-weight: bold; color: #0066FF;">
                    Rp {{ number_format($f->harga, 0, ',', '.') }}
                </div>

                <div style="color: #888; font-size: 14px;">
                    Sekali Jalan
                </div>

                <a href="{{ route('tiket.create', $f->id) }}" class="btn btn-primary mt-2 w-100">
                    Pesan Tiket
                </a>
              </div>


          </div>
        @endforeach


    </div>

    @if(isset($returnFlights) && $returnFlights)
    <hr class="my-4">
    <h4>Penerbangan Pulang ({{ request('checkout') }})</h4>
    <div class="row g-4 mt-2">
      @foreach ($returnFlights as $f)
        <div style="
          width: 100%;
          background: #fff;
          border-radius: 15px;
          padding: 20px;
          margin-bottom: 20px;
          box-shadow: 0 2px 10px rgba(0,0,0,0.08);
          display: flex;
          justify-content: space-between;
          align-items: center;
        ">

          <div>
            <div style="font-size: 18px; font-weight: 600;">
              {{ $f->nama_maskapai }}
            </div>

            <div style="margin-top: 4px; font-size: 17px; font-weight: bold;">
              {{ $f->asal->kota }} ({{ $f->asal->kode_iata }})
              ↔
              {{ $f->tujuan->kota }} ({{ $f->tujuan->kode_iata }})
            </div>

            <div style="margin-top: 4px; color: #555;">
              {{ \Carbon\Carbon::createFromFormat('Y-m-d', $f->tanggal)->locale('id')->isoFormat('dddd, D MMM YYYY') }}
            </div>
          </div>

          <div style="text-align: right;">
            <div style="font-size: 20px; font-weight: bold; color: #0066FF;">
              Rp {{ number_format($f->harga, 0, ',', '.') }}
            </div>

            <div style="color: #888; font-size: 14px;">
              Sekali Jalan
            </div>
          </div>

        </div>
      @endforeach
    </div>
    @endif

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
