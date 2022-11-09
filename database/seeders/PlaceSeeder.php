<?php

namespace Database\Seeders;

use App\Models\Place;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PlaceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $places = [
            ['name' => 'Душанбе'],
            ['name' => 'Худжанд'],
        ];

        foreach ($places as $place) {
            Place::create($place);
        }
    }
}
