<?php

namespace Database\Seeders;

use App\Models\Box;
use App\Models\Ingredient;
use App\Models\IngredientPair;
use Illuminate\Database\Seeder;

class IngredientPairSeeder extends Seeder
{
  private const SERASI = 'Tidak ada larangan umum untuk dipakai bersama; manfaat pada noda/warna kulit atau dukungan barrier dapat saling melengkapi.';
  private const TENANG = 'Bahan penenang/pelembap dapat mendukung toleransi. Efek tetap bergantung pada formula dan kondisi kulit.';
  private const EKSFOLIAN = 'Bisa meningkatkan rasa perih karena keasaman/eksfoliasi; mulai dengan frekuensi rendah atau pisahkan waktu.';
  private const BARU = 'Tidak dikenal konflik umum; kenalkan produk baru secara bertahap dan amati respons kulit.';

  public function run(): void
  {
    $S = self::SERASI; $T = self::TENANG; $E = self::EKSFOLIAN; $B = self::BARU;

    // ─── BOX 1: Brightening Zone ───
    $this->seedBox(1, [
      ['Alpha Arbutin', 'Vitamin C',   'hijau',  $S],
      ['Alpha Arbutin', 'Niacinamide', 'hijau',  $S],
      ['Alpha Arbutin', 'Kojic Acid',  'hijau',  $S],
      ['Alpha Arbutin', 'AHA',         'kuning', 'Umumnya mungkin digunakan; eksfolian dapat menambah rasa perih pada kulit sensitif. Mulai terpisah dan pantau toleransi.'],
      ['Vitamin C',     'Niacinamide', 'hijau',  $S],
      ['Vitamin C',     'Kojic Acid',  'kuning', 'Sama-sama menarget noda; potensi perih meningkat pada kulit sensitif. Tambahkan bertahap.'],
      ['Vitamin C',     'AHA',         'kuning', $E],
      ['Niacinamide',   'Kojic Acid',  'hijau',  $S],
      ['Niacinamide',   'AHA',         'hijau',  $S],
      ['Kojic Acid',    'AHA',         'kuning', $E],
    ]);

    // ─── BOX 2: Acne Zone ───
    $this->seedBox(2, [
      ['BHA (Salicylic Acid)', 'Benzoyl Peroxide',          'kuning', 'Keduanya dapat mengeringkan; bukan larangan mutlak. Mulai perlahan, gunakan pelembap, dan pisahkan waktu bila perih.'],
      ['BHA (Salicylic Acid)', 'Ceramide',                  'hijau',  $T],
      ['BHA (Salicylic Acid)', 'Azelaic Acid',              'kuning', $E],
      ['BHA (Salicylic Acid)', 'Centella Asiatica (Cica)',  'hijau',  $T],
      ['Benzoyl Peroxide',     'Ceramide',                  'hijau',  $T],
      ['Benzoyl Peroxide',     'Azelaic Acid',              'kuning', 'Potensi kering/perih bertambah; pisahkan waktu pemakaian bila kulit sensitif.'],
      ['Benzoyl Peroxide',     'Centella Asiatica (Cica)',  'hijau',  $T],
      ['Ceramide',             'Azelaic Acid',              'hijau',  $T],
      ['Ceramide',             'Centella Asiatica (Cica)',  'hijau',  $T],
      ['Azelaic Acid',         'Centella Asiatica (Cica)',  'hijau',  $T],
    ]);

    // ─── BOX 3: Aging & Antioxidant Zone ───
    $this->seedBox(3, [
      ['Peptide',   'Bakuchiol',       'hijau',  $B],
      ['Peptide',   'Vitamin E',       'hijau',  $T],
      ['Peptide',   'Retinol',         'hijau',  $B],
      ['Peptide',   'Tranexamic Acid', 'hijau',  $S],
      ['Bakuchiol', 'Vitamin E',       'hijau',  $T],
      ['Bakuchiol', 'Retinol',         'kuning', 'Sama-sama dipakai untuk perawatan penuaan; kulit sensitif dapat lebih mudah iritasi. Kenalkan bertahap.'],
      ['Bakuchiol', 'Tranexamic Acid', 'hijau',  $S],
      ['Vitamin E', 'Retinol',         'hijau',  $T],
      ['Vitamin E', 'Tranexamic Acid', 'hijau',  $T],
      ['Retinol',   'Tranexamic Acid', 'hijau',  $S],
    ]);

    // ─── BOX 4: Renew Zone ───
    $this->seedBox(4, [
      ['Niacinamide', 'Vitamin C',            'hijau',  $S],
      ['Niacinamide', 'Retinol',              'hijau',  $S],
      ['Niacinamide', 'BHA (Salicylic Acid)', 'hijau',  $S],
      ['Niacinamide', 'Lactic Acid',          'hijau',  $S],
      ['Vitamin C',   'Retinol',              'kuning', 'Boleh dalam rutinitas berbeda; tumpukan bahan aktif berpotensi memicu perih. Mulai perlahan dan pantau kulit.'],
      ['Vitamin C',   'BHA (Salicylic Acid)', 'kuning', $E],
      ['Vitamin C',   'Lactic Acid',          'kuning', $E],
      ['Retinol',     'BHA (Salicylic Acid)', 'kuning', 'Retinol dan BHA leave-on dapat mengiritasi; pemula sebaiknya pisah malam. BHA bilas dapat berbeda toleransinya.'],
      ['Retinol',     'Lactic Acid',          'merah',  'Eksfoliasi dan retinol sekaligus mudah mengiritasi; gunakan pada malam berbeda.'],
      ['BHA (Salicylic Acid)', 'Lactic Acid', 'kuning', 'Dua produk eksfolian dapat meningkatkan perih dan kering; mulai terpisah, gunakan bergantian bila kulit sensitif. Produk gabungan yang diformulasikan khusus bisa berbeda.'],
    ]);
  }

  /** Cari ID bahan lewat NAMA, bukan urutan, supaya aman kalau seeder bahan berubah. */
  private function seedBox(int $order, array $pairs): void
  {
    $box = Box::where('order', $order)->firstOrFail();
    $ids = Ingredient::where('box_id', $box->id)->pluck('id', 'name');

    foreach ($pairs as [$a, $b, $status, $desc]) {
      IngredientPair::create([
        'box_id'          => $box->id,
        'ingredient_a_id' => $ids[$a] ?? throw new \RuntimeException("Bahan '$a' tidak ada di box $order"),
        'ingredient_b_id' => $ids[$b] ?? throw new \RuntimeException("Bahan '$b' tidak ada di box $order"),
        'status'          => $status,
        'title'           => "$a + $b",
        'description'     => $desc,
      ]);
    }
  }
}