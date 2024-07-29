<?php

namespace Database\Seeders;

use App\Models\IkuJurusan;
use Illuminate\Database\Seeder;

class IkuJurusanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        IkuJurusan::insert([
            [

                'title' => 'IKU Jurusan 1',
                'content' => 'Konten untuk IKU Jurusan 1',

            ],
            [

                'title' => 'IKU Jurusan 2',
                'content' => 'Konten untuk IKU Jurusan 2',
            ],
            // Tambahkan data lainnya sesuai kebutuhan
        ]);
    }
}
