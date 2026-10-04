<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'Skin Salon') – Skincare Pairing Game</title>


  <meta name="description"
    content="Game edukasi skincare: cocokkan dua bahan aktif dan lihat apakah pasangannya aman untuk kulitmu.">

    <!-- Favicon -->
  <link rel="icon" type="image/png" href="{{ asset('favicon-96x96.png') }}" sizes="96x96">
  <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
  <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
  <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
  <meta name="apple-mobile-web-app-title" content="Skin Salon">
  <link rel="manifest" href="{{ asset('site.webmanifest') }}">
  <meta name="theme-color" content="#ffb3d9">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link
    href="https://fonts.googleapis.com/css2?family=Quicksand:wght@400;600;700&family=Nunito:wght@400;700;800;900&display=swap"
    rel="stylesheet">
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>

  <!-- Background decorative stars -->
  <div class="star-bg" id="starBg"></div>

  <!-- HEADER -->
  <header class="site-header">
    <h1 class="site-title">
      <!-- Lucide: sparkles -->
      <svg class="icon" viewBox="0 0 24 24" aria-hidden="true">
        <path d="M9.937 15.5A2 2 0 0 0 8.5 14.063l-6.135-1.582a.5.5 0 0 1 0-.962L8.5 9.936A2 2 0 0 0 9.937 8.5l1.582-6.135a.5.5 0 0 1 .963 0L14.063 8.5A2 2 0 0 0 15.5 9.937l6.135 1.581a.5.5 0 0 1 0 .964L15.5 14.063a2 2 0 0 0-1.437 1.437l-1.582 6.135a.5.5 0 0 1-.963 0z"/>
        <path d="M20 3v4"/><path d="M22 5h-4"/><path d="M4 17v2"/><path d="M5 18H3"/>
      </svg>
      Skin Salon
    </h1>

    <button class="music-btn" id="musicBtn" type="button" onclick="toggleMusic()" aria-pressed="false">
      <!-- Lucide: volume-x (musik OFF) -->
      <svg class="icon" id="iconMusicOff" viewBox="0 0 24 24" aria-hidden="true">
        <path d="M11 4.702a.705.705 0 0 0-1.203-.498L6.413 7.587A1.4 1.4 0 0 1 5.416 8H3a1 1 0 0 0-1 1v6a1 1 0 0 0 1 1h2.416a1.4 1.4 0 0 1 .997.413l3.383 3.384A.705.705 0 0 0 11 19.298z"/>
        <line x1="22" x2="16" y1="9" y2="15"/><line x1="16" x2="22" y1="9" y2="15"/>
      </svg>
      <!-- Lucide: volume-2 (musik ON) -->
      <svg class="icon" id="iconMusicOn" viewBox="0 0 24 24" aria-hidden="true" hidden>
        <path d="M11 4.702a.705.705 0 0 0-1.203-.498L6.413 7.587A1.4 1.4 0 0 1 5.416 8H3a1 1 0 0 0-1 1v6a1 1 0 0 0 1 1h2.416a1.4 1.4 0 0 1 .997.413l3.383 3.384A.705.705 0 0 0 11 19.298z"/>
        <path d="M16 9a5 5 0 0 1 0 6"/><path d="M19.364 18.364a9 9 0 0 0 0-12.728"/>
      </svg>
      <span id="musicLabel">Musik OFF</span>
    </button>
  </header>

  <main class="main-wrap">
    @yield('content')
  </main>

  <script>
    // ── Music Toggle ──────────────────────────────────────────
    let musicOn = false, audioCtx = null, bgNodes = [];

    function setMusicUI(on) {
      document.getElementById('musicLabel').textContent = on ? 'Musik ON' : 'Musik OFF';
      document.getElementById('iconMusicOn').hidden = !on;
      document.getElementById('iconMusicOff').hidden = on;
      document.getElementById('musicBtn').setAttribute('aria-pressed', on);
    }

    function toggleMusic() {
      if (!audioCtx) audioCtx = new (window.AudioContext || window.webkitAudioContext)();
      if (musicOn) {
        bgNodes.forEach(n => { try { n.stop() } catch (e) { } });
        bgNodes = [];
        musicOn = false;
      } else {
        playBg();
        musicOn = true;
      }
      setMusicUI(musicOn);
    }

    function playBg() {
      const notes = [523, 659, 784, 659, 523, 659, 784, 880, 784, 659, 523, 587];
      let t = audioCtx.currentTime;
      function loop() {
        notes.forEach(freq => {
          const osc = audioCtx.createOscillator();
          const gain = audioCtx.createGain();
          osc.type = 'sine'; osc.frequency.value = freq;
          gain.gain.setValueAtTime(0, t);
          gain.gain.linearRampToValueAtTime(0.055, t + 0.05);
          gain.gain.linearRampToValueAtTime(0, t + 0.38);
          osc.connect(gain); gain.connect(audioCtx.destination);
          osc.start(t); osc.stop(t + 0.42);
          bgNodes.push(osc); t += 0.43;
        });
        const timer = setTimeout(() => { if (musicOn) loop(); }, (notes.length * 0.43 - 0.5) * 1000);
        bgNodes.push({ stop: () => clearTimeout(timer) });
      }
      loop();
    }

    // ── Face Drawing ──────────────────────────────────────────
    function drawFace(canvas, type, mood = 'neutral') {
      if (!canvas) return;
      const c = canvas.getContext('2d');
      const w = canvas.width, h = canvas.height;
      c.clearRect(0, 0, w, h);
      const cx = w / 2, cy = h / 2, r = Math.min(w, h) / 2 - 3;

      const skins = {
        dry: { base: '#f5c29a', shadow: '#e0a076' },
        oily: { base: '#c8e6c9', shadow: '#a5d6a7' },
        sensitive: { base: '#fce4ec', shadow: '#f48fb1' },
        aging: { base: '#ffe0b2', shadow: '#ffcc80' },
      };
      const s = skins[type] || skins.dry;

      // Face base
      const grd = c.createRadialGradient(cx - r * .2, cy - r * .2, r * .1, cx, cy, r);
      grd.addColorStop(0, s.base); grd.addColorStop(1, s.shadow);
      c.beginPath(); c.arc(cx, cy, r, 0, Math.PI * 2);
      c.fillStyle = grd; c.fill();

      // Condition marks
      if (type === 'dry') {
        c.fillStyle = 'rgba(200,150,100,.28)';
        [[cx - .28 * r, cy - .1 * r, 5], [cx + .22 * r, cy + .05 * r, 4], [cx, cy + .18 * r, 3.5]].forEach(([x, y, rs]) => {
          c.beginPath(); c.arc(x, y, rs, 0, Math.PI * 2); c.fill();
        });
      } else if (type === 'oily') {
        const shine = c.createRadialGradient(cx, cy - .15 * r, 2, cx, cy - .15 * r, r * .55);
        shine.addColorStop(0, 'rgba(255,255,255,.42)'); shine.addColorStop(1, 'rgba(255,255,255,0)');
        c.beginPath(); c.arc(cx, cy, r, 0, Math.PI * 2); c.fillStyle = shine; c.fill();
        c.fillStyle = 'rgba(220,80,80,.65)';
        [[cx - .2 * r, cy - .1 * r], [cx + .25 * r, cy - .05 * r], [cx + .05 * r, cy + .2 * r], [cx - .15 * r, cy + .25 * r]].forEach(([x, y]) => {
          c.beginPath(); c.arc(x, y, 3.5, 0, Math.PI * 2); c.fill();
        });
      } else if (type === 'sensitive') {
        c.fillStyle = 'rgba(240,100,100,.16)';
        c.beginPath(); c.ellipse(cx - .3 * r, cy, r * .28, r * .2, 0, 0, Math.PI * 2); c.fill();
        c.beginPath(); c.ellipse(cx + .3 * r, cy, r * .28, r * .2, 0, 0, Math.PI * 2); c.fill();
      } else if (type === 'aging') {
        c.strokeStyle = 'rgba(150,100,60,.28)'; c.lineWidth = 1.5; c.lineCap = 'round';
        [[cx - .35 * r, cy - .2 * r, 14, 2], [cx + .2 * r, cy - .18 * r, 12, 1.5], [cx - .1 * r, cy + .3 * r, 18, 2]].forEach(([x, y, len, vy]) => {
          c.beginPath(); c.moveTo(x, y); c.quadraticCurveTo(x + len / 2, y + vy, x + len, y); c.stroke();
        });
        c.fillStyle = 'rgba(120,80,40,.18)';
        [[cx + .1 * r, cy + .08 * r, 6], [cx - .2 * r, cy + .15 * r, 4.5]].forEach(([x, y, rs]) => {
          c.beginPath(); c.arc(x, y, rs, 0, Math.PI * 2); c.fill();
        });
      }

      // Eyes
      const eyeY = cy - r * .18;
      c.fillStyle = '#4a2f2f';
      [-1, 1].forEach(side => {
        const ex = cx + side * r * .3;
        if (mood === 'happy') {
          c.strokeStyle = '#4a2f2f'; c.lineWidth = 2.2; c.lineCap = 'round';
          c.beginPath(); c.moveTo(ex - 6, eyeY); c.lineTo(ex + 6, eyeY); c.stroke();
        } else {
          c.beginPath(); c.ellipse(ex, eyeY, 6, 7, 0, 0, Math.PI * 2); c.fill();
          c.fillStyle = '#fff'; c.beginPath(); c.arc(ex + 2, eyeY - 2, 2.5, 0, Math.PI * 2); c.fill();
          c.fillStyle = '#4a2f2f';
        }
      });

      // Eyebrows
      c.strokeStyle = '#6d4c41'; c.lineWidth = 2.5; c.lineCap = 'round';
      [-1, 1].forEach(side => {
        const ex = cx + side * r * .3;
        const tilt = mood === 'sad' ? 3 * side : -3 * side;
        c.beginPath(); c.moveTo(ex - 8, eyeY - 10 + tilt); c.lineTo(ex + 8, eyeY - 10 - tilt); c.stroke();
      });

      // Mouth
      c.strokeStyle = '#c0725a'; c.lineWidth = 2.5; c.lineCap = 'round';
      const my = cy + r * .35;
      c.beginPath();
      if (mood === 'happy') {
        c.arc(cx, my - 6, 10, 0.15 * Math.PI, 0.85 * Math.PI);
      } else if (mood === 'sad') {
        c.arc(cx, my + 6, 10, 1.15 * Math.PI, 1.85 * Math.PI);
      } else {
        c.moveTo(cx - 9, my); c.lineTo(cx + 9, my);
      }
      c.stroke();

      // Blush
      if (mood === 'happy') {
        c.fillStyle = 'rgba(255,150,150,.18)';
        [-1, 1].forEach(side => {
          c.beginPath(); c.ellipse(cx + side * r * .45, cy + r * .05, 10, 6, 0, 0, Math.PI * 2); c.fill();
        });
      }

      // Mirror rim
      c.strokeStyle = 'rgba(255,182,219,.7)'; c.lineWidth = 2.5;
      c.beginPath(); c.arc(cx, cy, r, 0, Math.PI * 2); c.stroke();
    }

    // ── Init stars ────────────────────────────────────────────
    (function () {
      const bg = document.getElementById('starBg');
      const colors = ['#ffb3d9', '#b3e5fc', '#e8d5ff', '#ffeaa7'];
      for (let i = 0; i < 28; i++) {
        const d = document.createElement('div');
        d.className = 'star';
        const size = Math.random() * 8 + 4;
        d.style.cssText = `width:${size}px;height:${size}px;left:${Math.random() * 100}%;top:${Math.random() * 100}%;background:${colors[i % 4]};opacity:.3;animation-delay:${Math.random() * 3}s;animation-duration:${2 + Math.random() * 3}s`;
        bg.appendChild(d);
      }
    })();
  </script>

  @yield('scripts')
</body>

</html>