<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AdminUserController extends Controller
{
    private const TABS = [
        'all'   => 'Semua',
        'admin' => 'Admin',
        'pro'   => 'Pro',
        'free'  => 'Free',
    ];

    public function index(Request $request)
    {
        $filter = $request->query('filter', 'all');
        if (! array_key_exists($filter, self::TABS)) {
            $filter = 'all';
        }

        $search = trim((string) $request->query('q', ''));

        $users = User::withCount('orders')
            ->when($filter === 'admin', fn ($q) => $q->where('is_admin', true))
            ->when($filter === 'pro', fn ($q) => $q->where('is_pro', true))
            ->when($filter === 'free', fn ($q) => $q->where('is_pro', false))
            ->when($search !== '', fn ($q) => $q->where(function ($w) use ($search) {
                $w->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            }))
            ->latest()
            ->simplePaginate(15)
            ->withQueryString();

        return view('admin.users.index', [
            'users'  => $users,
            'filter' => $filter,
            'search' => $search,
            'tabs'   => self::TABS,
            'counts' => [
                'all'   => User::count(),
                'admin' => User::where('is_admin', true)->count(),
                'pro'   => User::where('is_pro', true)->count(),
                'free'  => User::where('is_pro', false)->count(),
            ],
        ]);
    }

    public function pro(Request $request, User $user)
    {
        $request->validate(['value' => ['required', 'boolean']]);
        $value = $request->boolean('value');

        $user->forceFill(['is_pro' => $value])->save();

        return back()->with('status', $value
            ? "{$user->name} sekarang Pro."
            : "Akses Pro {$user->name} dicabut.");
    }

    public function admin(Request $request, User $user)
    {
        $request->validate(['value' => ['required', 'boolean']]);

        if ($user->is($request->user())) {
            return back()->with('error', 'Kamu tidak bisa mengubah akses admin akunmu sendiri.');
        }

        $value = $request->boolean('value');
        $user->forceFill(['is_admin' => $value])->save();

        return back()->with('status', $value
            ? "{$user->name} sekarang Admin."
            : "Akses Admin {$user->name} dicabut.");
    }

    public function destroy(Request $request, User $user)
    {
        if ($user->is($request->user())) {
            return back()->with('error', 'Kamu tidak bisa menghapus akunmu sendiri.');
        }

        if ($user->is_admin) {
            return back()->with('error', 'Turunkan akses admin akun ini dulu sebelum menghapusnya.');
        }

        $name   = $user->name;
        $proofs = $user->orders()->whereNotNull('proof_path')->pluck('proof_path')->all();

        DB::transaction(function () use ($user) {
            DB::table('sessions')->where('user_id', $user->id)->delete();
            $user->orders()->delete();
            $user->delete();
        });

        if ($proofs) {
            Storage::disk('local')->delete($proofs);
        }

        return back()->with('status', "Akun {$name} dihapus.");
    }
}