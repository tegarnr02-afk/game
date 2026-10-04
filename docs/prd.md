# PRD --- Game Cocok Bahan Skincare (Box Game)

**Implementasi: Laravel + Blade + Alpine.js**

------------------------------------------------------------------------

## 1. Ringkasan

Game edukasi skincare berbasis web: pemain memilih *box* (kondisi kulit
tertentu), lalu mencocokkan dua dari lima bahan aktif untuk melihat
apakah pasangan tersebut aman/direkomendasikan. Versi pertama fokus pada
Box 1--2 yang playable, sementara Box 3--4 tampil terkunci sebagai
pratinjau untuk fase berbayar berikutnya.

## 2. Ruang Lingkup

  In-scope (v1)                                 Out-of-scope (v1)
  --------------------------------------------- -------------------------------
  Beranda dengan 4 box + cermin                 Pembayaran / unlock Box 3--4
  Loop bermain penuh Box 1 & 2 (20 pasangan)    Integrasi QRIS
  40 pasangan tersimpan di database (4 box)     Pasangan antar-box
  Toggle musik                                  Aset ilustrasi generatif (AI)
  Popup hasil pink/biru + status hijau/kuning   Verifikasi pembayaran

## 3. Alur Pengguna

**Beranda**

-   Tombol **Start** membuka box yang sedang dipilih (default salah satu
    box terbuka).
-   Tombol **Next** menggeser pilihan box dan memperbesar pratinjau
    wajah box tersebut.
-   Cermin (mirror) tiap box bisa diklik langsung untuk masuk ke box
    itu.
-   Toggle musik: audio baru mulai diputar setelah user klik
    (menghindari autoplay block browser).

**Loop Bermain (Box 1 & 2)**

1.  Pemain memilih 2 dari 5 bahan aktif yang ditampilkan sebagai ikon.
2.  **Klik pertama** (bahan A) → tampilkan petunjuk/hint singkat.
3.  **Klik kedua** (bahan B) → popup pink dengan teks biru muncul,
    berisi:
    -   Status **hijau** (aman/cocok) atau **kuning** (perlu hati-hati)
        sesuai data pasangan.
    -   Keterangan pasangan sesuai teks sumber (40 pasangan asli, tidak
        dikarang ulang).
4.  Wajah karakter bereaksi visual (ekspresi berubah) sesuai status.
5.  Pemain bisa **coba pasangan lain** atau **kembali ke beranda**.

**Box 3 & 4**

-   Tampil sebagai pratinjau (preview wajah + info kondisi) dan status
    **terkunci**.
-   Tidak ada interaksi cocok-bahan, tidak ada pembukaan room, tidak ada
    pembayaran di v1.

## 4. Arsitektur Laravel

    app/
    ├── Http/Controllers/
    │   ├── HomeController.php        // beranda, pilih box, toggle preview
    │   ├── BoxController.php         // load box + 5 bahan aktifnya
    │   └── PairController.php        // cek pasangan bahan (2 klik)
    ├── Models/
    │   ├── Box.php                   // 4 box (id, nama, kondisi, status locked/unlocked, urutan cermin)
    │   ├── Ingredient.php            // bahan aktif per box (nama, ikon, hint)
    │   └── IngredientPair.php        // 40 pasangan (box_id, ingredient_a, ingredient_b, status, keterangan)
    database/
    ├── migrations/
    │   ├── create_boxes_table.php
    │   ├── create_ingredients_table.php
    │   └── create_ingredient_pairs_table.php
    └── seeders/
        └── IngredientPairSeeder.php  // isi 40 pasangan asli dari 4 box, tanpa pasangan antar-box
    resources/
    ├── views/
    │   ├── home.blade.php            // beranda: Start, Next, cermin, toggle musik
    │   ├── box.blade.php             // 5 ikon bahan + wajah + popup
    │   └── box-locked.blade.php      // pratinjau Box 3–4
    └── js/
        └── game.js                   // logic klik 1 & 2, render popup, ganti ekspresi wajah
    routes/
    └── web.php

### Skema Database (ringkas)

**boxes**: `id`, `name`, `condition_label`, `is_locked` (bool), `order`,
`mirror_asset` **ingredients**: `id`, `box_id`, `name`, `icon_key`,
`hint_text` **ingredient_pairs**: `id`, `box_id`, `ingredient_a_id`,
`ingredient_b_id`, `status` (`hijau`/`kuning`), `description` (teks asli
dari dokumen sumber)

> Catatan: `ingredient_pairs` **tidak** boleh memiliki baris yang
> menghubungkan `box_id` berbeda --- validasi ini ditegakkan di level
> seeder & constraint aplikasi, bukan hanya asumsi data.

### Route (contoh)

``` php
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/box/{box}', [BoxController::class, 'show'])->name('box.show');
Route::post('/box/{box}/pair', [PairController::class, 'check'])->name('box.pair.check');
```

`PairController::check` menerima `ingredient_a_id` & `ingredient_b_id`,
mencari baris `ingredient_pairs` yang cocok (urutan A/B tidak masalah),
lalu mengembalikan JSON `status` + `description` untuk dirender
Alpine.js sebagai popup.

## 5. Konten

-   Seluruh **40 pasangan** dari 4 box disimpan persis sesuai teks
    sumber, tanpa parafrase yang mengubah makna medis.
-   Hanya pasangan di **Box 1 & 2** yang dapat diuji/dimainkan di v1;
    pasangan Box 3 & 4 tetap tersimpan di database untuk dipakai saat
    box tersebut dibuka nanti.
-   Setiap layar box menampilkan catatan tetap:
    -   Uji toleransi kulit sebelum pemakaian bahan baru.
    -   Anjuran penggunaan sunscreen SPF 30+.
    -   Disclaimer: hasil permainan adalah edukasi, **bukan** jaminan
        atau saran medis.

## 6. Visual

-   Wajah karakter 2D dibuat orisinal per kondisi box (bukan foto/aset
    pihak ketiga), digambar dengan Canvas atau CSS sederhana.
-   Cermin dan ikon bahan juga dibuat dengan Canvas/CSS sederhana ---
    sengaja minimalis, bukan placeholder yang belum jadi.
-   Tidak menggunakan aset ilustrasi hasil generative AI di versi hemat
    biaya ini (estimasi 12,38 Credits jika dipakai, dan tidak dipilih
    untuk v1).
-   Tidak ditemukan aset lama proyek yang bisa dipakai ulang, sehingga
    semua visual dibuat baru dengan pendekatan sederhana di atas.

## 7. Pemeriksaan & Deploy Pertama

Checklist sebelum deploy pertama dianggap lolos:

Build berhasil tanpa error

Tampilan diuji di 2 ukuran viewport desktop

Keempat cermin (Box 1--4) bisa diklik dan menampilkan box yang benar

Seluruh 20 pasangan yang playable (Box 1 & 2) diuji dan menampilkan
status + keterangan yang benar

Popup dan perubahan ekspresi wajah berfungsi sesuai status hijau/kuning

Toggle musik berfungsi (baru menyala setelah diklik)

Box 3 & 4 tampil terkunci, tidak bisa dimainkan

**Checkpoint deploy pertama**: loop Box 1--2 dan alur kembali ke beranda
sudah bisa dicoba end-to-end dan lolos seluruh poin di atas.

## 8. Batasan Versi Pertama

-   Box 3 & 4 hanya menampilkan pratinjau wajah + info kondisi + status
    terkunci --- **belum** membuka room, **belum** menerima pembayaran.
-   Integrasi QRIS dan pembukaan stage berbayar **ditunda** sampai
    tersedia:
    -   Akun QRIS merchant yang sah.
    -   Mekanisme verifikasi pembayaran (callback/webhook + validasi
        status transaksi).

## 9. Fase Berikutnya (di luar scope v1)

-   Integrasi pembayaran QRIS (merchant account + webhook verifikasi).
-   Unlock Box 3 & 4 setelah pembayaran terverifikasi (state `is_locked`
    di tabel `boxes` di-*update* via job/listener setelah callback
    sukses).
-   Kemungkinan aset ilustrasi generatif jika budget tersedia di fase
    berikutnya.
