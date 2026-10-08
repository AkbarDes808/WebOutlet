<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OutletController extends Controller
{
    public function index()
    {
        $outlets = collect([
            'Outlet 1' => 'Pusat',
            'Outlet 2' => 'Indomaret',
            'Outlet 3' => 'Bunderan',
            'Outlet 4' => 'Mersi',
            'Outlet 5' => 'Arca',
            'Outlet 6' => 'Larangan',
            'Outlet 7' => 'Unsoed',
        ]);

        return view('outlets.index', compact('outlets'));
    }
}
