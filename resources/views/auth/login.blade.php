{{-- ============================================ --}}
{{-- PAGE: Login Admin                           --}}
{{-- Route: GET  /login  → tampilkan form       --}}
{{-- Route: POST /login  → proses login         --}}
{{-- Controller: AuthController@showLogin       --}}
{{--             AuthController@login           --}}
{{-- ============================================ --}}

@extends('layouts.app')

@section('title', 'Login - SumberManis')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/login.css') }}">
@endpush

@section('content')

    {{-- Header --}}
    <header class="header">
        <div class="header-content">
            <div class="header-left">
                <a href="{{ route('user.index') }}" class="logo-link">
                    <i class="bi bi-cart4"></i> SumberManis
                </a>
            </div>
            <div class="header-center">
                <h1>Login</h1>
                <p>Masukkan akun Anda untuk melanjutkan</p>
            </div>
            <div class="header-right"></div>
        </div>
    </header>

    {{-- Form Login --}}
    <main>
        <div class="auth-container">
            <div class="auth-box">

                {{-- Ikon & Judul --}}
                <div style="text-align: center; margin-bottom: 2rem;">
                    <div style="font-size: 3rem;"><i class="bi bi-person-fill-lock"></i></div>
                    <h2>Login ke Admin</h2>
                    <p style="color: #666;">Masukkan email dan password</p>
                </div>

                {{-- Pesan Error dari Laravel --}}
                @if (session('error'))
                    <div class="alert alert-danger" style="padding: 12px; border-radius: 8px; background: #fde8e8; color: #c0392b; margin-bottom: 1rem;">
                        <i class="bi bi-exclamation-circle"></i> {{ session('error') }}
                    </div>
                @endif

                {{-- Pesan Error Validasi --}}
                @if ($errors->any())
                    <div class="alert alert-danger" style="padding: 12px; border-radius: 8px; background: #fde8e8; color: #c0392b; margin-bottom: 1rem;">
                        @foreach ($errors->all() as $error)
                            <div><i class="bi bi-dot"></i> {{ $error }}</div>
                        @endforeach
                    </div>
                @endif

                {{-- Form --}}
                <form method="POST" action="{{ route('auth.login') }}">
                    @csrf

                    <div class="form-groupp">
                        <label for="email">Email</label>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            placeholder="Ketik email Anda"
                            value="{{ old('email') }}"
                            required
                            autofocus
                        >
                        <i class="bi bi-person-fill" style="position:absolute; right:14px; top:38px; color:#aaa;"></i>
                    </div>

                    <div class="form-groupp">
                        <label for="password">Password</label>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            placeholder="Password"
                            required
                        >
                    </div>

                    <button type="submit" id="btnlogin" class="btn btn-primary" style="width:100%; padding: 12px;">
                        <i class="bi bi-lock-fill"></i> Login
                    </button>
                </form>

                <div class="auth-link">
                    <p style="margin-top: 10px;">
                        <a href="{{ route('user.index') }}">← Kembali ke Beranda</a>
                    </p>
                </div>

            </div>
        </div>
    </main>

    @include('partials.footer')

@endsection
