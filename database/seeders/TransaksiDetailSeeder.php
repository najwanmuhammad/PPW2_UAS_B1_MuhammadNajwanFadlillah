<?php

namespace Database\Seeders;

use App\Models\Transaksi;
use App\Models\TransaksiDetail;
use Bezhanov\Faker\Provider\Commerce;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class TransaksiDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        $faker->addProvider(new Commerce($faker));

        $transaksi = Transaksi::all();

        foreach ($transaksi as $t) {
            // Tentukan jumlah detail produk acak antara 5 hingga 15
            $numberOfDetails = $faker->numberBetween(5, 15);
            $total_harga = 0;

            for ($j = 0; $j < $numberOfDetails; $j++) {
                $hargaSatuan = $faker->numberBetween(10, 500) * 100; // Harga satuan antara 1.000 hingga 50.000
                $jumlah = $faker->numberBetween(1, 5); // Jumlah produk per transaksi
                $subtotal = $hargaSatuan * $jumlah; // Menghitung subtotal
                $total_harga += $subtotal; // Menambahkan subtotal ke total_harga

                // Simpan detail transaksi
                TransaksiDetail::create([
                    'id_transaksi' => $t->id,
                    'nama_produk' => $faker->productName,
                    'harga_satuan' => $hargaSatuan,
                    'jumlah' => $jumlah,
                    'subtotal' => $subtotal, // Menggunakan subtotal
                ]);
            }

            // Update total harga transaksi, bayar dan kembalian
            $t->total_harga = $total_harga;
            $t->bayar = ceil($total_harga / 50000) * 50000; // Bayar adalah kelipatan 50.000
            $t->kembalian = $t->bayar - $total_harga; // Menghitung kembalian
            $t->save(); // Simpan perubahan pada transaksi
        }
    }
}
