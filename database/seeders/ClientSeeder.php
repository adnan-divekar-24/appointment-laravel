<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClientSeeder extends Seeder
{
    public function run()
    {
        DB::table('clients')->insert([
            ['name' => 'John Doe', 'email' => 'john@example.com', 'phone' => '9876543210'],
            ['name' => 'Jane Smith', 'email' => 'jane@example.com', 'phone' => '8765432109'],
        ]);
    }
}

