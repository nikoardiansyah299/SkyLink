<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Penerbangan;
use App\Models\Tiket;
use App\Models\Pemesanan;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TiketController extends Controller
{
    // =============================
    // HALAMAN FORM PESAN TIKET
    // =============================
    public function create($id_penerbangan)
    {
        $flight = Penerbangan::with(['bandaraAsal', 'bandaraTujuan', 'maskapai'])
                    ->findOrFail($id_penerbangan);

        $takenSeats = Tiket::where('id_penerbangan', $id_penerbangan)
                        ->pluck('seat')
                        ->toArray();

        return view('travels.pesan', compact('flight', 'takenSeats'));
    }

    // =============================
    // GENERATE KODE UNIK
    // =============================
    private function generateUniqueCode()
    {
        do {
            $kode = strtoupper(Str::random(6));
        } while (Pemesanan::where('kode', $kode)->exists());

        return $kode;
    }

    // =============================
    // SIMPAN TIKET
    // =============================
    public function store(Request $r)
    {
        $rules = [
            'id_penerbangan'       => 'required|exists:penerbangan,id',
            'nama_penumpang.*'     => 'required|string',
            // NIK is an Indonesian national ID (16 digits). Validate as a 16-character string
            // to allow leading zeros and preserve formatting. We validate length here,
            // and store as string in the DB after running the migration.
            'nik.*'                => 'required|string|size:16',
            // The booking (pemesanan) requires a class selection. Always validate kelas
            // so we can persist `tipe_kelas` on the pemesanan record (DB requires it).
            'kelas.*'              => 'required|string',
            'seat.*'               => 'required|string',
        ];

        // If the tiket table defines a `kelas` column, require kelas selection per passenger
        if (Schema::hasColumn('tiket', 'kelas')) {
            $rules['kelas.*'] = 'required|string';
        }

        $r->validate($rules);

        $flight = Penerbangan::findOrFail($r->id_penerbangan);

        // Cek kursi bentrok
        $takenSeats = Tiket::where('id_penerbangan', $flight->id)->pluck('seat')->toArray();

        foreach ($r->seat as $seat) {
            if (in_array($seat, $takenSeats)) {
                return back()->with('error', "Seat $seat sudah terisi.");
            }
        }

        // Hitung total harga
        $jumlahTiket = count($r->nama_penumpang);
        // dd($jumlahTiket);
        $totalBayar  = $flight->harga * $jumlahTiket;
        // dd($totalBayar);

        // === Buat pemesanan utama ===
        // Note: some databases may not have a `total` column on `pemesanan`.
        // To avoid SQL errors without changing the schema, don't include
        // the `total` field when creating the record. If you prefer to
        // persist total, add the column via migration.
        $pemesananData = [
            'id_users'        => Auth::id(),
            'id_penerbangan'  => $flight->id,
            'kode'            => $this->generateUniqueCode(),
            'jumlah_tiket'    => $jumlahTiket,
            // Persist the selected class for the entire booking. Use the first
            // passenger's class as the booking class.
            'tipe_kelas'      => $r->kelas[0] ?? 'ekonomi',
            'status'          => 'Pending',
        ];

        try {
            DB::transaction(function() use ($r, $flight, $pemesananData) {
                $pemesanan = Pemesanan::create($pemesananData);

                // === Simpan tiket per penumpang ===
                for ($i = 0; $i < count($r->nama_penumpang); $i++) {
                    $ticketData = [
                        'nama_penumpang'  => $r->nama_penumpang[$i],
                        'nik'             => $r->nik[$i],
                        'id_pemesanan'    => $pemesanan->id,
                        'id_penerbangan'  => $flight->id,
                        'seat'            => $r->seat[$i],
                    ];

                    if (Schema::hasColumn('tiket', 'kelas')) {
                        $ticketData['kelas'] = $r->kelas[$i] ?? null;
                    }

                    Tiket::create($ticketData);
                }
            });

            return redirect()->route('travels.index')
                             ->with('success', 'Tiket berhasil dipesan!');
        } catch (\Exception $e) {
            Log::error('Ticket booking failed: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request' => $r->all(),
            ]);

            // If app debug is enabled show exception message to help debugging.
            $message = config('app.debug') ? $e->getMessage() : 'Terjadi kesalahan saat memproses pemesanan. Silakan coba lagi.';

            return back()->withInput()->with('error', $message);
        }
    }
}
