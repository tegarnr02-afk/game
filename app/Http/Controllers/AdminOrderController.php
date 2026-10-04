<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminOrderController extends Controller
{
    private const TABS = [
        'all'             => 'Semua',
        'awaiting_review' => 'Menunggu Review',
        'pending'         => 'Belum Upload Bukti',
        'paid'            => 'Disetujui',
        'rejected'        => 'Ditolak',
    ];

    public function index(Request $request)
    {
        $status = $request->query('status', 'awaiting_review');
        if (! array_key_exists($status, self::TABS)) {
            $status = 'awaiting_review';
        }

        $search = trim((string) $request->query('q', ''));

        $orders = Order::with('user:id,name,email')
            ->when($status !== 'all', fn ($q) => $q->where('status', $status))
            ->when($search !== '', fn ($q) => $q->where(function ($w) use ($search) {
                $w->where('order_code', 'like', "%{$search}%")
                  ->orWhereHas('user', fn ($u) => $u->where('email', 'like', "%{$search}%"));
            }))
            ->latest('proof_uploaded_at')
            ->latest()
            ->simplePaginate(15)
            ->withQueryString();

        return view('admin.orders.index', [
            'orders'   => $orders,
            'status'   => $status,
            'search'   => $search,
            'tabs'     => self::TABS,
            'counts'   => Order::selectRaw('status, count(*) as total')->groupBy('status')->pluck('total', 'status'),
            'proCount' => User::where('is_pro', true)->count(),
            'revenue'  => Order::where('status', 'paid')->sum('amount'),
        ]);
    }

    public function show(Order $order)
    {
        $order->load('user:id,name,email,is_pro', 'reviewer:id,name');

        return view('admin.orders.show', [
            'order' => $order,
            'bank'  => Order::BANK,
        ]);
    }

    public function proof(Order $order)
    {
        abort_unless($order->proof_path && Storage::disk('local')->exists($order->proof_path), 404);

        return Storage::disk('local')->response($order->proof_path);
    }

    public function approve(Request $request, Order $order)
    {
        $data = $request->validate([
            'approve_note' => ['nullable', 'string', 'max:300'],
        ]);

        if (! $order->approve($request->user(), $data['approve_note'] ?? null)) {
            return redirect()->route('admin.orders.show', $order)
                ->with('error', 'Pesanan tidak bisa disetujui: sudah diproses atau pengguna belum mengunggah bukti.');
        }

        return redirect()->route('admin.orders.index')
            ->with('status', "Pesanan {$order->order_code} disetujui. {$order->user->name} sekarang Pro.");
    }

    public function reject(Request $request, Order $order)
    {
        $data = $request->validate([
            'note' => ['required', 'string', 'min:5', 'max:300'],
        ], [
            'note.required' => 'Alasan penolakan wajib diisi.',
            'note.min'      => 'Alasan penolakan minimal 5 karakter.',
            'note.max'      => 'Alasan penolakan maksimal 300 karakter.',
        ]);

        if (! $order->reject($request->user(), $data['note'])) {
            return redirect()->route('admin.orders.show', $order)
                ->with('error', 'Pesanan ini sudah diproses sehingga tidak bisa ditolak.');
        }

        return redirect()->route('admin.orders.index')
            ->with('status', "Pesanan {$order->order_code} ditolak.");
    }
}