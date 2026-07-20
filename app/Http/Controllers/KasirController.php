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
            ->orderBy('name')
            ->get();

        return view('kasir.index', compact('menus', 'shiftClosed'));
    }
}