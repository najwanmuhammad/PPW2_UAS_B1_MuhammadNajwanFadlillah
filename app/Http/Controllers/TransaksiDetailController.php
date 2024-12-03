<?php

namespace App\Http\Controllers;

use App\Models\TransaksiDetail;
use Illuminate\Http\Request;

use App\Models\Transaksi;
use Illuminate\Support\Facades\DB;

class TransaksiDetailController extends Controller
{

    public function index()
    {
        $transaksidetail = TransaksiDetail::with('transaksi')->orderBy('id', 'DESC')->get();
        return view('transaksidetail.index', compact('transaksidetail'));
    }

    public function detail(Request $request)
    {
        $transaksi = Transaksi::with('transaksidetail')->findOrFail($request->id_transaksi);
        return view('transaksidetail.detail', compact('transaksi'));
    }

    public function edit($id)
    {
        $transaksidetail = TransaksiDetail::findOrFail($id);
        return view('transaksidetail.edit', compact('transaksidetail'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_produk' => 'required|string',
            'harga_satuan' => 'required|numeric',
            'jumlah' => 'required|numeric',
        ]);

        DB::beginTransaction();
        try {
            $transaksidetail = TransaksiDetail::findOrFail($id);

            // Update detail transaksi
            $transaksidetail->nama_produk = $request->input('nama_produk');
            $transaksidetail->harga_satuan = $request->input('harga_satuan');
            $transaksidetail->jumlah = $request->input('jumlah');
            $transaksidetail->subtotal = $transaksidetail->harga_satuan * $transaksidetail->jumlah;
            $transaksidetail->save();

            // Update total_harga dan kembalian pada transaksi
            $transaksi = Transaksi::with('transaksidetail')->findOrFail($transaksidetail->id_transaksi);
            $total_harga = $transaksi->transaksidetail->sum('subtotal');
            $transaksi->total_harga = $total_harga;
            $transaksi->kembalian = $transaksi->bayar - $total_harga;
            $transaksi->save();

            DB::commit();
            return redirect('transaksidetail/' . $transaksidetail->id_transaksi)->with('pesan', 'Berhasil mengubah data');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->withErrors(['Transaction' => 'Gagal mengubah data'])->withInput();
        }
    }

    public function destroy()
    {
        $transaksidetail = TransaksiDetail::findOrFail($id);

        $transaksi = Transaksi::with('transaksidetail')->findOrFail($transaksidetail->id_transaksi);
        $transaksi->total_harga = sum subtotal;
        $transaksi->kembalian = bayar - total_harga;
        $transaksi->save();

        return redirect('transaksidetail/'.$transaksidetail->id_transaksi)->with('pesan', 'Berhasil menghapus data');
    }
}
