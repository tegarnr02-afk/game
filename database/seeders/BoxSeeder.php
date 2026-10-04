<?php

namespace Database\Seeders;

use App\Models\Box;
use Illuminate\Database\Seeder;

class BoxSeeder extends Seeder
{
  public function run(): void
  {
    $boxes = [
      [
        'name' => 'Brightening Zone',
        'condition_label' => 'Dehidrasi, kusam, butuh hidrasi ekstra',
        'condition_label_after' => 'Kulit lembap, cerah, dan lebih bercahaya',
        'is_locked' => false,
        'order' => 1,
        'face_type' => 'dry',
        'icon' => '💧',
      ],
      [
        'name' => 'Acne Zone',
        'condition_label' => 'Bentol jerawat merah menonjol di jidat & dagu, kilap minyak berlebih di T-zone',
        'condition_label_after' => 'Peradangan mereda, minyak lebih terkontrol, kulit lebih tenang',
        'is_locked' => false,
        'order' => 2,
        'face_type' => 'oily',
        'icon' => '🔴',
      ],
     [
  'name' => 'Aging & Antioxidant Zone',
  'condition_label' => "Garis-garis halus di sudut mata (crow's feet) dan lipatan senyum, kulit terlihat kurang bervolume/kendor",
  'condition_label_after' => 'Garis halus tampak berkurang, kulit lebih kenyal dan segar',
  'is_locked' => true,
  'order' => 3,
  'face_type' => 'aging',
  'icon' => '🌟',
],
[
  'name' => 'Renew Zone',
  'condition_label' => 'Bintik hitam kecil (komedo/pori tersumbat) di hidung dan bercak kulit bersisik/kering di sekitar pipi',
  'condition_label_after' => 'Pori lebih bersih, tekstur kulit lebih halus dan merata',
  'is_locked' => true,
  'order' => 4,
  'face_type' => 'renew',
  'icon' => '🔄',
],
    ];

    foreach ($boxes as $box) {
      Box::create($box);
    }
  }
}