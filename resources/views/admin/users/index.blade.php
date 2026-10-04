@extends('layouts.admin')

@section('title', 'Pengguna')

@section('admin')
  <div x-data="{ ask: null }">
    <header class="adm-head">
      <div>
        <h1>Pengguna</h1>
        <p>Kelola akses admin, Pro, dan akun pengguna</p>
      </div>
    </header>

    @if (session('status'))
      <div class="adm-flash">{{ session('status') }}</div>
    @endif
    @if (session('error'))
      <div class="adm-flash adm-flash-error">{{ session('error') }}</div>
    @endif

    <nav class="adm-tabs" aria-label="Filter pengguna">
      @foreach ($tabs as $key => $label)
        <a href="{{ route('admin.users.index', ['filter' => $key, 'q' => $search ?: null]) }}"
          class="adm-tab {{ $filter === $key ? 'is-active' : '' }}">
          {{ $label }} <small>{{ $counts[$key] }}</small>
        </a>
      @endforeach
    </nav>

    <form method="GET" action="{{ route('admin.users.index') }}" class="adm-search">
      <input type="hidden" name="filter" value="{{ $filter }}">
      <input type="search" name="q" value="{{ $search }}" placeholder="Cari nama atau email..." aria-label="Cari pengguna">
      <button type="submit" class="adm-btn adm-btn-ico"><x-admin.icon name="search" /> Cari</button>
      @if ($search !== '')
        <a href="{{ route('admin.users.index', ['filter' => $filter]) }}" class="adm-btn adm-btn-ghost">Reset</a>
      @endif
    </form>

    <div class="adm-table-wrap">
      <table class="adm-table" style="min-width: 860px">
        <thead>
          <tr>
            <th>Pengguna</th>
            <th>Akses</th>
            <th>Pesanan</th>
            <th>Terdaftar</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse ($users as $user)
            @php
              $isSelf = $user->is(auth()->user());

              $proAsk = [
                  'action' => route('admin.users.pro', $user),
                  'method' => 'PATCH',
                  'value'  => $user->is_pro ? 0 : 1,
                  'tone'   => $user->is_pro ? 'warn' : 'ok',
                  'icon'   => 'crown',
                  'title'  => $user->is_pro ? 'Cabut akses Pro?' : 'Jadikan Pro?',
                  'text'   => $user->is_pro
                      ? 'Akses Pro ' . $user->name . ' akan dicabut dan zona premium kembali terkunci.'
                      : $user->name . ' akan mendapat akses Pro dan bisa membuka semua zona.',
                  'button' => $user->is_pro ? 'Ya, cabut Pro' : 'Ya, jadikan Pro',
              ];

              $adminAsk = [
                  'action' => route('admin.users.admin', $user),
                  'method' => 'PATCH',
                  'value'  => $user->is_admin ? 0 : 1,
                  'tone'   => $user->is_admin ? 'warn' : 'info',
                  'icon'   => 'shield',
                  'title'  => $user->is_admin ? 'Cabut akses Admin?' : 'Jadikan Admin?',
                  'text'   => $user->is_admin
                      ? $user->name . ' tidak akan bisa lagi mengelola pesanan dan pengguna.'
                      : $user->name . ' akan bisa mengelola pesanan dan pengguna.',
                  'button' => $user->is_admin ? 'Ya, cabut Admin' : 'Ya, jadikan Admin',
              ];

              $deleteAsk = [
                  'action' => route('admin.users.destroy', $user),
                  'method' => 'DELETE',
                  'value'  => null,
                  'tone'   => 'danger',
                  'icon'   => 'trash',
                  'title'  => 'Hapus akun?',
                  'text'   => 'Akun ' . $user->name . ' - ' . $user->email . ' akan dihapus permanen beserta '
                      . $user->orders_count . ' pesanan dan bukti transfernya. Tindakan ini tidak bisa dibatalkan.',
                  'button' => 'Ya, hapus akun',
              ];
            @endphp
            <tr>
              <td>
                <strong>{{ $user->name }}</strong>
                @if ($isSelf)<span class="adm-self">Kamu</span>@endif
                <small>{{ $user->email }}</small>
              </td>
              <td data-label="Akses">
                <div class="adm-badges">
                  @if ($user->is_admin)<span class="adm-badge adm-admin">Admin</span>@endif
                  <span class="adm-badge {{ $user->is_pro ? 'adm-paid' : 'adm-pending' }}">{{ $user->is_pro ? 'Pro' : 'Free' }}</span>
                  @if ($user->google_id)<span class="adm-badge adm-google">Google</span>@endif
                </div>
              </td>
              <td data-label="Pesanan">{{ $user->orders_count }}</td>
              <td data-label="Terdaftar">{{ $user->created_at->format('d M Y, H:i') }}</td>
              <td>
                <div class="adm-row-actions">
                  <button type="button" class="adm-btn adm-btn-sm adm-btn-ico {{ $user->is_pro ? 'adm-btn-ghost' : 'adm-btn-ok' }}"
                    @click="ask = @js($proAsk)">
                    <x-admin.icon name="crown" />
                    {{ $user->is_pro ? 'Cabut Pro' : 'Jadikan Pro' }}
                  </button>

                  @unless ($isSelf)
                    <button type="button" class="adm-btn adm-btn-sm adm-btn-ico {{ $user->is_admin ? 'adm-btn-ghost' : '' }}"
                      @click="ask = @js($adminAsk)">
                      <x-admin.icon name="shield" />
                      {{ $user->is_admin ? 'Cabut Admin' : 'Jadikan Admin' }}
                    </button>

                    @if ($user->is_admin)
                      <button type="button" class="adm-btn adm-btn-sm adm-btn-no adm-btn-ico" disabled
                        title="Turunkan akses admin dulu sebelum menghapus akun ini">
                        <x-admin.icon name="trash" /> Hapus
                      </button>
                    @else
                      <button type="button" class="adm-btn adm-btn-sm adm-btn-no adm-btn-ico"
                        @click="ask = @js($deleteAsk)">
                        <x-admin.icon name="trash" /> Hapus
                      </button>
                    @endif
                  @endunless
                </div>
              </td>
            </tr>
          @empty
            <tr><td colspan="5" class="adm-empty">Tidak ada pengguna yang cocok.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>

    @if ($users->hasPages())
      <div class="adm-pager">
        @if ($users->onFirstPage())
          <span>&lsaquo; Sebelumnya</span>
        @else
          <a href="{{ $users->previousPageUrl() }}">&lsaquo; Sebelumnya</a>
        @endif
        <span>Hal. {{ $users->currentPage() }}</span>
        @if ($users->hasMorePages())
          <a href="{{ $users->nextPageUrl() }}">Berikutnya &rsaquo;</a>
        @else
          <span>Berikutnya &rsaquo;</span>
        @endif
      </div>
    @endif

    {{-- Modal konfirmasi untuk semua aksi di halaman ini --}}
    <x-admin.confirm />
  </div>
@endsection