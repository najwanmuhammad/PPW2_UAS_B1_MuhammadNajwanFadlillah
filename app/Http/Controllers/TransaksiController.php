<?php

namespace App\Http\Controllers;

use App\Models\TransaksiDetail;
use Illuminate\Http\Request;

use App\Models\Transaksi;
use Illuminate\Support\Facades\DB;

class TransaksiController extends Controller
{
    public function index()
    {
        $transaksi = Transaksi::orderBy('tanggal_pembelian', 'DESC')->get();
        return view('transaksi.index', compact('transaksi'));
    }

    public function create()
    {
        return view('transaksi.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal_pembelian' => 'required|date',
            'bayar' => 'required|numeric',
            'nama_produk.*' => 'required|string',
            'harga_satuan.*' => 'required|numeric',
            'jumlah.*' => 'required|numeric',
        ]);

        DB::beginTransaction();
        try {
            // Simpan transaksi
            $transaksi = new Transaksi();
            $transaksi->tanggal_pembelian = $request->input('tanggal_pembelian');
            $transaksi->total_harga = 0; // Akan diupdate setelah perhitungan subtotal
            $transaksi->bayar = $request->input('bayar');
            $transaksi->kembalian = 0; // Akan diupdate setelah perhitungan
            $transaksi->save();

            // Simpan detail transaksi
            $total_harga = 0;
            foreach ($request->input('nama_produk') as $index => $nama_produk) {
                $transaksiDetail = new TransaksiDetail();
                $transaksiDetail->id_transaksi = $transaksi->id;
                $transaksiDetail->nama_produk = $nama_produk;
                $transaksiDetail->harga_satuan = $request->input('harga_satuan')[$index];
                $transaksiDetail->jumlah = $request->input('jumlah')[$index];
                $transaksiDetail->subtotal = $transaksiDetail->harga_satuan * $transaksiDetail->jumlah;
                $transaksiDetail->save();

                $total_harga += $transaksiDetail->subtotal;
            }

            // Update total harga dan kembalian
            $transaksi->total_harga = $total_harga;
            $transaksi->kembalian = $request->input('bayar') - $total_harga;
            $transaksi->save();

            DB::commit();
            return redirect('/transaksi')->with('pesan', 'Berhasil menambahkan data');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->withErrors(['Transaction' => 'Gagal menambahkan data'])->withInput();
        }
    }

    public function edit($id)
    {
        $transaksi = Transaksi::findOrFail($id);
        return view('transaksi.edit', compact('transaksi'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'bayar' => 'required|numeric'
        ]);

        $transaksi = Transaksi::findOrFail($id);
        $transaksi->bayar = $request->input('bayar');
        $transaksi->kembalian = $transaksi->bayar - $transaksi->total_harga;
        $transaksi->save();

        return redirect('/transaksi')->with('pesan', 'Berhasil mengubah data');
    }


    public function destroy($id)
    {
        $transaksi = Transaksi::findOrFail($id);
        $transaksi->delete();

        return redirect('/transaksi')->with('pesan', 'Berhasil menghapus data');
    }
}
