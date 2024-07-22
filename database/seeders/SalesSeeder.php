<?php

namespace Database\Seeders;

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
        $months = [
            '01', '02', '03', '04', '05', '06', 
            '07', '08', '09', '10', '11', '12'
        ];

        foreach ($months as $month) {
            DB::table('sales')->insert([
                'name' => 'John Doe',
                'phone' => '123456789',
                'address' => '123 Main St',
                'invoice_number' => 'INV123456',
                'product_id' => 1,
                'qty' => rand(1, 100),
                'total' => rand(1000, 5000),
                'sell_date' => now(),
                'year' => date('Y'),
                'month' => $month,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
