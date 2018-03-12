<?php

use Illuminate\Database\Seeder;

class MarketplacesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Marketplace::truncate();

        \App\Marketplace::create([
            'country_id' => 1,
            'name' => 'Amazon'
        ]);
    }
}
