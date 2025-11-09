<?php

namespace App\Http\Controllers;

class DashboardController extends Controller
{
    public function admin()
    {
        return view('dashboards.admin');
    }
    
    public function kasir()
    {
        return view('dashboards.kasir');
    }
}
