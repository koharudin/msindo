<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SalesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $salesData = [
            ['name' => 'Imam', 'phone' => '123123123', 'address' => 'Jakarta', 'invoice_number' => 'INV9876', 'product_id' => '1', 'month' => '07', 'year' => '2023', 'qty' => 1800, 'sell_date' => Carbon::create('2023', '07', '01')->format('Y-m-d'), 'total' => 1800 * 50000],
            ['name' => 'Ahmad', 'phone' => '234234234', 'address' => 'Bandung', 'invoice_number' => 'INV9877', 'product_id' => '1', 'month' => '08', 'year' => '2023', 'qty' => 1700, 'sell_date' => Carbon::create('2023', '08', '01')->format('Y-m-d'), 'total' => 1700 * 50000],
            ['name' => 'Budi', 'phone' => '345345345', 'address' => 'Surabaya', 'invoice_number' => 'INV9878', 'product_id' => '1', 'month' => '09', 'year' => '2023', 'qty' => 1900, 'sell_date' => Carbon::create('2023', '09', '01')->format('Y-m-d'), 'total' => 1900 * 50000],
            ['name' => 'Joko', 'phone' => '456456456', 'address' => 'Medan', 'invoice_number' => 'INV9879', 'product_id' => '1', 'month' => '10', 'year' => '2023', 'qty' => 1800, 'sell_date' => Carbon::create('2023', '10', '01')->format('Y-m-d'), 'total' => 1800 * 50000],
            ['name' => 'Siti', 'phone' => '567567567', 'address' => 'Makassar', 'invoice_number' => 'INV9880', 'product_id' => '1', 'month' => '11', 'year' => '2023', 'qty' => 1500, 'sell_date' => Carbon::create('2023', '11', '01')->format('Y-m-d'), 'total' => 1500 * 50000],
            ['name' => 'Rina', 'phone' => '678678678', 'address' => 'Yogyakarta', 'invoice_number' => 'INV9881', 'product_id' => '1', 'month' => '12', 'year' => '2023', 'qty' => 1500, 'sell_date' => Carbon::create('2023', '12', '01')->format('Y-m-d'), 'total' => 1500 * 50000],
            ['name' => 'Dani', 'phone' => '789789789', 'address' => 'Semarang', 'invoice_number' => 'INV9882', 'product_id' => '1', 'month' => '01', 'year' => '2024', 'qty' => 1400, 'sell_date' => Carbon::create('2024', '01', '01')->format('Y-m-d'), 'total' => 1400 * 50000],
            ['name' => 'Lina', 'phone' => '890890890', 'address' => 'Palembang', 'invoice_number' => 'INV9883', 'product_id' => '1', 'month' => '02', 'year' => '2024', 'qty' => 1100, 'sell_date' => Carbon::create('2024', '02', '01')->format('Y-m-d'), 'total' => 1100 * 50000],
            ['name' => 'Eko', 'phone' => '901901901', 'address' => 'Pontianak', 'invoice_number' => 'INV9884', 'product_id' => '1', 'month' => '03', 'year' => '2024', 'qty' => 1400, 'sell_date' => Carbon::create('2024', '03', '01')->format('Y-m-d'), 'total' => 1400 * 50000],
            ['name' => 'Nina', 'phone' => '012012012', 'address' => 'Banjarmasin', 'invoice_number' => 'INV9885', 'product_id' => '1', 'month' => '04', 'year' => '2024', 'qty' => 1300, 'sell_date' => Carbon::create('2024', '04', '01')->format('Y-m-d'), 'total' => 1300 * 50000],
            ['name' => 'Rudi', 'phone' => '123321123', 'address' => 'Cirebon', 'invoice_number' => 'INV9886', 'product_id' => '1', 'month' => '05', 'year' => '2024', 'qty' => 1700, 'sell_date' => Carbon::create('2024', '05', '01')->format('Y-m-d'), 'total' => 1700 * 50000],
            ['name' => 'Tari', 'phone' => '234432234', 'address' => 'Jambi', 'invoice_number' => 'INV9887', 'product_id' => '1', 'month' => '06', 'year' => '2024', 'qty' => 1100, 'sell_date' => Carbon::create('2024', '06', '01')->format('Y-m-d'), 'total' => 1100 * 50000],
        ];

        // Insert data into the sales table
        DB::table('sales')->insert($salesData);
    }
}
