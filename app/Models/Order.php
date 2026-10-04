<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Order extends Model
{
    public const PLAN = [
        'code'   => 'pro-lifetime',
        'name'   => 'Dermatch Pro',
        'amount' => 15000,
    ];

    public const BANK = [
        'bank'   => 'BCA',
        'number' => '2891172496',
        'holder' => 'Ganes Diva Anjani',
    ];

    public const STATUS_LABELS = [
    'pending'         => 'Belum Upload Bukti',
    'awaiting_review' => 'Menunggu Review',
    'paid'            => 'Disetujui',
    'rejected'        => 'Ditolak',
];

public function planLabel(): string
{
    return $this->plan === self::PLAN['code'] ? self::PLAN['name'] : $this->plan;
}

public function methodLabel(): string
{
    return $this->payment_method === 'bank_transfer' ? 'Transfer Bank' : $this->payment_method;
}

    protected $fillable = [
        'user_id', 'order_code', 'plan', 'amount', 'payment_method',
        'proof_path', 'proof_uploaded_at', 'status', 'paid_at',
        'reviewed_by', 'reviewed_at', 'admin_note',
    ];

    protected function casts(): array
    {
        return [
            'paid_at'           => 'datetime',
            'proof_uploaded_at' => 'datetime',
            'reviewed_at'       => 'datetime',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'order_code';
    }

    public function statusLabel(): string
    {
        return self::STATUS_LABELS[$this->status] ?? $this->status;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    // Setujui: hanya dari "menunggu verifikasi" (atau "ditolak", untuk memperbaiki salah tolak).
   public function approve(User $admin, ?string $note = null): bool
{
    return DB::transaction(function () use ($admin, $note) {
        $order = static::whereKey($this->id)->lockForUpdate()->first();

        if ($order->status !== 'awaiting_review' || ! $order->proof_path) {
            return false;
        }

        $order->update([
            'status'      => 'paid',
            'paid_at'     => now(),
            'reviewed_by' => $admin->id,
            'reviewed_at' => now(),
            'admin_note'  => $note ?: null,
        ]);

        $order->user->forceFill(['is_pro' => true])->save();

        return true;
    });
}

    public function reject(User $admin, string $note): bool
    {
        return DB::transaction(function () use ($admin, $note) {
            $order = static::whereKey($this->id)->lockForUpdate()->first();

            if ($order->status !== 'awaiting_review') {
                return false;
            }

            $order->update([
                'status'      => 'rejected',
                'reviewed_by' => $admin->id,
                'reviewed_at' => now(),
                'admin_note'  => $note,
            ]);

            return true;
        });
    }
    
}