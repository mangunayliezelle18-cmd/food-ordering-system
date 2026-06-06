<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Order extends Model
{
    use HasFactory;

    public const STATUSES = [
        'pending',
        'approved',
        'preparing',
        'out_for_delivery',
        'delivered',
        'rejected',
        'cancelled',
    ];

    protected $fillable = [
        'user_id',
        'rider_id',
        'total_amount',
        'status',
        'delivery_address',
        'contact_number',
        'notes',
        'delivery_proof_path',
        'delivered_at',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'delivered_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function rider()
    {
        return $this->belongsTo(User::class, 'rider_id');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getDeliveryProofUrlAttribute(): ?string
    {
        if (! $this->delivery_proof_path) {
            return null;
        }

        if (str_starts_with($this->delivery_proof_path, 'delivery_proofs/')) {
            return asset($this->delivery_proof_path);
        }

        if (str_starts_with($this->delivery_proof_path, 'delivery-proofs/')) {
            return asset('storage/' . $this->delivery_proof_path);
        }

        return Storage::disk('public')->url($this->delivery_proof_path);
    }
}
