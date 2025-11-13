<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'customer_id',
        'service_id',
        'promotion_id',
        'user_id',
        'invoice',
        'vehicle_brand',
        'vehicle_plate',
        // === BAGIAN INI YANG PENTING AGAR TIDAK 0 ===
        'total',
        'base_price',
        'discount',
        'amount_paid',
        'change',
        // ============================================
        'status',
        'payment_method',
        'slot',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function promotion()
    {
        return $this->belongsTo(Promotion::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
