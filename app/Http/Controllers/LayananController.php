<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class layananController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $layanan = [
            [
                'id' => 1,
                'name' => 'Premium Car Wash',
                'description' => 'Cuci mobil lengkap dengan wax dan vacuum interior.',
                'price' => 150000
            ],
            [
                'id' => 2,
                'name' => 'Interior Detailing',
                'description' => 'Pembersihan mendalam untuk seluruh bagian interior mobil.',
                'price' => 350000
            ],
            [
                'id' => 3,
                'name' => 'Nano Ceramic Coating',
                'description' => 'Lapisan pelindung cat dengan daya tahan hingga 2 tahun.',
                'price' => 750000
            ]
        ];

        return view('admin.layanan.index', compact('layanan'));
    }

}
