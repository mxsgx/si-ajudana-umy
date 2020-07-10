<?php

use Illuminate\Database\Seeder;

class LecturerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function run()
    {
        $filePath = storage_path('backup/db.json');

        if (File::exists($filePath)) {
            $file = File::get($filePath);
            $json = json_decode($file, true);
            $collection = collect($json);
            $collection->where('name', 'lecturers')->first(function ($result) {
                foreach ($result['data'] as $data) {
                    $lecturer = \App\Lecturer::create([
                        'name' => $data['name'],
                        'nip' => $data['nip'],
                        'nik' => $data['nik'],
                        'birth_date' => $data['birth_date'],
                        'birth_place' => $data['birth_place'] === 'Dummy' ? '' : $data['birth_place'],
                        'address' => $data['address'] === 'Dummy' ? '' : $data['address'],
                        'study_id' => $data['study_program_id'],
                    ]);

                    \App\User::create([
                        'name' => $data['name'],
                        'email' => $data['email'],
                        'password' => Hash::make('dosen'),
                        'role' => 'lecturer',
                        'lecturer_id' => $lecturer->id,
                    ]);
                }
            });
        }
    }
}
