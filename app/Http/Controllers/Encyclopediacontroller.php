<?php

namespace App\Http\Controllers;

class EncyclopediaController extends Controller
{
    public function index()
    {
        $items = collect(config('encyclopedia.ingredients'))
            ->map(function (array $item) {
                $item['icon_url'] = ! empty($item['icon'])
                    ? asset('images/ingredients/' . $item['icon'])
                    : null;

                return $item;
            })
            ->values();

        return view('encyclopedia', ['items' => $items]);
    }
}