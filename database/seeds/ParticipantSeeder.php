<?php

use Illuminate\Database\Seeder;

class ParticipantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $filePath = storage_path('backup/db.json');

        if (File::exists($filePath)) {
            $file = File::get($filePath);
            $json = json_decode($file, true);
            $collection = collect($json);
            $collection->where('name', 'participants')->first(function ($result) {
                foreach ($result['data'] as $data) {
                    \App\Participant::create($data);
                }
            });
        }
    }
}
