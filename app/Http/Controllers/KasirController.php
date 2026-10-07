<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\ShiftClosing;

class KasirController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if (!$user) {
            return redirect('/login');
        }

        $shiftClosed = ShiftClosing::where('user_id', $user->id)
            ->whereDate('tanggal', now()->toDateString())
            ->exists();

        $menus = Menu::where('is_active', true)
            ->orderByRaw("
                CASE
                    WHEN category = 'Menu Utama' THEN 1
                    WHEN category = 'Menu Tambahan' THEN 2
                    WHEN category = 'Menu Gratis' THEN 3
                    ELSE 4
                END
            ")
            ->orderBy('name')
            ->orderBy('price')
            ->get();

        return view('kasir.index', compact(
            'menus',
            'shiftClosed'
        ));
    }
}