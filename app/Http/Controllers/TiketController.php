<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Penerbangan;
use App\Models\Tiket;
use App\Models\Pemesanan;
use Illuminate\Support\Str;

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
        $r->validate([
            'id_penerbangan'       => 'required|exists:penerbangan,id',
            'nama_penumpang.*'     => 'required|string',
            'nik.*'                => 'required|string',
            'seat.*'               => 'required|string',
        ]);

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
        $pemesanan = Pemesanan::create([
            'id_users'        => auth()->id(),
            'id_penerbangan'  => $flight->id,
            'kode'            => $this->generateUniqueCode(),
            'jumlah_tiket'    => $jumlahTiket,
            'total'           => $totalBayar,
            'status'          => 'Pending',
        ]);

        // === Simpan tiket per penumpang ===
        for ($i = 0; $i < count($r->nama_penumpang); $i++) {
            Tiket::create([
                'nama_penumpang'  => $r->nama_penumpang[$i],
                'nik'             => $r->nik[$i],
                'kelas'           => $r->kelas[$i],
                'id_pemesanan'    => $pemesanan->id,
                'id_penerbangan'  => $flight->id,
                'seat'            => $r->seat[$i],
            ]);
        }

        return redirect()->route('user.tiket.sukses')
                         ->with('success', 'Tiket berhasil dipesan!');
    }
}
