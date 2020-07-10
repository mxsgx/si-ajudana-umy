<?php

use Illuminate\Database\Seeder;

class FinancialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $names = [
            'Pendaftaran Seminar', 'Biaya Akomodasi', 'Biaya Transportasi Lokal',
            'Biaya Transportasi', 'Uang Saku', 'Uang Harian', 'Biaya Prosiding/Publikasi Jurnal',
            'Proofreading', 'Visa',
        ];

        foreach ($names as $name) {
            \App\Financial::create(compact('name'));
        }
    }
}
