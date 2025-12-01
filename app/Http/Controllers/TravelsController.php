<?php

namespace App\Http\Controllers;

use App\Models\PenerbanganModel;
use App\Models\Bandara;
use Illuminate\Http\Request;

class TravelsController extends Controller
{
    public function index(Request $request) {
        $query = PenerbanganModel::with(['asal', 'tujuan']);

        // Search by destination (match maskapai or tujuan city/name/code)
        if ($request->has('destination') && $request->destination !== null && trim($request->destination) !== '') {
            $dest = trim($request->destination);
            $query->where(function($q) use ($dest) {
                $q->where('nama_maskapai', 'like', "%{$dest}%")
                  ->orWhereHas('tujuan', function($qq) use ($dest) {
                      $qq->where('kota', 'like', "%{$dest}%")
                         ->orWhere('nama_bandara', 'like', "%{$dest}%")
                         ->orWhere('kode_iata', 'like', "%{$dest}%");
                  })
                  ->orWhereHas('asal', function($qq) use ($dest) {
                      $qq->where('kota', 'like', "%{$dest}%")
                         ->orWhere('nama_bandara', 'like', "%{$dest}%")
                         ->orWhere('kode_iata', 'like', "%{$dest}%");
                  });
            });
        }

        // Filter by departure date
        if ($request->has('checkin') && $request->checkin) {
            $query->where('tanggal', $request->checkin);
        }

        $flights = $query->get();

        // If return date provided, fetch return flights (same date = checkout)
        $returnFlights = null;
        if ($request->has('checkout') && $request->checkout) {
            $returnFlights = PenerbanganModel::with(['asal','tujuan'])->where('tanggal', $request->checkout)->get();
        }

        return view('/travels/flights', compact('flights', 'returnFlights'));
    }

    public function create(){
        $bandara = Bandara::all();
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
