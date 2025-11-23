@extends('layouts.app')

@section('content')
<div class="container mt-4">

    <div class="row justify-content-center">
        <div class="col-md-6">

            <h3 class="mb-4">Profil Pengguna</h3>

            {{-- ALERT SUCCESS / ERROR --}}
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- FORM UPDATE PROFIL --}}
            <div class="card mb-4">
                <div class="card-header">Informasi Akun</div>

                <div class="card-body">
                    <form action="{{ route('profile.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        {{-- Username --}}
                        <div class="form-group mb-3">
                            <label for="username">Username</label>
                            <input type="text" 
                                   id="username" 
                                   name="username" 
                                   class="form-control" 
                                   value="{{ old('username', auth()->user()->username) }}">
                        </div>

                        {{-- Email --}}
                        <div class="form-group mb-3">
                            <label for="email">Email</label>
                            <input type="email" 
                                   id="email" 
                                   name="email" 
                                   class="form-control" 
                                   value="{{ old('email', auth()->user()->email) }}">
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Update Profil</button>
                    </form>
                </div>
            </div>


            {{-- FORM UPDATE PASSWORD --}}
            <div class="card">
                <div class="card-header">Ganti Password</div>

                <div class="card-body">
                    <form action="{{ route('profile.password') }}" method="POST">
                        @csrf
                        @method('PUT')

                        {{-- Password Lama --}}
                        <div class="form-group mb-3">
                            <label for="current_password">Password Lama</label>
                            <input type="password" 
                                   id="current_password" 
                                   name="current_password" 
                                   class="form-control" 
                                   required>
                        </div>

                        {{-- Password Baru --}}
                        <div class="form-group mb-3">
                            <label for="new_password">Password Baru</label>
                            <input type="password"
                                   id="new_password" 
                                   name="new_password" 
                                   class="form-control"
                                   required>
                        </div>

                        {{-- Konfirmasi Password Baru --}}
                        <div class="form-group mb-3">
                            <label for="new_password_confirmation">Konfirmasi Password Baru</label>
                            <input type="password"
                                   id="new_password_confirmation" 
                                   name="new_password_confirmation" 
                                   class="form-control"
                                   required>
                        </div>

                        <button type="submit" class="btn btn-warning w-100">Ganti Password</button>
                    </form>
                </div>
            </div>

        </div>
    </div>

</div>
@endsection
