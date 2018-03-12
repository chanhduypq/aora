<?php

use Illuminate\Database\Seeder;

class CountriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Country::truncate();

        \App\Country::create([
            'name' => 'USA'
        ]);
    }
}
