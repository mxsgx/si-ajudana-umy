<?php

use Illuminate\Database\Seeder;

class StudySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $names = [
            'Teknik Mesin', 'Teknik Sipil', 'Teknik Elektro',
            'Teknologi Informasi',
        ];
        $faculty_id = \App\Faculty::where('name', '=' , 'Fakultas Teknik')->first()->id;

        foreach ($names as $name) {
            \App\Study::create(compact(['name', 'faculty_id']));
        }
    }
}
