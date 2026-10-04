<?php

namespace App\Http\Controllers;

use App\Models\Box;
use Illuminate\Support\Facades\Route;
use Illuminate\View\View;

class HomeController extends Controller
{
  public function index(): View
  {
    $boxes = Box::with('ingredients')->orderBy('order')->get();
    $isPro = auth()->user()->is_pro;

    $boxesJson = $boxes->map(function ($b) use ($isPro) {
      return [
        'id' => $b->id,
        'name' => $b->name,
        'condition' => $b->condition_label,
        'faceType' => $b->face_type,
        'icon' => $b->icon,
        'locked' => $b->is_locked && !$isPro,
        'url' => route('box.show', $b->id),
      ];
    })->values();

    return view('home', compact('boxes', 'boxesJson', 'isPro'));
  }
}
