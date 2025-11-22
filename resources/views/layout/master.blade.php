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

<!-- footer -->
    <footer class="bg-dark text-light py-4 mt-5">
      <div class="container">
        <div class="row align-items-start">
          <div class="col-md-3 mb-3">
            <div class="small text-muted mb-2">Follow us on:</div>
            <div class="d-flex align-items-center gap-2">
              <!-- YouTube -->
              <a href="#" class="d-inline-flex align-items-center justify-content-center rounded-circle shadow-sm" style="width:40px;height:40px;background:#FF5252;color:#fff;">
                <img src="https://cdn.jsdelivr.net/npm/simple-icons@v9/icons/youtube.svg" width="18" height="18" alt="YouTube" style="filter: brightness(0) invert(1);">
              </a>
              
              <!-- X (Twitter) -->
              <a href="#" class="d-inline-flex align-items-center justify-content-center rounded-circle shadow-sm" style="width:40px;height:40px;background:#33C7FF;color:#fff;">
                <img src="https://cdn.jsdelivr.net/npm/simple-icons@v9/icons/twitter.svg" width="18" height="18" alt="X" style="filter: brightness(0) invert(1);">
              </a>
              
              <!-- Facebook -->
              <a href="#" class="d-inline-flex align-items-center justify-content-center rounded-circle shadow-sm" style="width:40px;height:40px;background:#1877F2;color:#fff;">
                <img src="https://cdn.jsdelivr.net/npm/simple-icons@v9/icons/facebook.svg" width="18" height="18" alt="Facebook" style="filter: brightness(0) invert(1);">
              </a>
              
              <!-- Instagram -->
              <a href="#" class="d-inline-flex align-items-center justify-content-center rounded-circle shadow-sm" style="width:40px;height:40px;background:#FFC107;color:#fff;">
                <img src="https://cdn.jsdelivr.net/npm/simple-icons@v9/icons/instagram.svg" width="18" height="18" alt="Instagram" style="filter: brightness(0) invert(1);">
              </a>
            </div>
          </div>
          
          <div class="col-md-3 mb-3 text-center">
            <h6 class="fw-bold">Discover</h6>
            <ul class="list-unstyled small">
              <li><a href="/" class="text-light text-decoration-none">Home</a></li>
              <li><a href="/booking" class="text-light text-decoration-none">My Booking</a></li>
              <li><a href="/travel" class="text-light text-decoration-none">Destination</a></li>
            </ul>
          </div>
          
          <div class="col-md-3 mb-3 text-center">
            <h6 class="fw-bold">Quick Links</h6>
            <ul class="list-unstyled small">
              <li><a href="/login" class="text-light text-decoration-none">Login</a></li>
              <li><a href="/register" class="text-light text-decoration-none">Register</a></li>
              <li><a href="/travels" class="text-light text-decoration-none">Available Travels</a></li>
            </ul>
          </div>
          
          <div class="col-md-3 mb-3 text-center">
            <h6 class="fw-bold">Contact</h6>
            <p class="text-muted small mb-1"><span class="text-light">Address : Lorem</span></p>
            <p class="text-muted small mb-1"><span class="text-light">Email : xyz@mail.com</span></p>
            <p class="text-muted small mb-0"><span class="text-light">Phone : 00022200222</span></p>
          </div>
        </div>
        
        <hr class="bg-secondary">
        <div class="text-center text-light medium">
          <p>&copy; 2025 SkyLink. All rights reserved.</p>
        </div>
      </div>
    </footer>
  </body>
</html>