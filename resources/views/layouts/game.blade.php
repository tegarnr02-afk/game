<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'Skin Salon') – Skincare Mix Lab</title>
  <meta name="description" content="Game edukasi skincare: cocokkan bahan aktif dan glow up kulitmu!">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link
    href="https://fonts.googleapis.com/css2?family=Nunito:wght@700;800;900&family=Quicksand:wght@600;700&display=swap"
    rel="stylesheet">
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="game-body">

  @yield('content')

  <script>
    // ── Music ───────────────────────────────────
    let musicVolume = 1;
    try {
      const v = parseFloat(localStorage.getItem('musicVolume'));
      if (!isNaN(v)) musicVolume = Math.min(1, Math.max(0, v));
    } catch (e) { }

    // Status pilihan pengguna, dibaca dari penyimpanan browser
    let musicOn = false;
    try { musicOn = localStorage.getItem('musicOn') === '1'; } catch (e) { }

    const bgm = new Audio("{{ asset('audio/bgm.mp3') }}");
    bgm.loop = true;
    bgm.preload = 'auto';
    bgm.volume = musicVolume;
    try {
      const t = parseFloat(sessionStorage.getItem('bgmTime'));
      if (!isNaN(t)) bgm.currentTime = t;
    } catch (e) { }

    function saveMusicState() {
      try { localStorage.setItem('musicOn', musicOn ? '1' : '0'); } catch (e) { }
    }

    function setMusicVolume(v) {
      musicVolume = Math.min(1, Math.max(0, v));
      try { localStorage.setItem('musicVolume', musicVolume); } catch (e) { }
      bgm.volume = musicVolume;
    }

    // Sinkronkan label + ikon SVG dengan status musik.
    // Ikon: beri atribut data-music-icon="on" / "off" pada elemen <svg>.
    function setMusicUI(on) {
      const lbl = document.getElementById('musicLabel');
      if (lbl) lbl.textContent = on ? 'MUSIC ON' : 'MUSIC OFF';
      document.querySelectorAll('[data-music-icon="on"]').forEach(el => { el.hidden = !on; });
      document.querySelectorAll('[data-music-icon="off"]').forEach(el => { el.hidden = on; });
      const btn = document.getElementById('musicBtn');
      if (btn) btn.setAttribute('aria-pressed', on ? 'true' : 'false');
    }

    function announceMusic() {
      setMusicUI(musicOn);
      window.dispatchEvent(new CustomEvent('music-change', { detail: { on: musicOn } }));
    }

    function playBgm() {
      return bgm.play().then(() => true).catch((err) => {
        console.warn('BGM belum bisa diputar:', err.name, err.message);
        return false;
      });
    }

    function toggleMusic() {
      // Status ON tapi suara masih ditahan browser: tombol ini memulai suaranya
      if (musicOn && bgm.paused) { playBgm(); return; }

      musicOn = !musicOn;
      saveMusicState();
      if (musicOn) playBgm(); else bgm.pause();
      announceMusic();
    }

    // Simpan posisi lagu sebelum pindah halaman
    window.addEventListener('pagehide', () => {
      try { sessionStorage.setItem('bgmTime', bgm.currentTime); } catch (e) { }
    });

    // Simpan posisi lagu secara berkala (cadangan untuk HP)
    let lastSave = 0;
    bgm.addEventListener('timeupdate', () => {
      const now = Date.now();
      if (now - lastSave < 1000) return;
      lastSave = now;
      try { sessionStorage.setItem('bgmTime', bgm.currentTime); } catch (e) { }
    });

    document.addEventListener('DOMContentLoaded', () => {
      setMusicUI(musicOn);
      if (!musicOn) return;

      playBgm().then((ok) => {
        if (ok) return; // browser mengizinkan, musik langsung bunyi

        // Ditahan browser: coba lagi di setiap interaksi sampai berhasil
        const events = ['pointerdown', 'pointerup', 'mousedown', 'touchend', 'keydown', 'click'];
        const resume = (e) => {
          // tombol musik dan saklar pengaturan menangani kliknya sendiri
          if (e.target && e.target.closest && e.target.closest('#musicBtn, .settings-switch')) return;
          if (!musicOn || !bgm.paused) return;
          bgm.play().then(() => {
            events.forEach(ev => document.removeEventListener(ev, resume, true));
          }).catch((err) => {
            console.warn('BGM masih gagal:', err.name, err.message);
          });
        };
        events.forEach(ev => document.addEventListener(ev, resume, true));
      });
    });

    // ── Face Drawing ───────────────────────────────────
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
      const grd = c.createRadialGradient(cx - r * .2, cy - r * .2, r * .1, cx, cy, r);
      grd.addColorStop(0, s.base); grd.addColorStop(1, s.shadow);
      c.beginPath(); c.arc(cx, cy, r, 0, Math.PI * 2);
      c.fillStyle = grd; c.fill();
      if (type === 'dry') {
        c.fillStyle = 'rgba(200,150,100,.28)';
        [[cx - .28 * r, cy - .1 * r, 5], [cx + .22 * r, cy + .05 * r, 4], [cx, cy + .18 * r, 3.5]].forEach(([x, y, rs]) => { c.beginPath(); c.arc(x, y, rs, 0, Math.PI * 2); c.fill(); });
      } else if (type === 'oily') {
        const shine = c.createRadialGradient(cx, cy - .15 * r, 2, cx, cy - .15 * r, r * .55);
        shine.addColorStop(0, 'rgba(255,255,255,.42)'); shine.addColorStop(1, 'rgba(255,255,255,0)');
        c.beginPath(); c.arc(cx, cy, r, 0, Math.PI * 2); c.fillStyle = shine; c.fill();
        c.fillStyle = 'rgba(220,80,80,.65)';
        [[cx - .2 * r, cy - .1 * r], [cx + .25 * r, cy - .05 * r], [cx + .05 * r, cy + .2 * r]].forEach(([x, y]) => { c.beginPath(); c.arc(x, y, 3.5, 0, Math.PI * 2); c.fill(); });
      } else if (type === 'sensitive') {
        c.fillStyle = 'rgba(240,100,100,.16)';
        c.beginPath(); c.ellipse(cx - .3 * r, cy, r * .28, r * .2, 0, 0, Math.PI * 2); c.fill();
        c.beginPath(); c.ellipse(cx + .3 * r, cy, r * .28, r * .2, 0, 0, Math.PI * 2); c.fill();
      } else if (type === 'aging') {
        c.strokeStyle = 'rgba(150,100,60,.28)'; c.lineWidth = 1.5; c.lineCap = 'round';
        [[cx - .35 * r, cy - .2 * r, 14, 2], [cx + .2 * r, cy - .18 * r, 12, 1.5], [cx - .1 * r, cy + .3 * r, 18, 2]].forEach(([x, y, len, vy]) => { c.beginPath(); c.moveTo(x, y); c.quadraticCurveTo(x + len / 2, y + vy, x + len, y); c.stroke(); });
        c.fillStyle = 'rgba(120,80,40,.18)';
        [[cx + .1 * r, cy + .08 * r, 6], [cx - .2 * r, cy + .15 * r, 4.5]].forEach(([x, y, rs]) => { c.beginPath(); c.arc(x, y, rs, 0, Math.PI * 2); c.fill(); });
      }
      const eyeY = cy - r * .18;
      c.fillStyle = '#4a2f2f';
      [-1, 1].forEach(side => {
        const ex = cx + side * r * .3;
        if (mood === 'happy') { c.strokeStyle = '#4a2f2f'; c.lineWidth = 2.2; c.lineCap = 'round'; c.beginPath(); c.moveTo(ex - 6, eyeY); c.lineTo(ex + 6, eyeY); c.stroke(); }
        else { c.beginPath(); c.ellipse(ex, eyeY, 6, 7, 0, 0, Math.PI * 2); c.fill(); c.fillStyle = '#fff'; c.beginPath(); c.arc(ex + 2, eyeY - 2, 2.5, 0, Math.PI * 2); c.fill(); c.fillStyle = '#4a2f2f'; }
      });
      c.strokeStyle = '#6d4c41'; c.lineWidth = 2.5; c.lineCap = 'round';
      [-1, 1].forEach(side => { const ex = cx + side * r * .3; const tilt = mood === 'sad' ? 3 * side : -3 * side; c.beginPath(); c.moveTo(ex - 8, eyeY - 10 + tilt); c.lineTo(ex + 8, eyeY - 10 - tilt); c.stroke(); });
      c.strokeStyle = '#c0725a'; c.lineWidth = 2.5; c.lineCap = 'round';
      const my = cy + r * .35; c.beginPath();
      if (mood === 'happy') { c.arc(cx, my - 6, 10, 0.15 * Math.PI, 0.85 * Math.PI); }
      else if (mood === 'sad') { c.arc(cx, my + 6, 10, 1.15 * Math.PI, 1.85 * Math.PI); }
      else { c.moveTo(cx - 9, my); c.lineTo(cx + 9, my); }
      c.stroke();
      if (mood === 'happy') { c.fillStyle = 'rgba(255,150,150,.18)';[-1, 1].forEach(side => { c.beginPath(); c.ellipse(cx + side * r * .45, cy + r * .05, 10, 6, 0, 0, Math.PI * 2); c.fill(); }); }
      c.strokeStyle = 'rgba(255,182,219,.7)'; c.lineWidth = 2.5; c.beginPath(); c.arc(cx, cy, r, 0, Math.PI * 2); c.stroke();
    }
  </script>

  @yield('scripts')
</body>

</html>