@extends('layouts.admin')

@section('title', 'Periksa Pesanan')

@section('admin')
  <div class="adm-narrow">
    <a href="{{ route('admin.orders.index', ['status' => $order->status]) }}" class="adm-link">&lsaquo; Kembali ke
      daftar</a>
    <header class="adm-head">
      <div>
        <h1>{{ $order->order_code }}</h1>
        <p><span class="adm-badge adm-{{ $order->status }}">{{ $order->statusLabel() }}</span></p>
      </div>
    </header>

    @if (session('status'))
      <div class="adm-flash">{{ session('status') }}</div>
    @endif
    @if (session('error'))
      <div class="adm-flash adm-flash-error">{{ session('error') }}</div>
    @endif

    <div class="adm-detail">
      <section class="adm-box">
        <h2>Data pesanan</h2>
        <dl class="adm-dl">
          <div>
            <dt>Nama</dt>
            <dd>{{ $order->user->name }}</dd>
          </div>
          <div>
            <dt>Email</dt>
            <dd>{{ $order->user->email }}</dd>
          </div>
          <div>
            <dt>Status akun</dt>
            <dd>{{ $order->user->is_pro ? 'Pro' : 'Free' }}</dd>
          </div>
          <div>
            <dt>Nominal</dt>
            <dd><strong>Rp {{ number_format($order->amount, 0, ',', '.') }}</strong></dd>
          </div>
          <div>
            <dt>Paket</dt>
            <dd>{{ $order->planLabel() }}</dd>
          </div>
          <div>
            <dt>Metode</dt>
            <dd>{{ $order->methodLabel() }}</dd>
          </div>
          <div>
            <dt>Dibuat</dt>
            <dd>{{ $order->created_at->format('d M Y, H:i') }}</dd>
          </div>
          @if ($order->paid_at)
            <div>
              <dt>Disetujui pada</dt>
              <dd>{{ $order->paid_at->format('d M Y, H:i') }}</dd>
            </div>
          @endif
          <div>
            <dt>Rekening tujuan</dt>
            <dd>{{ $bank['bank'] }} {{ $bank['number'] }}</dd>
          </div>
          <div>
            <dt>Bukti dikirim</dt>
            <dd>{{ $order->proof_uploaded_at?->format('d M Y, H:i') ?? '-' }}</dd>
          </div>
          @if ($order->reviewed_at)
            <div>
              <dt>Diproses oleh</dt>
              <dd>{{ $order->reviewer?->name ?? '-' }}, {{ $order->reviewed_at->format('d M Y, H:i') }}</dd>
            </div>
          @endif
          @if ($order->admin_note)
            <div>
              <dt>Catatan</dt>
              <dd>{{ $order->admin_note }}</dd>
            </div>
          @endif
        </dl>
      </section>

      <section class="adm-box">
        <h2>Bukti transfer</h2>
        @if ($order->proof_path)
          <a href="{{ route('admin.orders.proof', $order) }}" target="_blank" rel="noopener">
            <img class="adm-proof" src="{{ route('admin.orders.proof', $order) }}"
              alt="Bukti transfer {{ $order->order_code }}">
          </a>
          <p class="adm-hint">Klik gambar untuk membuka ukuran penuh.</p>
        @else
          <p class="adm-empty">User belum mengunggah bukti.</p>
        @endif
      </section>
    </div>

    @if ($order->status === 'awaiting_review' && $order->proof_path)
      @php
        $approveAsk = [
          'action' => route('admin.orders.approve', $order),
          'method' => 'PATCH',
          'value' => null,
          'form' => 'approve-form',
          'tone' => 'ok',
          'icon' => 'check',
          'title' => 'Setujui pesanan?',
          'text' => 'Pastikan nominal Rp ' . number_format($order->amount, 0, ',', '.')
            . ' sudah cocok dengan mutasi rekening. Akun ' . $order->user->name . ' akan menjadi Pro.',
          'button' => 'Ya, setujui',
        ];
      @endphp

      <p class="adm-check">
        Sebelum menyetujui, cocokkan nominal <strong>Rp {{ number_format($order->amount, 0, ',', '.') }}</strong>
        dan waktu transfer dengan mutasi rekening {{ $bank['bank'] }}.
      </p>

      <div class="adm-actions" x-data="{ rejectOpen: {{ $errors->has('note') ? 'true' : 'false' }}, ask: null }">
        <form id="approve-form" method="POST" action="{{ route('admin.orders.approve', $order) }}" class="adm-reject">
          @csrf
          @method('PATCH')
          <label for="approve_note">Catatan (opsional)</label>
          <textarea id="approve_note" name="approve_note" rows="2" maxlength="300"
            placeholder="Contoh: Transfer cocok dengan mutasi BCA."></textarea>

          {{-- Tombol berdampingan --}}
          <div style="display:flex; gap:10px; margin-top:4px;">
            <button type="button" class="adm-btn adm-btn-ok adm-btn-ico" style="flex:1;" @click="ask = @js($approveAsk)">
              <x-admin.icon name="check" /> Setujui & aktifkan Pro
            </button>
            <button type="button" class="adm-btn adm-btn-no adm-btn-ico" style="flex:0 0 auto;" @click="rejectOpen = true">
              <x-admin.icon name="x" /> Tolak
            </button>
          </div>
        </form>

        <div class="adm-modal" x-cloak x-show="rejectOpen" x-transition.opacity @keydown.escape.window="rejectOpen = false"
          @click.self="rejectOpen = false">
          <form method="POST" action="{{ route('admin.orders.reject', $order) }}" class="adm-reject adm-modal-card">
            @csrf
            @method('PATCH')
            <h2>Tolak pesanan</h2>
            <label for="note">Alasan penolakan (dibaca pengguna)</label>
            <textarea id="note" name="note" rows="4" minlength="5" maxlength="300" required
              placeholder="Contoh: Nominal transfer kurang dari Rp 15.000.">{{ old('note') }}</textarea>
            @error('note')
            <p class="adm-error">{{ $message }}</p>@enderror
            <div class="adm-modal-actions">
              <button type="button" class="adm-btn adm-btn-ghost" @click="rejectOpen = false">Batal</button>
              <button type="submit" class="adm-btn adm-btn-no adm-btn-ico"><x-admin.icon name="x" /> Tolak pesanan</button>
            </div>
          </form>
        </div>

        {{-- Modal konfirmasi Setujui --}}
        <x-admin.confirm />
      </div>

    @elseif ($order->status === 'pending')
      <p class="adm-check">Menunggu pengguna mengunggah bukti transfer.</p>
    @endif
  </div>
@endsection