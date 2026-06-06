<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        'delivery_proof_base64',
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

    public function getDeliveryProofUrlAttribute()
    {
        // If Base64 image exists, use it directly.
        // This works even on Render because it does not need file storage.
        if (! empty($this->delivery_proof_base64)) {
            return $this->delivery_proof_base64;
        }

        // If no file path, no proof.
        if (empty($this->delivery_proof_path)) {
            return null;
        }

        // If saved inside public/delivery_proofs
        if (str_starts_with($this->delivery_proof_path, 'delivery_proofs/')) {
            return asset($this->delivery_proof_path);
        }

        // If saved inside storage/app/public/delivery-proofs
        if (str_starts_with($this->delivery_proof_path, 'delivery-proofs/')) {
            return asset('storage/' . $this->delivery_proof_path);
        }

        // Default fallback
        return asset($this->delivery_proof_path);
    }

    public function getHasDeliveryProofAttribute()
    {
        return ! empty($this->delivery_proof_base64) || ! empty($this->delivery_proof_path);
    }
}