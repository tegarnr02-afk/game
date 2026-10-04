@extends('layouts.game')

@section('title', $mode === 'login' ? 'Masuk' : 'Daftar')

@section('content')
  <div class="lg-wrap" x-data="{ show: false }">
    <div class="lg-stage {{ $mode === 'register' ? 'lg-register' : '' }}"
      style="--lg-bg-mobile: url('{{ asset('images/login-mobile.webp') }}'); background-image: url('{{ asset('images/login.webp') }}');">

      <img class="lg-logo" src="{{ asset('images/logo-desktop.webp') }}" alt="Dermatch">

      <img class="lg-logo-mobile" src="{{ asset('images/logo-desktop.webp') }}" alt="Dermatch">

      <div class="lg-panel">
        <div class="lg-inner">

         

          <h1 class="lg-title">
            {{ $mode === 'login' ? 'Selamat Datang di' : 'Gabung di' }}
            <span>Dermatch!</span>
          </h1>
          <p class="lg-sub">Mix, care, and glow your skin!</p>

          <form method="POST" action="{{ $mode === 'login' ? route('login.submit') : route('register.submit') }}"
            novalidate>
            @csrf

            @if ($mode === 'register')
              <div class="lg-field">
                <svg class="lg-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                  stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                  <circle cx="12" cy="8" r="4" />
                  <path d="M4 21c0-4 4-6 8-6s8 2 8 6" />
                </svg>
                <input class="lg-input" type="text" name="name" value="{{ old('name') }}" placeholder="Nama"
                  aria-label="Nama" autocomplete="name" required>
              </div>
              @error('name')
              <p class="lg-error">{{ $message }}</p> @enderror
            @endif

            <div class="lg-field">
              <svg class="lg-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <rect x="3" y="5" width="18" height="14" rx="2" />
                <path d="m3 7 9 6 9-6" />
              </svg>
              <input class="lg-input" type="email" name="email" value="{{ old('email') }}"
                placeholder="Email atau Username" aria-label="Email" autocomplete="email" required>
            </div>
            @error('email')
            <p class="lg-error">{{ $message }}</p> @enderror

            <div class="lg-field">
              <svg class="lg-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <rect x="5" y="11" width="14" height="9" rx="2" />
                <path d="M8 11V8a4 4 0 0 1 8 0v3" />
              </svg>
              <input class="lg-input lg-input-pass" :type="show ? 'text' : 'password'" name="password"
                placeholder="Password" aria-label="Password"
                autocomplete="{{ $mode === 'login' ? 'current-password' : 'new-password' }}" required>
              <button type="button" class="lg-eye" @click="show = !show"
                :aria-label="show ? 'Sembunyikan password' : 'Tampilkan password'">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"
                  stroke-linejoin="round" aria-hidden="true">
                  <path d="M2 12s4-7 10-7 10 7 10 7-4 7-10 7S2 12 2 12Z" />
                  <circle cx="12" cy="12" r="3" />
                  <path x-show="!show" d="M3 3l18 18" />
                </svg>
              </button>
            </div>
            @error('password')
            <p class="lg-error">{{ $message }}</p> @enderror

            @if ($mode === 'register')
              <div class="lg-field">
                <svg class="lg-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                  stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                  <rect x="5" y="11" width="14" height="9" rx="2" />
                  <path d="M8 11V8a4 4 0 0 1 8 0v3" />
                </svg>
                <input class="lg-input" :type="show ? 'text' : 'password'" name="password_confirmation"
                  placeholder="Ulangi password" aria-label="Ulangi password" autocomplete="new-password" required>
              </div>
          @else
  <div class="lg-remember-row">
    <label class="lg-remember">
      <input type="checkbox" name="remember" value="1" checked> Ingat saya
    </label>
  </div>
@endif

            <button type="submit" class="lg-submit">
              <span>{{ $mode === 'login' ? 'Masuk' : 'Daftar' }}</span>
              <i aria-hidden="true">&#10140;</i>
            </button>
          </form>

          <div class="lg-divider"><span>atau {{ $mode === 'login' ? 'masuk' : 'daftar' }} dengan</span></div>

          <div class="lg-social-row">
           <a href="{{ route('auth.google') }}" class="lg-social-btn lg-google-btn-sm">
              <svg viewBox="0 0 24 24" aria-hidden="true" width="16" height="16">
                <path fill="#4285F4"
                  d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" />
                <path fill="#34A853"
                  d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" />
                <path fill="#FBBC05"
                  d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l3.66-2.84z" />
                <path fill="#EA4335"
                  d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" />
              </svg>
              Google
            </a>
          </div>

          {{-- Desktop: full-width Google only --}}
          <a href="{{ route('auth.google') }}" class="lg-google-btn">
            <svg viewBox="0 0 24 24" aria-hidden="true" width="18" height="18">
              <path fill="#4285F4"
                d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" />
              <path fill="#34A853"
                d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" />
              <path fill="#FBBC05"
                d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l3.66-2.84z" />
              <path fill="#EA4335"
                d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" />
            </svg>
            Google
          </a>

          <p class="lg-switch">
            @if ($mode === 'login')
              Belum punya akun? <a href="{{ route('register') }}">Daftar sekarang &rsaquo;</a>
            @else
              Sudah punya akun? <a href="{{ route('login') }}">Masuk di sini &rsaquo;</a>
            @endif
          </p>
        </div>
      </div>
    </div>
  </div>
@endsection