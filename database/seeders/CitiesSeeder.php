<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CitiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cities = [

        ];

        $cities = collect($cities)->map(function($city) {
            return [
                'name' => $city,
                'created_at' => now(),
                'updated_at' => now()
            ];
        })->toArray();

        DB::table('cities')->insert($cities);
    }
}
