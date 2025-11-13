<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'email',
        'license_plate',
        'vehicle_type',
        'user_id',
    ];

    /**
     * Relasi ke tabel User.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke tabel Transaction.
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
