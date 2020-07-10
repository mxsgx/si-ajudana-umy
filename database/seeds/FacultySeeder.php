<?php

use Illuminate\Database\Seeder;

class FacultySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $names = [
            'Fakultas Teknik',
        ];

        foreach ($names as $name) {
            \App\Faculty::create(compact('name'));
        }
    }
}
