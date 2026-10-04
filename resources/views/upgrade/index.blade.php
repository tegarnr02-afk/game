@extends('layouts.game')

@section('title', 'Upgrade ke Pro')

@section('content')
  <div class="pay-wrap" style="background-image: url('{{ asset('images/home.webp') }}');">
    <div class="pay-card">
      <a href="{{ route('home') }}" class="pay-back">&lsaquo; Kembali</a>

      <div class="pay-crown" aria-hidden="true">&#128081;</div>
      <p class="pay-kicker">PRO MEMBER</p>
      <h1 class="pay-title">Upgrade ke Dermatch Pro</h1>

      <ul class="pay-perks">
        <li>Buka Stage 3 &amp; Stage 4</li>
        <li>Tantangan eksklusif</li>
        <li>Perjalanan skincare yang lebih lengkap</li>
      </ul>

      <div class="pay-price">
        <span>Rp {{ number_format($plan['amount'], 0, ',', '.') }}</span>
        <small>sekali bayar</small>
      </div>

           <form method="POST" action="{{ route('upgrade.checkout') }}">
        @csrf
        <h2 class="pay-h2">Metode pembayaran</h2>
        <div class="pay-single-method">
          <strong>Transfer Bank BCA</strong>
          <span>Transfer manual, lalu unggah bukti pembayaran.</span>
        </div>

        <div class="pay-total">
          <span>Total</span>
          <strong>Rp {{ number_format($plan['amount'], 0, ',', '.') }}</strong>
        </div>

        <button type="submit" class="upgrade-primary pay-submit">LANJUT KE PEMBAYARAN</button>
      </form>
    </div>
  </div>
@endsection