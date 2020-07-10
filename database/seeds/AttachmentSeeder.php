<?php

use Illuminate\Database\Seeder;

class AttachmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $names = [
            'Biaya Prosiding', 'Biaya Seminar', 'Biaya Akomodasi',
            'Biaya Transportasi', 'Biaya Transportasi Lokal',
            'Undangan', 'Accepted Letter', 'Brosur / Leaflet / Link',
            'Draft Artikel Ilmiah / Naskah', 'Artikel Ilmiah / Naskah',
        ];

        foreach ($names as $name) {
            \App\Attachment::create(compact('name'));
        }
    }
}
