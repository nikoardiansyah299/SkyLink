<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Penerbangan;
use App\Models\Tiket;
use App\Models\Pemesanan;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

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
        // Note: some databases may not have a `total` column on `pemesanan`.
        // To avoid SQL errors without changing the schema, don't include
        // the `total` field when creating the record. If you prefer to
        // persist total, add the column via migration.
        $pemesananData = [
            'id_users'        => \Auth::id(),
            'id_penerbangan'  => $flight->id,
            'kode'            => $this->generateUniqueCode(),
            'jumlah_tiket'    => $jumlahTiket,
            'status'          => 'Pending',
        ];

        $pemesanan = Pemesanan::create($pemesananData);

        // === Simpan tiket per penumpang ===
        for ($i = 0; $i < count($r->nama_penumpang); $i++) {
            // Some installations may not have a `kelas` column on `tiket`.
            // To avoid SQL errors without changing the schema, do not
            // include `kelas` in the insert. The booking form still
            // collects a class selection for UX, but we won't persist it
            // unless the DB schema includes the column.
            Tiket::create([
                'nama_penumpang'  => $r->nama_penumpang[$i],
                'nik'             => $r->nik[$i],
                'id_pemesanan'    => $pemesanan->id,
                'id_penerbangan'  => $flight->id,
                'seat'            => $r->seat[$i],
            ]);
        }

        return redirect()->route('user.tiket.sukses')
                         ->with('success', 'Tiket berhasil dipesan!');
    }
}
