<?php

namespace App\Http\Controllers;

use App\Models\Box;
use App\Models\IngredientPair;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PairController extends Controller
{
  public function check(Request $request, Box $box): JsonResponse
  {
    $request->validate([
      'ingredient_a_id' => ['required', 'integer'],
      'ingredient_b_id' => ['required', 'integer'],
    ]);

    $a = (int) $request->ingredient_a_id;
    $b = (int) $request->ingredient_b_id;

    // Order-independent lookup
    $pair = IngredientPair::where('box_id', $box->id)
      ->where(function ($q) use ($a, $b) {
        $q->where(fn($q2) => $q2->where('ingredient_a_id', $a)->where('ingredient_b_id', $b))
          ->orWhere(fn($q2) => $q2->where('ingredient_a_id', $b)->where('ingredient_b_id', $a));
      })
      ->first();

    if (!$pair) {
      return response()->json(['error' => 'Pasangan tidak ditemukan.'], 404);
    }

    return response()->json([
      'status' => $pair->status,   // 'hijau' | 'kuning'
      'title' => $pair->title,
      'description' => $pair->description,
    ]);
  }
}
