<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('products')->insert([
            'name' => 'lampuSS22',
            'year' => date('Y'),
            'month' => date('m'),
            'stock' => rand(10, 100),
            'price' => rand(50000, 150000),
            'buy_date' => Carbon::now()->subDays(rand(1, 365)),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
