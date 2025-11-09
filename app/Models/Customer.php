<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

// TAMBAHKAN BARIS INI
use App\Models\Transaction;

class Customer extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'phone'];

    /**
     * Mendapatkan semua transaksi yang dimiliki oleh customer ini.
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }
}