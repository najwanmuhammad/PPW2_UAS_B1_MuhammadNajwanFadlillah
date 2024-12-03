<?php

namespace App\Http\Controllers;

use App\Models\Transaksi; // Jika model Transaksi ada
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $transaksi_count = Transaksi::count();
        $item_terjual = Transaksi::sum('jumlah_item'); // Misalnya jumlah item dijual
        $omzet = Transaksi::sum('total'); // Misalnya total omzet

        return view('dashboard', compact('transaksi_count', 'item_terjual', 'omzet'));
    }

}
