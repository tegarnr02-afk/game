@extends('layouts.game')

@section('content')
  @php($awaiting = \App\Models\Order::where('status', 'awaiting_review')->count())

  <div class="adm-app" x-data="{ nav: false }" @keydown.escape.window="nav = false">
    <aside class="adm-side" :class="{ 'is-open': nav }" aria-label="Menu admin">
      <div class="adm-brand">
        <img src="{{ asset('images/logo-desktop.webp') }}" alt="Dermatch">
        <span>ADMIN PANEL</span>
      </div>

      <nav class="adm-nav">
        <a href="{{ route('admin.dashboard') }}"
          class="{{ request()->routeIs('admin.dashboard') ? 'is-active' : '' }}">
          <i aria-hidden="true"><x-admin.icon name="dashboard" /></i> Dashboard
        </a>
                <a href="{{ route('admin.users.index') }}"
          class="{{ request()->routeIs('admin.users.*') ? 'is-active' : '' }}">
          <i aria-hidden="true"><x-admin.icon name="users" /></i> Pengguna
        </a>
        <a href="{{ route('admin.orders.index') }}"
          class="{{ request()->routeIs('admin.orders.*') ? 'is-active' : '' }}">
          <i aria-hidden="true"><x-admin.icon name="orders" /></i> Pesanan
          @if ($awaiting > 0)<b class="adm-count">{{ $awaiting }}</b>@endif
        </a>
      </nav>

      <div class="adm-side-foot">
        <div class="adm-user">
          <strong>{{ auth()->user()->name }}</strong>
          <small>{{ auth()->user()->email }}</small>
        </div>
        <a href="{{ route('home') }}" class="adm-side-link"><x-admin.icon name="back" /> Kembali ke game</a>
        <form method="POST" action="{{ route('logout') }}">
          @csrf
          <button type="submit" class="adm-side-link"><x-admin.icon name="logout" /> Keluar</button>
        </form>
      </div>
    </aside>

    <div class="adm-backdrop" x-cloak x-show="nav" x-transition.opacity @click="nav = false"></div>

    <div class="adm-main">
      <header class="adm-topbar">
        <button type="button" class="adm-burger" aria-label="Buka menu" @click="nav = true"><x-admin.icon name="menu" /></button>
        <strong>@yield('title')</strong>
      </header>

      <main class="adm-content">
        @yield('admin')
      </main>
    </div>
  </div>
@endsection