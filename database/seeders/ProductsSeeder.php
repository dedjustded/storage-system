<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $products = [
            [
                'name' => 'PlayStation 5',
                'unit_price' => 500,
                'stock' => 20,
            ],
            [
                'name' => 'iPhone 11',
                'unit_price' => 700,
                'stock' => 30,
            ],
            [
                'name' => 'Nvidia RTX 3080',
                'unit_price' => 1000,
                'stock' => 10,
            ],
        ];

        DB::table('products')->insert($products);
    }
}