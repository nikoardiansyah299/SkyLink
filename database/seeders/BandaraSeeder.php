<?php

namespace Database\Seeders;

use App\Models\Bandara;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BandaraSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Bandara::insert([
            [
                'nama_bandara' => 'Soekarno-Hatta International Airport',
                'kota' => 'Jakarta',
                'kode_iata' => 'CGK',
                'negara' => 'Indonesia'
            ],
            [
                'nama_bandara' => 'Ngurah Rai International Airport',
                'kota' => 'Denpasar',
                'kode_iata' => 'DPS',
                'negara' => 'Indonesia'
            ],
            [
                'nama_bandara' => 'Kuala Lumpur International Airport',
                'kota' => 'Kuala Lumpur',
                'kode_iata' => 'KUL',
                'negara' => 'Malaysia'
            ],
            [
                'nama_bandara' => 'Changi Airport',
                'kota' => 'Singapore',
                'kode_iata' => 'SIN',
                'negara' => 'Singapore'
            ],
        ]);
    }
}
