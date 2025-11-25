<?php

namespace App\Http\Controllers;

use App\Models\PenerbanganModel;
use App\Models\BandaraModel;
use Illuminate\Http\Request;

class TravelsController extends Controller
{
    public function index() {
        $flights = PenerbanganModel::with(['asal', 'tujuan'])->get();
        // return $flights;
        return view('/travels/flights', compact('flights'));
    }

    public function create(){
        $bandara = BandaraModel::all();
        return view('/travels/create', compact('bandara'));
    }

    public function store(Request $r){

        $r->validate([
            'maskapai' => 'required',
            'harga' => 'required|numeric',
            'asal_id' => 'required',
            'tujuan_id' => 'required',
            'tanggal' => 'required|date',
        ]);

        PenerbanganModel::create([
            'nama_maskapai' => $r->maskapai,
            'harga' => $r->harga,
            'id_bandara_asal' => $r->asal_id,
            'id_bandara_tujuan' => $r->tujuan_id,
            'tanggal' => $r->tanggal,
            'jam_berangkat' => $r->jam_berangkat,
            'jam_tiba' => $r->jam_tiba,
            'gambar' => '/images/plane1.png'
        ]);

        return redirect()->back()->with('success', 'Data penerbangan berhasil ditambahkan!');
    }
    
}
