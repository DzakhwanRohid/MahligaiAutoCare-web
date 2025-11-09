<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Service extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'description', 'price'];

    /**
     * Mendapatkan semua transaksi yang menggunakan layanan ini.
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }
}
