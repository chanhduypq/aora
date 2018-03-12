<?php

use Illuminate\Database\Seeder;

class ShippingMethodsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\ShippingMethod::truncate();

        \App\ShippingMethod::create([
            'country_id' => 1,
            'name' => 'Standard',
            'base_charge' => '13.40',
            'weight_charge' => '0.72',
            'fuel_surcharge' => '0.19',
            'duration' => '4 - 6',
        ]);
        \App\ShippingMethod::create([
            'country_id' => 1,
            'name' => 'Economy',
            'base_charge' => '13.40',
            'weight_charge' => '0.72',
            'fuel_surcharge' => '0.19',
            'duration' => '9 - 12',
        ]);
        \App\ShippingMethod::create([
            'country_id' => 1,
            'name' => 'Sea',
            'base_charge' => '13.90',
            'weight_charge' => '0.39',
            'fuel_surcharge' => '0.0',
            'duration' => '35 - 60',
        ]);
    }
}
