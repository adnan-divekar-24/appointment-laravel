<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubDepartmentsSeeder extends Seeder
{
    public function run()
    {
        $subDepartments = [
            // Doctor's Specialties
            ['department_id' => 1, 'name' => 'Skin Care Specialist'],
            ['department_id' => 1, 'name' => 'Cardiologist'],
            ['department_id' => 1, 'name' => 'Orthopedic Surgeon'],

            // Nurse Specialties
            ['department_id' => 2, 'name' => 'ICU Nurse'],
            ['department_id' => 2, 'name' => 'Pediatric Nurse'],
            ['department_id' => 2, 'name' => 'Surgical Nurse'],

            // Receptionist Specialties
            ['department_id' => 3, 'name' => 'Front Desk'],
            ['department_id' => 3, 'name' => 'Billing Assistant'],
            ['department_id' => 3, 'name' => 'Appointment Coordinator'],

            // Lab Technician Specialties
            ['department_id' => 4, 'name' => 'Blood Testing'],
            ['department_id' => 4, 'name' => 'Microbiology'],
            ['department_id' => 4, 'name' => 'Radiology'],

            // Pharmacist Specialties
            ['department_id' => 5, 'name' => 'Retail Pharmacist'],
            ['department_id' => 5, 'name' => 'Clinical Pharmacist'],
            ['department_id' => 5, 'name' => 'Hospital Pharmacist'],
        ];

        DB::table('sub_departments')->insert($subDepartments);
    }
}
