<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Hitung jumlah transaksi
        $transaksi_count = Transaksi::count();

        // Hitung jumlah item terjual (dari tabel transaksi_detail)
        $item_terjual = \DB::table('transaksi_detail')->sum('jumlah');

        // Hitung omzet (dari tabel transaksi, kolom total_harga)
        $omzet = Transaksi::sum('total_harga');

        // Kirim data ke view
        return view('dashboard', compact('transaksi_count', 'item_terjual', 'omzet'));
    }
}
