<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'key',  // Nama pengaturannya (misal: 'business_name')
        'value', // Nilai pengaturannya (misal: 'Mahligai Auto Care')
    ];

    // Kita tidak pakai timestamps (created_at/updated_at) untuk tabel ini
    public $timestamps = false;
}
