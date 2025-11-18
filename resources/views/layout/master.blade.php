<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title', 'Travel Ticket System')</title>

  <!--  Bootstrap CSS -->
  <link 
    href="{{ asset('/css/bootstrap.min.css')}}" rel="stylesheet">
  <style>
    body {
      display: flex;
      flex-direction: column;
      min-height: 100vh;
    }
    main {
      flex: 1;
    }
    footer {
      margin-top: auto;
    }
  </style>
</head>

<body>
  <!--  Navbar -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
    <div class="container">
      <a class="navbar-brand fw-bold text-light" href="/">🌍 SkyLink</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navmenu">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navmenu">
        <ul class="navbar-nav ms-auto">
          @if(isset($user) || session('user_id'))
            <li class="nav-item"><a class="nav-link text-light" href="/">Dashboard</a></li>
            <li class="nav-item"><a class="nav-link text-light" href="/travels">Travels</a></li>
            <li class="nav-item"><a class="nav-link text-light" href="/bookings">My Bookings</a></li>
            @if(isset($isAdmin) || session('is_admin'))
              <li class="nav-item"><a class="nav-link text-light" href="/travel/create">Manage Travels</a></li>
            @endif
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle text-light" href="#" id="userMenu" role="button" data-bs-toggle="dropdown">
                {{ session('username', 'User') }}
              </a>
              <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item" href="/edit-password">Edit Password</a></li>
                <li><hr class="dropdown-divider"></li>
                <li>
                  <form method="POST" action="{{ route('logout') }}" style="display:inline;">
                    @csrf
                    <button type="submit" class="dropdown-item" style="background:none; border:none; cursor:pointer;">Logout</button>
                  </form>
                </li>
              </ul>
            </li>
          @else
            <li class="nav-item"><a class="nav-link text-light" href="/login">Login</a></li>
            <li class="nav-item"><a class="nav-link text-light" href="/register">Register</a></li>
          @endif
        </ul>
      </div>
    </div>
  </nav>

  <!-- Main Content -->
  <main>
    @yield('content')
  </main>

  <!-- Footer -->
  <footer class="bg-dark text-light py-4 mt-5">
    <div class="container">
      <div class="row">
        <div class="col-md-4 mb-3">
          <h5 class="fw-bold">🌍 SkyLink</h5>
          <p class="text-muted small">Your trusted travel ticket booking platform</p>
        </div>
        <div class="col-md-4 mb-3">
          <h6 class="fw-bold">Quick Links</h6>
          <ul class="list-unstyled small">
            <li><a href="/" class="text-muted text-decoration-none">Home</a></li>
            <li><a href="/travels" class="text-muted text-decoration-none">Available Travels</a></li>
            <li><a href="/bookings" class="text-muted text-decoration-none">My Bookings</a></li>
          </ul>
        </div>
        <div class="col-md-4 mb-3">
          <h6 class="fw-bold">Contact</h6>
          <p class="text-muted small">
            Email: support@travelid.com<br>
            Phone: +1-800-TRAVEL-ID
          </p>
        </div>
      </div>
      <hr class="bg-secondary">
      <div class="text-center text-muted small">
        <p>&copy; 2025 TravelID. All rights reserved.</p>
      </div>
    </div>
  </footer>
</body>
</html>