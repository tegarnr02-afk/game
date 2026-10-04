<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    public function show(Request $request)
    {
        $user = $request->user();

        if ($user->is_pro) {
            return redirect()->route('home');
        }

        // Kalau ada pesanan menunggu verifikasi ATAU ditolak → arahkan ke halaman order
        // supaya user bisa melihat statusnya (termasuk alasan penolakan)
        $existing = Order::where('user_id', $user->id)
            ->whereIn('status', ['awaiting_review', 'rejected'])
            ->latest()
            ->first();

        if ($existing) {
            return redirect()->route('upgrade.order', $existing);
        }

        return view('upgrade.index', ['plan' => Order::PLAN]);
    }

    public function checkout(Request $request)
    {
        $user = $request->user();

        // Pakai ulang pesanan yang belum selesai, supaya tidak menumpuk.
        $order = Order::where('user_id', $user->id)
            ->whereIn('status', ['pending', 'awaiting_review', 'rejected'])
            ->latest()
            ->first()
            ?? Order::create([
                'user_id' => $user->id,
                'order_code' => 'DMT-' . strtoupper(Str::random(10)),
                'plan' => Order::PLAN['code'],
                'amount' => Order::PLAN['amount'],
                'payment_method' => 'bank_transfer',
                'status' => 'pending',
            ]);

        return redirect()->route('upgrade.order', $order);
    }

    public function order(Request $request, Order $order)
    {
        abort_unless($order->user_id === $request->user()->id, 403);

        return view('upgrade.order', [
            'order' => $order,
            'bank' => Order::BANK,
        ]);
    }

    public function uploadProof(Request $request, Order $order)
    {
        abort_unless($order->user_id === $request->user()->id, 403);
        abort_if($order->status === 'paid', 403);

        $request->validate([
            'proof' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ], [
            'proof.required' => 'Pilih foto bukti transfer dulu.',
            'proof.image' => 'File harus berupa gambar.',
            'proof.mimes' => 'Format harus JPG, PNG, atau WEBP.',
            'proof.max' => 'Ukuran file maksimal 4 MB.',
            'proof.uploaded' => 'Unggahan gagal. Coba file yang lebih kecil.',
        ]);

        // Ganti file lama kalau user mengirim ulang.
        if ($order->proof_path) {
            Storage::disk('local')->delete($order->proof_path);
        }

        $order->update([
            'proof_path' => $request->file('proof')->store('payment-proofs', 'local'),
            'proof_uploaded_at' => now(),
            'status' => 'awaiting_review',
        ]);

        return redirect()->route('upgrade.order', $order);
    }

    // Menampilkan bukti milik sendiri (file disimpan privat, tidak bisa diakses lewat URL publik).
    public function proof(Request $request, Order $order)
    {
        abort_unless($order->user_id === $request->user()->id, 403);
        abort_unless($order->proof_path && Storage::disk('local')->exists($order->proof_path), 404);

        return Storage::disk('local')->response($order->proof_path);
    }
}