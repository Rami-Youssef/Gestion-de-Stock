<?php

namespace Database\Seeders;

use App\Models\MouvementStock;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MouvementStockSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        MouvementStock::factory(5)->create();
    }
}
