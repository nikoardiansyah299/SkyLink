@extends('layout.master')

@section('title', 'Travel Ticket System - Home')

@section('content')

<!-- Hero -->
<section class="py-5 bg-primary text-light">
  <div class="container">
    <div class="row align-items-center">
      <div class="col-lg-6">
        <h1 class="display-5 fw-bold">Discover the World with Us</h1>
        <p class="lead">Explore breathtaking destinations and create unforgettable memories with our expertly crafted tours.</p>
        <p>
          <a href="{{ url('/travels') }}" class="btn btn-light btn-lg me-2">Start Exploring</a>
          <a href="{{ url('/tours') }}" class="btn btn-outline-light btn-lg">View Tours</a>
        </p>
      </div>
      <div class="col-lg-6 mt-4 mt-lg-0">
        <div class="card shadow-sm">
          <div class="card-body">
            <h5 class="card-title">Search Tours</h5>
            <form class="row g-2" action="#" method="post">
              <div class="col-12">
                <input type="text" name="destination" class="form-control" placeholder="Destination" required>
              </div>
              <div class="col-md-6">
                <input type="date" name="checkin" class="form-control" required>
              </div>
              <div class="col-md-6">
                <input type="date" name="checkout" class="form-control" required>
              </div>
              <div class="col-md-6">
                <input type="number" name="adults" class="form-control" min="1" value="2" required>
              </div>
              <div class="col-md-6">
                <input type="number" name="children" class="form-control" min="0" value="0">
              </div>
              <div class="col-12">
                <button class="btn btn-primary w-100">Search Tours</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
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
          <img src="https://lh3.googleusercontent.com/gps-cs-s/AG0ilSxyn1UgFn9RW4GBYoiu-LD9JGsTPXCjZZyGY1TleBqFKNVWPxWS57og_0ESOs6yv7dq1hnwKjxW1It0piRGfR9-xHRmHqt182CkdOkK7zFlxWa92apqVlQX53SDrHAQoh0TO84=s680-w680-h510-rw" class="card-img-top" alt="Machu Picchu" loading="lazy">
          <div class="card-body">
            <h5 class="card-title">Machu Picchu, Peru</h5>
            <p class="card-text text-muted">Trek through ancient Incan ruins and witness spectacular sites.</p>
          </div>
          <div class="card-footer bg-white border-0">
            <a href="#" class="btn btn-primary btn-sm">Explore</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Featured Tours (simple grid) -->
<section class="py-5">
  <div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h2 class="h4 mb-0">Featured Tours</h2>
      <a href="#" class="btn btn-sm btn-outline-primary">See All Tours</a>
    </div>
    <div class="row g-4">
      @foreach (range(1,4) as $i)
      <div class="col-lg-3 col-md-6">
        <div class="card h-100 shadow-sm">
          <img src="https://source.unsplash.com/600x400/?tour,travel,landscape,{{$i}}" class="card-img-top" alt="Tour {{$i}}" loading="lazy">
          <div class="card-body">
            <h5 class="card-title">Sample Tour {{$i}}</h5>
            <p class="card-text text-muted">Short description of the tour highlighting attractions and duration.</p>
          </div>
          <div class="card-footer bg-white border-0 d-flex justify-content-between align-items-center">
            <a href="#" class="btn btn-primary btn-sm">Explore Tour</a>
            <small class="text-muted">4.8</small>
          </div>
        </div>
      </div>
      @endforeach
    </div>
  </div>
</section>

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
          <div class="fw-bold">Saul Goodman</div>
          <small class="text-muted">CEO &amp; Founder</small>
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

<!-- Call to Action -->
<section class="py-5 text-center">
  <div class="container">
    <h3 class="mb-3">Ready to Start Your Next Adventure?</h3>
    <p class="text-muted mb-4">Discover breathtaking destinations, create unforgettable memories, and explore the world with our expertly crafted travel packages.</p>
    <a href="#" class="btn btn-primary btn-lg me-2">Explore Destinations</a>
    <a href="#" class="btn btn-outline-secondary btn-lg">Plan Your Trip</a>
  </div>
</section>

@endsection