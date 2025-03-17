<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DepartmentsSeeder extends Seeder
{
    public function run()
    {
        $departments = [
            ['id' => 1, 'name' => 'Doctor'],
            ['id' => 2, 'name' => 'Nurse'],
            ['id' => 3, 'name' => 'Receptionist'],
            ['id' => 4, 'name' => 'Lab Technician'],
            ['id' => 5, 'name' => 'Pharmacist'],
        ];

        DB::table('departments')->insert($departments);
    }
}
