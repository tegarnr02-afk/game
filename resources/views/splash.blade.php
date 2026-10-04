@extends('layouts.game')

@section('title', 'Memuat...')

@section('content')
  <div class="splash" x-data="splash()" x-init="start()" :class="{ 'is-leaving': leaving }"
    style="background-image: url('{{ asset('images/bg.webp') }}');">

    <span class="splash-bubble" style="--x:12%; --s:22px; --d:7s; --w:0s">&#10022;</span>
    <span class="splash-bubble" style="--x:28%; --s:14px; --d:9s; --w:1.2s">&#10023;</span>
    <span class="splash-bubble" style="--x:46%; --s:18px; --d:8s; --w:2.4s">&#10022;</span>
    <span class="splash-bubble" style="--x:64%; --s:26px; --d:10s; --w:0.6s">&#10023;</span>
    <span class="splash-bubble" style="--x:80%; --s:16px; --d:7.5s; --w:1.8s">&#10022;</span>
    <span class="splash-bubble" style="--x:92%; --s:20px; --d:9.5s; --w:3s">&#10023;</span>

    <div class="splash-center">
      <img class="splash-logo" src="{{ asset('images/logo-desktop.webp') }}" alt="Skincare Mix Lab">

      <div class="splash-flask" aria-hidden="true">&#129514;</div>

      <div class="splash-bar" role="progressbar" aria-valuemin="0" aria-valuemax="100" :aria-valuenow="Math.round(progress)">
        <div class="splash-bar-fill" :style="'width:' + progress + '%'"></div>
      </div>

      <p class="splash-tip" x-text="tip"></p>
      <p class="splash-percent"><span x-text="Math.round(progress)"></span>%</p>
    </div>
  </div>
@endsection

@section('scripts')
  <script>
    function splash() {
      return {
        progress: 0,
        leaving: false,
        tip: 'Menyiapkan laboratorium...',
        tips: [
          'Menyiapkan laboratorium...',
          'Mengumpulkan bahan aktif...',
          'Mencampur formula...',
          'Hampir siap, glow up dimulai!'
        ],
        start() {
          const total = 3000;
          const begin = performance.now();
          const tick = (now) => {
            const t = Math.min(1, (now - begin) / total);
            this.progress = Math.min(100, t * 100);
            this.tip = this.tips[Math.min(this.tips.length - 1, Math.floor(t * this.tips.length))];
            if (t < 1) {
              requestAnimationFrame(tick);
            } else {
              this.leaving = true;
              setTimeout(() => { window.location.href = @json(route('login')); }, 500);
            }
          };
          requestAnimationFrame(tick);
        },
      };
    }
  </script>
@endsection