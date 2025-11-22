@extends('layout.master')

@section('title', 'Travel Ticket System - Home')

@section('content')

<!-- Hero -->
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

<!-- About / Why Us -->
<section class="py-5">
  <div class="container">
    <div class="row g-4 align-items-center">
      <div class="col-md-6">
        <h3>Creating Unforgettable Travel Experiences</h3>
        <p class="text-muted">Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium.</p>
        <ul class="list-unstyled">
          <li class="mb-2">• Expert local guides in every destination</li>
          <li class="mb-2">• Customized itineraries for every traveler</li>
          <li class="mb-2">• 24/7 customer support throughout your journey</li>
        </ul>
      </div>
      <div class="col-md-6">
        <img src="https://images.unsplash.com/photo-1530521954074-e64f6810b32d?q=80&w=1470&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" alt="Travel" class="img-fluid rounded" loading="lazy">
      </div>
    </div>
  </div>
</section>

<!-- Featured Destinations -->
<section class="py-5 bg-light">
  <div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h2 class="h4 mb-0">Featured Destinations</h2>
      <a href="#" class="btn btn-sm btn-outline-primary">View All</a>
    </div>
    <div class="row g-4">
      <div class="col-md-4">
        <div class="card h-100">
          <img src="https://www.greekexclusiveproperties.com/wp-content/uploads/2019/10/Santorini-Declared-No1-Island-in-the-World-.jpg" class="card-img-top" alt="Santorini" loading="lazy">
          <div class="card-body">
            <h5 class="card-title">Santorini, Greece</h5>
            <p class="card-text text-muted">Experience breathtaking sunsets and pristine villages.</p>
          </div>
          <div class="card-footer bg-white border-0">
            <a href="#" class="btn btn-primary btn-sm">Explore</a>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card h-100">
          <img src="https://bankraya.co.id/uploads/insights/jO3TRUmMuBAuyilKHgu9Ovjfs3nFoubWiSSjB3Pn.jpg" class="card-img-top" alt="Bali" loading="lazy">
          <div class="card-body">
            <h5 class="card-title">Bali, Indonesia</h5>
            <p class="card-text text-muted">Discover tropical paradise with ancient temples and beaches.</p>
          </div>
          <div class="card-footer bg-white border-0">
            <a href="#" class="btn btn-primary btn-sm">Explore</a>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card h-100">
          <img src="https://www.agoda.com/wp-content/uploads/2019/03/Paris-airport-Paris-Eiffel-Tower.jpg" class="card-img-top" alt="Machu Picchu">
          <div class="card-body">
            <h5 class="card-title">Paris, France</h5>
            <p class="card-text text-muted">Nikmati pemandangan di atas Eiffel Tower dan nikmati kuliner di berbagai restoran khas Perancis.</p>
          </div>
          <div class="card-footer bg-white border-0">
            <a href="#" class="btn btn-primary btn-sm">Explore</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Services Highlight with Tabs -->
<section class="py-5">
  <div class="container">
    <div class="text-center mb-5">
      <h2 class="h3 fw-bold">Explore Our Product and Services</h2>
    </div>
    
    <!-- Tab Navigation -->
    <div class="d-flex gap-2 mb-4 justify-content-center flex-wrap">
      <button class="service-tab btn btn-outline-primary rounded-pill px-4 py-2 active" data-service="meals">
        Meals
      </button>
      <button class="service-tab btn btn-outline-primary rounded-pill px-4 py-2" data-service="baggage">
        Baggage
      </button>
      <button class="service-tab btn btn-outline-primary rounded-pill px-4 py-2" data-service="seat">
        Seat
      </button>
    </div>

    <!-- Service Content -->
    <div class="service-content">

      <div class="service-item active" id="meals" data-service="meals">
        <div class="card border-0 shadow-sm">
          <div class="row g-0 align-items-center">
            <div class="col-md-5">
              <img src="https://www.foodandwine.com/thmb/eOZthCo0FebSOODOCRQYSQHChzc=/1500x0/filters:no_upscale():max_bytes(150000):strip_icc()/The-Best-Foods-to-Order-on-a-Flight-According-to-Chefs-FT-BLOG1222-4c93bdb4cdaa4c469a75753f3b5ae00c.jpg" 
                   alt="Meals" class="img-fluid rounded-start" loading="lazy" style="height: 300px; object-fit: cover;">
            </div>
            <div class="col-md-7">
              <div class="card-body p-4">
                <h5 class="card-title fw-bold">Culinary Experiences & In-Flight Dining</h5>
                <p class="card-text text-muted mb-3">
                  On most long-haul flights, a complimentary hot meal service is included, with options that often feature a choice of international or local cuisine.
                </p>
                <p class="card-text text-muted">
                  For shorter trips, a selection of premium snacks and beverages is available.
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="service-item" id="baggage" data-service="baggage">
        <div class="card border-0 shadow-sm">
          <div class="row g-0 align-items-center">
            <div class="col-md-5">
              <img src="https://autourasia.com/uploads/Travel-Guide-Thailand/thailand-useful-information/airline-baggage-in-thailand/700-bagage-a-main.jpg" 
                   alt="Baggage" class="img-fluid rounded-start" loading="lazy" style="height: 300px; object-fit: cover;">
            </div>
            <div class="col-md-7">
              <div class="card-body p-4">
                <h5 class="card-title fw-bold">Premium Baggage Services</h5>
                <p class="card-text text-muted mb-3">
                  Travel with confidence with our generous baggage allowances. Check in your luggage worry-free with our reliable handling system.
                </p>
                <p class="card-text text-muted">
                  Priority baggage handling ensures your bags arrive safely and on time at your destination.
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="service-item" id="seat" data-service="seat">
        <div class="card border-0 shadow-sm">
          <div class="row g-0 align-items-center">
            <div class="col-md-5">
              <img src="https://media.cnn.com/api/v1/images/stellar/prod/171114182232-airline-seats-empty-stock.jpg?q=w_1110,c_fill" 
                   alt="Seat" class="img-fluid rounded-start" loading="lazy" style="height: 300px; object-fit: cover;">
            </div>
            <div class="col-md-7">
              <div class="card-body p-4">
                <h5 class="card-title fw-bold">Comfortable Seating Options</h5>
                <p class="card-text text-muted mb-3">
                  Choose from various seat options including economy, premium economy, and business class with enhanced comfort and amenities.
                </p>
                <p class="card-text text-muted">
                  Enjoy extra legroom, wider seats, and premium bedding on long-haul flights for maximum comfort during your journey.
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<style>
  .service-tab {
    cursor: pointer;
    transition: all 0.3s ease;
    font-weight: 600;
  }

  .service-tab:hover {
    background-color: #0d6efd !important;
    color: white !important;
    transform: translateY(-2px);
  }

  .service-tab.active {
    background-color: #0d6efd !important;
    color: white !important;
  }

  .service-item {
    display: none;
    animation: fadeIn 0.5s ease;
  }

  .service-item.active {
    display: block;
  }

  @keyframes fadeIn {
    from {
      opacity: 0;
      transform: translateY(10px);
    }
    to {
      opacity: 1;
      transform: translateY(0);
    }
  }
</style>

<script>
  document.querySelectorAll('.service-tab').forEach(tab => {
    tab.addEventListener('click', function() {
      const service = this.getAttribute('data-service');
      
      // Remove active class from all tabs and items
      document.querySelectorAll('.service-tab').forEach(t => t.classList.remove('active'));
      document.querySelectorAll('.service-item').forEach(item => item.classList.remove('active'));
      
      // Add active class to clicked tab and corresponding item
      this.classList.add('active');
      document.getElementById(service).classList.add('active');
    });
  });
</script>

<!-- Testimonials -->
<section class="py-5 bg-light">
  <div class="container">
    <div class="text-center mb-4">
      <h2 class="h4">Testimonials</h2>
      <p class="text-muted">What our customers are saying</p>
    </div>
    <div class="row">
      <div class="col-md-4">
        <div class="card p-3">
          <p class="mb-2">"Amazing experience! Highly recommend."</p>
          <div class="fw-bold">Saul Hudson</div>
          <small class="text-muted">Influencer</small>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card p-3">
          <p class="mb-2">"Great service and attention to detail."</p>
          <div class="fw-bold">Sara Wilsson</div>
          <small class="text-muted">Designer</small>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card p-3">
          <p class="mb-2">"Unforgettable trip, everything was perfect."</p>
          <div class="fw-bold">Jena Karlis</div>
          <small class="text-muted">Traveler</small>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="py-5 text-center">
  <div class="container">
    <h3 class="mb-3">Ready to Start Your Next Adventure?</h3>
    <p class="text-muted mb-4">Discover breathtaking destinations, create unforgettable memories, and explore the world with our expertly crafted travel packages.</p>
    <a href="#" class="btn btn-primary btn-lg me-2">Explore Destinations</a>
    <a href="#" class="btn btn-outline-secondary btn-lg">Plan Your Trip</a>
  </div>
</section>

@endsection