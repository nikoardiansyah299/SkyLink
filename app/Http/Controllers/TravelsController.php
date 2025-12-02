<?php

namespace App\Http\Controllers;

<<<<<<< HEAD
use App\Models\Penerbangan;
use App\Models\Bandara;
use App\Models\Maskapai;
=======
use App\Models\PenerbanganModel;
use App\Models\Bandara;
>>>>>>> e5b7a928db64dbd57fde9fef74848eaa19a1908a
use Illuminate\Http\Request;

class TravelsController extends Controller
{
<<<<<<< HEAD
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
=======
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
>>>>>>> e5b7a928db64dbd57fde9fef74848eaa19a1908a
    }

    public function create(){
        $bandara = Bandara::all();
<<<<<<< HEAD
        $maskapai = Maskapai::all(); // ambil daftar maskapai
        return view('/travels/create', compact('bandara', 'maskapai'));
=======
        return view('/travels/create', compact('bandara'));
>>>>>>> e5b7a928db64dbd57fde9fef74848eaa19a1908a
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
