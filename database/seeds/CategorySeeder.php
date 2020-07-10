<?php

use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $names = [
            'Usulan Anggaran Kegiatan', 'Pengajuan Reimburse/Penggantian',
        ];

        foreach ($names as $name) {
            \App\Category::create(compact('name'));
        }
    }
}
