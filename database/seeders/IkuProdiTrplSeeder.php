<?php

namespace Database\Seeders;

use App\Models\IkuProdiTrpl;
use Illuminate\Database\Seeder;

class IkuProdiTrplSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        IkuProdiTrpl::insert([
            [

                'title' => 'IKU Prodi TRPL 1',
                'content' => 'Konten untuk IKU Prodi TRPL 1',

            ],
            [

                'title' => 'IKU Prodi TRPL',
                'content' => 'Konten untuk IKU Prodi TRPL 2',
            ],
            // Tambahkan data lainnya sesuai kebutuhan
        ]);
    }
}
