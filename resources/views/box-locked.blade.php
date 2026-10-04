@extends('layouts.app')

@section('title', $box->name . ' – Terkunci')

@section('content')
  <div class="locked-screen">
    <div class="locked-card">
      <canvas id="lockedFace" class="face-canvas locked-face" width="140" height="140"></canvas>
      <div class="locked-badge-big">🔒 Segera Hadir</div>
      <h2 class="locked-title">{{ $box->icon }} {{ $box->name }}</h2>
      <p class="locked-condition">{{ $box->condition_label }}</p>
      <p class="locked-msg">Box ini akan tersedia setelah dirilis. Pantau update berikutnya!</p>
      <a href="{{ route('home') }}" class="btn btn-primary mt-4">🏠 Kembali ke Beranda</a>
    </div>
  </div>
@endsection

@section('scripts')
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const c = document.getElementById('lockedFace');
      if (c) drawFace(c, '{{ $box->face_type }}', 'sad');
    });
  </script>
@endsection