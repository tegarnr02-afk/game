@extends('layouts.admin')

@section('title', 'Dashboard')

@section('admin')
  <header class="adm-head">
    <div>
      <h1>Dashboard</h1>
      <p>Ringkasan Dermatch Pro</p>
    </div>
  </header>

  <section class="adm-stats">
    <a href="{{ route('admin.orders.index', ['status' => 'awaiting_review']) }}" class="adm-stat adm-stat-warn">
      <span>Menunggu review</span>
      <strong>{{ $counts['awaiting_review'] ?? 0 }}</strong>
    </a>
    <div class="adm-stat">
      <span>Pesanan disetujui</span>
      <strong>{{ $counts['paid'] ?? 0 }}</strong>
    </div>
    <div class="adm-stat">
      <span>User Pro</span>
      <strong>{{ $proCount }}</strong>
    </div>
    <div class="adm-stat">
      <span>Total pengguna</span>
      <strong>{{ $userCount }}</strong>
    </div>
    <div class="adm-stat">
      <span>Total masuk</span>
      <strong>Rp {{ number_format($revenue, 0, ',', '.') }}</strong>
    </div>
  </section>

  <div class="adm-grid">
    <section class="adm-box">
      <h2>Pemasukan 7 hari terakhir</h2>
      <div class="adm-chart">
        @foreach ($chart as $bar)
          @php($h = $bar['total'] ? max(4, round($bar['total'] / $chartMax * 100)) : 0)
          <div class="adm-bar" title="Rp {{ number_format($bar['total'], 0, ',', '.') }}">
            <small>{{ $bar['total'] ? number_format($bar['total'] / 1000, 0, ',', '.') . 'rb' : '-' }}</small>
            <div class="adm-bar-track"><div class="adm-bar-fill" style="height: {{ $h }}%"></div></div>
            <small>{{ $bar['label'] }}</small>
          </div>
        @endforeach
      </div>
    </section>

    <section class="adm-box">
      <h2>Antrean verifikasi</h2>
      @forelse ($queue as $order)
        <a class="adm-queue-item" href="{{ route('admin.orders.show', $order) }}">
          <div>
            <strong>{{ $order->user->name }}</strong>
            <small>{{ $order->order_code }} · {{ $order->proof_uploaded_at?->format('d M, H:i') }}</small>
          </div>
          <span>Rp {{ number_format($order->amount, 0, ',', '.') }}</span>
        </a>
      @empty
        <p class="adm-empty">Tidak ada pesanan yang menunggu.</p>
      @endforelse

      @if (($counts['awaiting_review'] ?? 0) > count($queue))
        <a href="{{ route('admin.orders.index', ['status' => 'awaiting_review']) }}" class="adm-link">Lihat semua &rsaquo;</a>
      @endif
    </section>
  </div>

  <section class="adm-box">
    <h2>Pengguna terbaru</h2>
    <div class="adm-table-wrap adm-flat">
      <table class="adm-table" style="min-width: 520px">
        <thead>
          <tr><th>Nama</th><th>Email</th><th>Status</th><th>Terdaftar</th></tr>
        </thead>
        <tbody>
          @foreach ($newUsers as $u)
            <tr>
              <td><strong>{{ $u->name }}</strong></td>
              <td>{{ $u->email }}</td>
              <td>
                <span class="adm-badge {{ $u->is_pro ? 'adm-paid' : 'adm-pending' }}">{{ $u->is_pro ? 'Pro' : 'Free' }}</span>
              </td>
              <td>{{ $u->created_at->format('d M Y, H:i') }}</td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </section>
@endsection