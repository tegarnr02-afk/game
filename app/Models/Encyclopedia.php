<?php

namespace App\Http\Controllers;

class EncyclopediaController extends Controller
{
    public function index()
    {
        $data = require app_path('Models/Encyclopedia.php');

        $items = collect($data['ingredients'])
            ->map(function ($i) {
                $i['icon_url'] = !empty($i['icon'])
                    ? asset('images/ingredients/' . $i['icon'])
                    : null;
                unset($i['icon']);
                return $i;
            })
            ->values();

        return view('encyclopedia.index', compact('items'));
    }
}