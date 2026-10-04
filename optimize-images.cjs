/**
 * optimize-images.js
 * -------------------------------------------------------
 * Batch-compress & convert PNG assets to WebP for game-salon.
 *
 * SETUP (run once, di folder project Laragon kamu):
 *   cd C:\laragon\www\game-salon
 *   npm install sharp
 *
 * USAGE:
 *   node optimize-images.js
 *
 * Ini akan baca semua file dari public/images/,
 * lalu tulis versi WebP-nya ke public/images/webp/
 * dengan ukuran & kualitas yang disesuaikan per jenis asset.
 * File PNG asli TIDAK dihapus (dipakai sebagai fallback).
 * -------------------------------------------------------
 */

const sharp = require('sharp');
const fs = require('fs');
const path = require('path');

const SRC_DIR = path.join(__dirname, 'public', 'images');
const OUT_DIR = path.join(SRC_DIR, 'webp');

if (!fs.existsSync(OUT_DIR)) fs.mkdirSync(OUT_DIR, { recursive: true });

// Konfigurasi per file: resize width (px) & quality WebP (0-100).
// Sesuaikan angka ini dengan ukuran tampil sebenarnya di CSS-mu.
// Aturan umum: jangan kirim gambar lebih besar dari ukuran tampil
// terbesarnya (biasanya = lebar layar desktop, ~1920px untuk full bg).
const CONFIG = {
  'home.png': { width: 1920, quality: 78 },          // desktop bg, full width
  'home-mobile.png': { width: 900, quality: 78 },    // mobile bg, cukup ~2x lebar HP (≈768px+headroom)
  'avatar.png': { width: 500, quality: 85 },          // biasanya elemen kecil/medium
  'bg.png': { width: 1920, quality: 75 },
  'logo.png': { width: 300, quality: 90 },            // logo perlu quality tinggi, ukuran kecil
  'logo-desktop.png': { width: 400, quality: 90 },
  'stage1.png': { width: 700, quality: 82 },
  'stage2.png': { width: 700, quality: 82 },
  'stage3.png': { width: 700, quality: 82 },
  'stage4.png': { width: 700, quality: 82 },
  'teks.png': { width: 600, quality: 88 },            // teks/tipografi, jaga ketajaman
  'teks2.png': { width: 600, quality: 88 },
};

async function run() {
  const files = fs.readdirSync(SRC_DIR).filter(f => f.toLowerCase().endsWith('.png'));

  console.log(`Ditemukan ${files.length} file PNG. Memproses...\n`);

  let totalBefore = 0;
  let totalAfter = 0;

  for (const file of files) {
    const srcPath = path.join(SRC_DIR, file);
    const cfg = CONFIG[file] || { width: 1200, quality: 80 }; // fallback default
    const outName = file.replace(/\.png$/i, '.webp');
    const outPath = path.join(OUT_DIR, outName);

    const beforeSize = fs.statSync(srcPath).size;

    await sharp(srcPath)
      .resize({ width: cfg.width, withoutEnlargement: true })
      .webp({ quality: cfg.quality })
      .toFile(outPath);

    const afterSize = fs.statSync(outPath).size;
    totalBefore += beforeSize;
    totalAfter += afterSize;

    const savedPct = (((beforeSize - afterSize) / beforeSize) * 100).toFixed(1);
    console.log(
      `${file.padEnd(20)} ${(beforeSize / 1024).toFixed(0).padStart(6)} KB  →  ` +
      `${outName.padEnd(20)} ${(afterSize / 1024).toFixed(0).padStart(6)} KB   ` +
      `(hemat ${savedPct}%)`
    );
  }

  console.log('\n──────────────────────────────────────────');
  console.log(`Total sebelum : ${(totalBefore / 1024 / 1024).toFixed(2)} MB`);
  console.log(`Total sesudah : ${(totalAfter / 1024 / 1024).toFixed(2)} MB`);
  console.log(`Hemat total   : ${(((totalBefore - totalAfter) / totalBefore) * 100).toFixed(1)}%`);
  console.log(`\nHasil ada di: ${OUT_DIR}`);
}

run().catch(err => {
  console.error('Gagal:', err);
  process.exit(1);
});
