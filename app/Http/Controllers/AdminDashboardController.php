<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $paid = Order::where('status', 'paid')
            ->where('paid_at', '>=', now()->subDays(6)->startOfDay())
            ->get(['amount', 'paid_at'])
            ->groupBy(fn ($o) => $o->paid_at->format('Y-m-d'));

        $chart = collect(range(6, 0))->map(function ($i) use ($paid) {
            $day = now()->subDays($i);

            return [
                'label' => $day->format('d/m'),
                'total' => (int) $paid->get($day->format('Y-m-d'), collect())->sum('amount'),
            ];
        });

        return view('admin.dashboard', [
            'counts'    => Order::selectRaw('status, count(*) as total')->groupBy('status')->pluck('total', 'status'),
            'revenue'   => Order::where('status', 'paid')->sum('amount'),
            'userCount' => User::count(),
            'proCount'  => User::where('is_pro', true)->count(),
            'chart'     => $chart,
            'chartMax'  => max(1, $chart->max('total')),
            'queue'     => Order::with('user:id,name,email')
                ->where('status', 'awaiting_review')
                ->oldest('proof_uploaded_at')
                ->limit(5)
                ->get(),
            'newUsers'  => User::latest()->limit(5)->get(['id', 'name', 'email', 'is_pro', 'created_at']),
        ]);
    }
}