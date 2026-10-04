<?php

namespace App\Http\Controllers;

use App\Models\Box;
use Illuminate\View\View;

class BoxController extends Controller
{
  public function show(Box $box): View
  {
    if ($box->is_locked && !auth()->user()->is_pro) {
      return view('box-locked', compact('box'));
    }

    $box->load('ingredients');
    return view('box', compact('box'));
  }
}
