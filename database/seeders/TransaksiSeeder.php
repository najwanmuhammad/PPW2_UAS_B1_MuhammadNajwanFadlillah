<?php

namespace Database\Seeders;

use App\Models\Transaksi;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class TransaksiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        // Tentukan rentang tanggal untuk seeder
        $startDate = Carbon::create(2024, 11, 1); // Mulai dari 1 November 2024
        $endDate = Carbon::create(2024, 11, 10); // Hingga 10 November 2024

        // Iterasi untuk setiap hari dalam rentang tanggal
        for ($date = $startDate; $date->lte($endDate); $date->addDay()) {
            // Tentukan jumlah transaksi acak antara 15 dan 20
            $numberOfTransactions = $faker->numberBetween(15, 20);

            for ($i = 0; $i < $numberOfTransactions; $i++) {
                // Generate nilai acak untuk transaksi
                $total_harga = $faker->numberBetween(50000, 500000); // Total harga antara 50.000 dan 500.000
                $bayar = $faker->numberBetween($total_harga, $total_harga + 100000); // Bayar >= total_harga
                $kembalian = $bayar - $total_harga; // Hitung kembalian

                // Simpan data transaksi
                Transaksi::create([
                    'tanggal_pembelian' => $date->format('Y-m-d'),
                    'total_harga' => $total_harga,
                    'bayar' => $bayar,
                    'kembalian' => $kembalian,
                ]);
            }
        }
    }
}
