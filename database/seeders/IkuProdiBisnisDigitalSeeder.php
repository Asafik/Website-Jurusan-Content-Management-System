<?php

namespace Database\Seeders;

use App\Models\IkuProdiBisnisDigital;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class IkuProdiBisnisDigitalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        IkuProdiBisnisDigital::insert([
            [

                'title' => 'IKU Prodi Bisnis Digital 1',
                'content' => 'Konten untuk IKU Prodi Bisnis Digital 1',

            ],
            [

                'title' => 'IKU Prodi Bisnis Digital 1',
                'content' => 'Konten untuk IKU Prodi Bisnis Digital 2',
            ],
            // Tambahkan data lainnya sesuai kebutuhan
        ]);
    }
}
