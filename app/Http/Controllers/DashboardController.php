<?php

namespace App\Http\Controllers;

use App\Models\Transaksi; // Jika model Transaksi ada
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Contoh: Mengambil jumlah transaksi dari model Transaksi
        $transaksi_count = Transaksi::count();

        return view('dashboard', ['transaksi_count' => $transaksi_count]);
    }
}
