@extends('layouts.admin')

@section('title', 'Pesanan')

@section('admin')
  <header class="adm-head">
    <div>
      <h1>Pesanan</h1>
      <p>Verifikasi pembayaran Dermatch Pro</p>
    </div>
  </header>

      @if (session('status'))
        <div class="adm-flash">{{ session('status') }}</div>
      @endif

            @if (session('error'))
        <div class="adm-flash adm-flash-error">{{ session('error') }}</div>
      @endif

      <section class="adm-stats">
        <div class="adm-stat adm-stat-warn">
          <span>Menunggu verifikasi</span>
          <strong>{{ $counts['awaiting_review'] ?? 0 }}</strong>
        </div>
        <div class="adm-stat">
          <span>Pesanan disetujui</span>
          <strong>{{ $counts['paid'] ?? 0 }}</strong>
        </div>
        <div class="adm-stat">
          <span>User Pro</span>
          <strong>{{ $proCount }}</strong>
        </div>
        <div class="adm-stat">
          <span>Total masuk</span>
          <strong>Rp {{ number_format($revenue, 0, ',', '.') }}</strong>
        </div>
      </section>

           <nav class="adm-tabs" aria-label="Filter status">
        @foreach ($tabs as $key => $label)
          <a href="{{ route('admin.orders.index', ['status' => $key, 'q' => $search ?: null]) }}"
            class="adm-tab {{ $status === $key ? 'is-active' : '' }}">
            {{ $label }}
            @if ($key !== 'all')<small>{{ $counts[$key] ?? 0 }}</small>@endif
          </a>
        @endforeach
      </nav>

      <form method="GET" action="{{ route('admin.orders.index') }}" class="adm-search">
        <input type="hidden" name="status" value="{{ $status }}">
        <input type="search" name="q" value="{{ $search }}" placeholder="Cari kode pesanan atau email..." aria-label="Cari pesanan">
        <button type="submit" class="adm-btn">Cari</button>
        @if ($search !== '')
          <a href="{{ route('admin.orders.index', ['status' => $status]) }}" class="adm-btn adm-btn-ghost">Reset</a>
        @endif
      </form>

           <div class="adm-table-wrap">
        <table class="adm-table" style="min-width: 980px">
          <thead>
            <tr>
              <th>Kode</th>
              <th>Pengguna</th>
              <th>Paket</th>
              <th>Nominal</th>
              <th>Metode</th>
              <th>Status</th>
              <th>Bukti</th>
              <th>Dibuat</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            @forelse ($orders as $order)
              <tr>
                <td><code>{{ $order->order_code }}</code></td>
                <td class="adm-td-wide" data-label="Pengguna">
                  <strong>{{ $order->user->name }}</strong>
                  <small>{{ $order->user->email }}</small>
                </td>
                <td data-label="Paket">{{ $order->planLabel() }}</td>
                <td data-label="Nominal">Rp {{ number_format($order->amount, 0, ',', '.') }}</td>
                <td data-label="Metode">{{ $order->methodLabel() }}</td>
                <td data-label="Status"><span class="adm-badge adm-{{ $order->status }}">{{ $order->statusLabel() }}</span></td>
                <td data-label="Bukti">{{ $order->proof_path ? 'Sudah upload' : 'Belum upload' }}</td>
                <td data-label="Dibuat">{{ $order->created_at->format('d M Y, H:i') }}</td>
                <td><a class="adm-btn" href="{{ route('admin.orders.show', $order) }}">Periksa</a></td>
              </tr>
            @empty
              <tr><td colspan="9" class="adm-empty">Tidak ada pesanan yang cocok.</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>

      @if ($orders->hasPages())
        <div class="adm-pager">
          @if ($orders->onFirstPage())
            <span>&lsaquo; Sebelumnya</span>
          @else
            <a href="{{ $orders->previousPageUrl() }}">&lsaquo; Sebelumnya</a>
          @endif
          <span>Hal. {{ $orders->currentPage() }}</span>
          @if ($orders->hasMorePages())
            <a href="{{ $orders->nextPageUrl() }}">Berikutnya &rsaquo;</a>
          @else
            <span>Berikutnya &rsaquo;</span>
          @endif
        </div>
      @endif
@endsection