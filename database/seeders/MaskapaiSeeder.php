<?php

namespace Database\Seeders;

use App\Models\Maskapai;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;


class MaskapaiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Maskapai::insert([
            [
                'nama_maskapai' => 'Garuda Indonesia',
                'logo' => '/images/garuda.png'
            ],
            [
                'nama_maskapai' => 'Lion Air',
                'logo' => '/images/lion.png'
            ],
            [
                'nama_maskapai' => 'AirAsia',
                'logo' => '/images/airasia.png'
            ],
            [
                'nama_maskapai' => 'Citilink',
                'logo' => '/images/citilink.png'
            ],
        ]);
    }
}
