<?php

use Illuminate\Database\Seeder;

class ActivitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $names = [
            'Seminar Internasional', 'Seminar Nasional', 'Penulisan Buku',
            'Pengajuan Paten', 'Publikasi Jurnal Nasional', 'Publikasi Jurnal Internasional',
            'Publikasi Jurnal Scopus', 'Pengabdian Kepada Masyarakat', 'Penilitian',
            'Workshop / Pelatihan', 'Pengajuan HAKI',
        ];

        foreach ($names as $name) {
            \App\Activity::create(compact('name'));
        }
    }
}
