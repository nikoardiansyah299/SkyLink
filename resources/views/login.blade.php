@extends('layout.master')

@section('title', 'Login - TravelID')

@section('content')
<div class="container py-5">
	<div class="row justify-content-center">
		<div class="col-md-5">
			<div class="card shadow-sm">
				<div class="card-body">
					<h3 class="card-title mb-4">Login</h3>

					@if(session('error'))
						<div class="alert alert-danger">{{ session('error') }}</div>
					@endif
					@if(session('success'))
						<div class="alert alert-success">{{ session('success') }}</div>
					@endif

					<form method="POST" action="{{ url('/login') }}">
						@csrf
						<div class="mb-3">
							<label class="form-label">Username</label>
							<input class="form-control" name="username" value="{{ old('username') }}" required>
							@error('username') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
						</div>
						<div class="mb-3">
							<label class="form-label">Password</label>
							<input class="form-control" name="password" type="password" required>
							@error('password') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
						</div>
						<div class="d-grid">
							<button class="btn btn-primary">Login</button>
						</div>
					</form>
				</div>
			</div>
			<p class="text-center mt-3 small text-muted">Belum punya akun? <a href="{{ url('/register') }}">Daftar</a></p>
		</div>
	</div>
</div>
@endsection