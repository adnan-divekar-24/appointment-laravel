<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ServiceSeeder extends Seeder
{
    public function run()
    {
        $services = [
            ['name' => 'Haircut'],
            ['name' => 'Shave'],
            ['name' => 'Facial'],
            ['name' => 'Manicure'],
            ['name' => 'Pedicure'],
            ['name' => 'Massage'],
            ['name' => 'Hair Coloring'],
            ['name' => 'Makeup'],
            ['name' => 'Eyebrow Threading'],
            ['name' => 'Beard Styling'],
        ];

        DB::table('services')->insert($services);
    }
}
