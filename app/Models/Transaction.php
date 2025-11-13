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
    // TAMBAHKAN KOLOM BARU KE FILLABLE
    protected $fillable = [
        'customer_id',
        'service_id',
        'promotion_id', // <-- BARU
        'transaction_code',
        'status',
        'payment_method', // <-- BARU
        'base_price', // <-- BARU
        'discount', // <-- BARU
        'total_price',
    ];

    /**
     * Relasi ke Customer
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Relasi ke Service
     */
    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    /**
     * =============================================
     * RELASI BARU KE PROMOTION
     * =============================================
     */
    public function promotion()
    {
        return $this->belongsTo(Promotion::class);
    }
}
