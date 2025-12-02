<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use App\Models\Bandara;
use App\Models\Maskapai;
use App\Models\Penerbangan;
use Carbon\Carbon;

class FeaturedFlightsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $origin = Bandara::firstWhere('kota', 'Jakarta');
        if (! $origin) {
            $origin = Bandara::create([
                'nama_bandara' => 'Soekarno-Hatta International Airport',
                'kota' => 'Jakarta',
                'kode_iata' => 'CGK',
                'negara' => 'Indonesia',
            ]);
        }

        $featured = [
            ['kota' => 'Santorini', 'kode' => 'JTR', 'nama' => 'Santorini (Thira) Airport', 'negara' => 'Greece'],
            ['kota' => 'Denpasar', 'kode' => 'DPS', 'nama' => 'Ngurah Rai International Airport', 'negara' => 'Indonesia'],
            ['kota' => 'Paris', 'kode' => 'CDG', 'nama' => 'Charles de Gaulle Airport', 'negara' => 'France'],
            ['kota' => 'Honolulu', 'kode' => 'HNL', 'nama' => 'Daniel K. Inouye International Airport', 'negara' => 'USA'],
            ['kota' => 'Sydney', 'kode' => 'SYD', 'nama' => 'Sydney Kingsford Smith Airport', 'negara' => 'Australia'],
            ['kota' => 'Tokyo', 'kode' => 'HND', 'nama' => 'Tokyo Haneda Airport', 'negara' => 'Japan'],
            ['kota' => 'London', 'kode' => 'LHR', 'nama' => 'Heathrow Airport', 'negara' => 'United Kingdom'],
            ['kota' => 'New York', 'kode' => 'JFK', 'nama' => 'John F. Kennedy International Airport', 'negara' => 'USA'],
            ['kota' => 'Dubai', 'kode' => 'DXB', 'nama' => 'Dubai International Airport', 'negara' => 'UAE'],
            ['kota' => 'Seoul', 'kode' => 'ICN', 'nama' => 'Incheon International Airport', 'negara' => 'South Korea'],
            ['kota' => 'Bangkok', 'kode' => 'BKK', 'nama' => 'Suvarnabhumi Airport', 'negara' => 'Thailand'],
            ['kota' => 'Kuala Lumpur', 'kode' => 'KUL', 'nama' => 'Kuala Lumpur International Airport', 'negara' => 'Malaysia'],
            ['kota' => 'Singapore', 'kode' => 'SIN', 'nama' => 'Changi Airport', 'negara' => 'Singapore'],
        ];

        // collect maskapais (create a default if none)
        $maskapais = Maskapai::all();
        if ($maskapais->isEmpty()) {
            $maskapais = collect([Maskapai::create(['nama_maskapai' => 'Featured Air', 'logo' => '/images/default-plane.png'])]);
        }

        foreach ($featured as $dest) {
            $bandara = Bandara::firstWhere('kota', $dest['kota']);
            if (! $bandara) {
                $bandara = Bandara::create([
                    'nama_bandara' => $dest['nama'],
                    'kota' => $dest['kota'],
                    'kode_iata' => $dest['kode'],
                    'negara' => $dest['negara'],
                ]);
            }

            // Create two travel dates: one next week, one in three weeks
            $dates = [Carbon::now()->addWeek()->startOfDay()->addHours(8), Carbon::now()->addWeeks(3)->startOfDay()->addHours(8)];

            foreach ($maskapais as $maskapai) {
                foreach ($dates as $d) {
                    $pData = [
                        'harga' => rand(500000, 5000000),
                        'id_bandara_asal' => $origin->id,
                        'id_bandara_tujuan' => $bandara->id,
                        'tanggal' => $d->format('Y-m-d'),
                        'jam_berangkat' => $d->copy()->addHours(2)->format('H:i'),
                        'jam_tiba' => $d->copy()->addHours(6)->format('H:i'),
                    ];

                    if (Schema::hasColumn('penerbangan', 'maskapai_id')) {
                        $pData['maskapai_id'] = $maskapai->id;

                        // avoid duplicate identical flight for same date + maskapai
                        $existsQuery = Penerbangan::where('id_bandara_asal', $origin->id)
                                    ->where('id_bandara_tujuan', $bandara->id)
                                    ->where('tanggal', $pData['tanggal'])
                                    ->where('maskapai_id', $maskapai->id);
                    } else {
                        $pData['nama_maskapai'] = $maskapai->nama_maskapai;
                        $pData['gambar'] = $maskapai->logo ?? '/images/default-plane.png';

                        // avoid duplicate identical flight for same date + nama_maskapai
                        $existsQuery = Penerbangan::where('id_bandara_asal', $origin->id)
                                    ->where('id_bandara_tujuan', $bandara->id)
                                    ->where('tanggal', $pData['tanggal'])
                                    ->where('nama_maskapai', $maskapai->nama_maskapai);
                    }

                    if (! $existsQuery->exists()) {
                        Penerbangan::create($pData);
                    }
                }
            }
        }
    }
}
