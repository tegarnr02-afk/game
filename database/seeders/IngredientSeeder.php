<?php

namespace Database\Seeders;

use App\Models\Box;
use App\Models\Ingredient;
use Illuminate\Database\Seeder;

class IngredientSeeder extends Seeder
{
  public function run(): void
  {
    $data = [
      // Box 1 – Brightening Zone
      1 => [
        ['name' => 'Alpha Arbutin', 'icon_key' => '✨', 'hint_text' => 'Menghambat produksi melanin, mencerahkan kulit'],
        ['name' => 'Vitamin C', 'icon_key' => '🍊', 'hint_text' => 'Antioksidan kuat, mencerahkan noda gelap'],
        ['name' => 'AHA', 'icon_key' => '🍋', 'hint_text' => 'Eksfoliasi lembut, mengangkat sel kulit mati'],
        ['name' => 'Niacinamide', 'icon_key' => '⭐', 'hint_text' => 'Mencerahkan, memperbaiki pori & tekstur'],
        ['name' => 'Kojic Acid', 'icon_key' => '🍄', 'hint_text' => 'Mengurangi hiperpigmentasi & noda gelap'],
      ],
      // Box 2 – Acne Zone (disesuaikan dengan dokumen fitur)
      2 => [
        ['name' => 'BHA (Salicylic Acid)', 'icon_key' => '🔬', 'hint_text' => 'Eksfoliasi pori mendalam, meredakan komedo & jerawat'],
        ['name' => 'Benzoyl Peroxide', 'icon_key' => '💊', 'hint_text' => 'Membunuh bakteri P. acnes penyebab jerawat'],
        ['name' => 'Ceramide', 'icon_key' => '🛡️', 'hint_text' => 'Memperkuat skin barrier, menenangkan kulit meradang'],
        ['name' => 'Azelaic Acid', 'icon_key' => '🌾', 'hint_text' => 'Meredakan peradangan & mencerahkan bekas jerawat'],
        ['name' => 'Centella Asiatica (Cica)', 'icon_key' => '🌿', 'hint_text' => 'Menenangkan kulit meradang, mempercepat pemulihan'],
      ],
      // Box 3 – Kulit Sensitif & Reaktif
     // Box 3 – Aging & Antioxidant Zone
3 => [
  ['name' => 'Peptide',        'icon_key' => '🧬', 'hint_text' => 'Membantu kulit tampak lebih kenyal dan mendukung tekstur'],
  ['name' => 'Bakuchiol',      'icon_key' => '🌱', 'hint_text' => 'Alternatif lembut retinol untuk perawatan penuaan'],
  ['name' => 'Vitamin E',      'icon_key' => '🌻', 'hint_text' => 'Antioksidan yang melembapkan dan melindungi kulit'],
  ['name' => 'Retinol',        'icon_key' => '🌙', 'hint_text' => 'Membantu menyamarkan garis halus dan memperbaiki tekstur'],
  ['name' => 'Tranexamic Acid','icon_key' => '💧', 'hint_text' => 'Membantu meratakan warna kulit dan menyamarkan noda'],
],
// Box 4 – Renew Zone
4 => [
  ['name' => 'Niacinamide',            'icon_key' => '⭐', 'hint_text' => 'Membantu memperbaiki pori dan tekstur kulit'],
  ['name' => 'Vitamin C',              'icon_key' => '🍊', 'hint_text' => 'Antioksidan yang membantu mencerahkan kulit'],
  ['name' => 'Retinol',                'icon_key' => '🌙', 'hint_text' => 'Membantu pembaruan kulit dan menyamarkan garis halus'],
  ['name' => 'BHA (Salicylic Acid)',   'icon_key' => '🔬', 'hint_text' => 'Membersihkan pori tersumbat dan komedo'],
  ['name' => 'Lactic Acid',            'icon_key' => '🥛', 'hint_text' => 'Eksfoliasi lembut untuk kulit kering dan bersisik'],
],
    ];

    foreach ($data as $order => $ingredients) {
      $box = Box::where('order', $order)->firstOrFail();
      foreach ($ingredients as $ingr) {
        Ingredient::create(array_merge($ingr, ['box_id' => $box->id]));
      }
    }
  }
}