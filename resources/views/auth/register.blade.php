@extends('layouts.auth')

@section('title', 'Daftar')
@section('meta_description', 'Buat akun SMPL baru')

@section('content')
    <h2 class="auth-card-title">Buat Akun Baru</h2>
    <p class="auth-card-desc">Daftar untuk mulai menggunakan SMPL</p>

    @if ($errors->any())
        <div class="alert alert-error">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="10"/>
                <line x1="15" y1="9" x2="9" y2="15"/>
                <line x1="9" y1="9" x2="15" y2="15"/>
            </svg>
            <div>
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        </div>
    @endif

    <form method="POST" action="{{ route('register') }}" id="registerForm">
        @csrf

        <!-- Name Field -->
        <div class="form-group">
            <label for="name" class="form-label">Nama Lengkap</label>
            <div class="form-input-wrapper">
                <input
                    type="text"
                    id="name"
                    name="name"
                    class="form-input {{ $errors->has('name') ? 'is-invalid' : '' }}"
                    placeholder="Masukkan nama lengkap"
                    value="{{ old('name') }}"
                    required
                    autofocus
                >
                <svg class="form-input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                    <circle cx="12" cy="7" r="4"/>
                </svg>
            </div>
        </div>

        <!-- Email Field -->
        <div class="form-group">
            <label for="email" class="form-label">Email</label>
            <div class="form-input-wrapper">
                <input
                    type="email"
                    id="email"
                    name="email"
                    class="form-input {{ $errors->has('email') ? 'is-invalid' : '' }}"
                    placeholder="nama@email.com"
                    value="{{ old('email') }}"
                    required
                >
                <svg class="form-input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                    <polyline points="22,6 12,13 2,6"/>
                </svg>
            </div>
        </div>

        <!-- Password Field -->
        <div class="form-group">
            <label for="password" class="form-label">Password</label>
            <div class="form-input-wrapper">
                <input
                    type="password"
                    id="password"
                    name="password"
                    class="form-input {{ $errors->has('password') ? 'is-invalid' : '' }}"
                    placeholder="Minimal 8 karakter"
                    required
                >
                <svg class="form-input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                    <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                </svg>
                <button type="button" class="password-toggle" aria-label="Toggle password visibility">
                    <svg class="eye-open" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                        <circle cx="12" cy="12" r="3"/>
                    </svg>
                    <svg class="eye-closed" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="display:none;">
                        <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/>
                        <line x1="1" y1="1" x2="23" y2="23"/>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Confirm Password Field -->
        <div class="form-group">
            <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
            <div class="form-input-wrapper">
                <input
                    type="password"
                    id="password_confirmation"
                    name="password_confirmation"
                    class="form-input"
                    placeholder="Ulangi password"
                    required
                >
                <svg class="form-input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                </svg>
                <button type="button" class="password-toggle" aria-label="Toggle password visibility">
                    <svg class="eye-open" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                        <circle cx="12" cy="12" r="3"/>
                    </svg>
                    <svg class="eye-closed" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="display:none;">
                        <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/>
                        <line x1="1" y1="1" x2="23" y2="23"/>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Submit -->
        <button type="submit" class="btn-primary" id="registerBtn" style="margin-top: 8px;">
            Daftar Akun
        </button>
    </form>
@endsection

@section('footer')
    <div class="auth-footer">
        Sudah punya akun? <a href="{{ route('login') }}">Masuk di sini</a>
    </div>
@endsection
