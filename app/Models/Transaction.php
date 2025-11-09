<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

// PASTIKAN TIGA BARIS DI BAWAH INI ADA DAN BENAR
use App\Models\Customer;
use App\Models\Service;
use App\Models\User;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice', 'customer_id', 'service_id', 'user_id',
        'vehicle_brand', 'vehicle_plate', 'total', 'status',
        'payment_method', 'amount_paid', 'change'
    ];

    /**
     * Mendapatkan data customer yang terkait dengan transaksi ini.
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Mendapatkan data layanan yang terkait dengan transaksi ini.
     */
    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    /**
     * Mendapatkan data kasir (user) yang terkait dengan transaksi ini.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}