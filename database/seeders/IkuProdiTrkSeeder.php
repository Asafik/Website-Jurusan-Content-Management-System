<?php

namespace Database\Seeders;

use App\Models\IkuProdiTrk;
use Illuminate\Database\Seeder;

class IkuProdiTrkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        IkuProdiTrk::insert([
            [

                'title' => 'IKU Prodi TRK 1',
                'content' => 'Konten untuk IKU Prodi TRK 1',

            ],
            [

                'title' => 'IKU Prodi TRK 1',
                'content' => 'Konten untuk IKU Prodi TRK 2',
            ],
            // Tambahkan data lainnya sesuai kebutuhan
        ]);
    }
}
