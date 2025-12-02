<?php

namespace App\Http\Controllers;

use App\Models\Penerbangan;
use App\Models\Bandara;
use App\Models\Maskapai;
use Illuminate\Http\Request;

class TravelsController extends Controller
{
    public function index(Request $r)
    {
        $kategori = $r->get('kategori');

        $flights = Penerbangan::with(['bandaraAsal', 'bandaraTujuan', 'maskapai'])
            ->get()
            ->filter(function ($f) use ($kategori) {

                if (!$kategori) return true; // tampilkan semua

                $asal  = $f->bandaraAsal->negara;
                $tujuan = $f->bandaraTujuan->negara;

                if ($kategori === 'domestik') {
                    return $asal === $tujuan;
                }

                if ($kategori === 'internasional') {
                    return $asal !== $tujuan;
                }

                return true;
            });

        return view('travels.flights', [
            'flights' => $flights,
            'kategori' => $kategori
        ]);
    }

    public function create(){
        $bandara = Bandara::all();
        $maskapai = Maskapai::all(); // ambil daftar maskapai
        return view('/travels/create', compact('bandara', 'maskapai'));
    }

    public function store(Request $r){

        $r->validate([
            'maskapai_id' => 'required|exists:maskapai,id',
            'harga' => 'required|numeric',
            'asal_id' => 'required',
            'tujuan_id' => 'required',
            'tanggal' => 'required|date',
            'jam_berangkat' => 'required',
            'jam_tiba' => 'required',
        ]);

        Penerbangan::create([
            'maskapai_id' => $r->maskapai_id,
            'harga' => $r->harga,
            'id_bandara_asal' => $r->asal_id,
            'id_bandara_tujuan' => $r->tujuan_id,
            'tanggal' => $r->tanggal,
            'jam_berangkat' => $r->jam_berangkat,
            'jam_tiba' => $r->jam_tiba,
        ]);

        return redirect()->route('travels.index')->with('success', 'Data penerbangan berhasil ditambahkan!');
    }

}
