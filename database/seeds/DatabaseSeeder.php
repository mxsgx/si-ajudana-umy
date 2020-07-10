<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            ActivitySeeder::class,
            AttachmentSeeder::class,
            FinancialSeeder::class,
            FacultySeeder::class,
            StudySeeder::class,
            CategorySeeder::class,
            ParticipantSeeder::class,
            UserSeeder::class,
            LecturerSeeder::class,
            SubmissionSeeder::class,
        ]);
    }
}
