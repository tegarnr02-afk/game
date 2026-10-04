@extends('layouts.game')

@section('title', 'Pembayaran')

@section('content')
<div class="pay-wrap" style="background-image: url('{{ asset('images/home.webp') }}');">
  <div class="pay-card">
    <a href="{{ route('home') }}" class="pay-back">&#8249; Kembali ke Home</a>


    @if ($order->status === 'paid')
      <div class="pay-crown" aria-hidden="true">&#127881;</div>
      <p class="pay-kicker">BERHASIL</p>
      <h1 class="pay-title">Kamu sekarang Pro!</h1>
      <p class="pay-note">Stage premium sudah terbuka. Selamat bermain dan bereksperimen dengan skincare-mu.</p>
      <a href="{{ route('home') }}" class="upgrade-primary pay-submit">MULAI MAIN</a>
    @else
    @php($review = $order->status === 'awaiting_review')

    <p class="pay-kicker">{{ $review ? 'MENUNGGU VERIFIKASI' : 'MENUNGGU PEMBAYARAN' }}</p>
    <h1 class="pay-title">{{ $review ? 'Bukti pembayaran terkirim' : 'Transfer ke rekening berikut' }}</h1>

    @if ($order->status === 'rejected')
      <p class="pay-alert">
        Bukti pembayaran ditolak{{ $order->admin_note ? ': ' . $order->admin_note : '.' }}
        Silakan unggah ulang.
      </p>
    @endif

    <div class="pay-bank" x-data="{ copied: false }">
      <p class="pay-bank-name">{{ $bank['bank'] }}</p>
      <p class="pay-bank-no">{{ $bank['number'] }}</p>
      <p class="pay-bank-holder">a.n. {{ $bank['holder'] }}</p>
      <button type="button" class="pay-copy"
        @click="navigator.clipboard.writeText('{{ $bank['number'] }}'); copied = true; setTimeout(() => copied = false, 1800)"
        x-text="copied ? 'Tersalin ✓' : 'Salin nomor rekening'"></button>
    </div>

    <dl class="pay-summary">
      <div>
        <dt>Kode pesanan</dt>
        <dd>{{ $order->order_code }}</dd>
      </div>
      <div>
        <dt>Total transfer</dt>
        <dd>Rp {{ number_format($order->amount, 0, ',', '.') }}</dd>
      </div>
    </dl>

    @if ($review)
      <p class="pay-note">Bukti kamu sedang diperiksa. Setelah disetujui, akunmu otomatis menjadi Pro.</p>
      <img class="pay-proof-img" src="{{ route('upgrade.proof', $order) }}" alt="Bukti transfer yang sudah dikirim">

      <h2 class="pay-h2">Salah foto? Kirim ulang</h2>
      <form method="POST" action="{{ route('upgrade.proof.upload', $order) }}" enctype="multipart/form-data"
        x-data="{ preview: null }">
        @csrf
        <label class="pay-upload">
          <input type="file" name="proof" accept="image/png,image/jpeg,image/webp" required
            @change="preview = $event.target.files[0] ? URL.createObjectURL($event.target.files[0]) : null">
          <template x-if="preview">
            <img :src="preview" alt="Pratinjau bukti transfer">
          </template>
          <span x-show="!preview">
            &#128206; Ketuk untuk memilih foto bukti transfer
            <small>JPG, PNG, atau WEBP · maksimal 4 MB</small>
          </span>
        </label>
        @error('proof')
          <p class="pay-error">{{ $message }}</p>
        @enderror
        <button type="submit" class="upgrade-primary pay-submit">KIRIM ULANG BUKTI</button>
      </form>

      <a href="{{ route('home') }}" class="upgrade-primary pay-submit"
        style="margin-top:.5rem;display:block;text-align:center;text-decoration:none;">&#127968; KEMBALI KE HOME</a>
    @else
      <form method="POST" action="{{ route('upgrade.proof.upload', $order) }}" enctype="multipart/form-data"
        x-data="{ preview: null }">
        @csrf
        <h2 class="pay-h2">Unggah bukti transfer</h2>
        <label class="pay-upload">
          <input type="file" name="proof" accept="image/png,image/jpeg,image/webp" required
            @change="preview = $event.target.files[0] ? URL.createObjectURL($event.target.files[0]) : null">
          <template x-if="preview">
            <img :src="preview" alt="Pratinjau bukti transfer">
          </template>
          <span x-show="!preview">
            &#128206; Ketuk untuk memilih foto bukti transfer
            <small>JPG, PNG, atau WEBP · maksimal 4 MB</small>
          </span>
        </label>
        @error('proof')
          <p class="pay-error">{{ $message }}</p>
        @enderror
        <button type="submit" class="upgrade-primary pay-submit">KIRIM BUKTI PEMBAYARAN</button>
      </form>
      <a href="{{ route('home') }}" class="pay-back pay-back-center">Bayar nanti</a>
    @endif
    @endif
  </div>
</div>
@endsection