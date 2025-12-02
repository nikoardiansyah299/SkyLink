<?php

namespace App\Http\Controllers;

use App\Models\Penerbangan;
use App\Models\PenerbanganModel;
use App\Models\Bandara;
use App\Models\Maskapai;
use Illuminate\Http\Request;

class TravelsController extends Controller
{
    /**
     * ===========================
     * INDEX (GABUNG FILTER & SEARCH)
     * ===========================
     */
    public function index(Request $request)
    {
        // === BAGIAN DARI KODE TEMANMU (search + return flight) ===
        $query = PenerbanganModel::with(['maskapai', 'asal', 'tujuan']);

        // Search by destination / maskapai / bandara
        if ($request->has('destination') && trim($request->destination) !== '') {
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

        // Filter tanggal keberangkatan
        if ($request->has('checkin') && $request->checkin) {
            $query->where('tanggal', $request->checkin);
        }

        $flights = $query->get();

        // Return flight (jika ada checkout)
        $returnFlights = null;
        if ($request->has('checkout') && $request->checkout) {
            $returnFlights = PenerbanganModel::with(['maskapai', 'asal','tujuan'])
                        ->where('tanggal', $request->checkout)
                        ->get();
        }

        // ============ BAGIAN DARI KODEMU (FILTER DOMESTIK/INTERNASIONAL) ============
        $kategori = $request->get('kategori');

        // Gunakan hasil flights yang sudah didapat dari query temanmu
        $flights = $flights->filter(function ($f) use ($kategori) {
            if (!$kategori) return true;

            $asal = $f->asal->negara ?? null;
            $tujuan = $f->tujuan->negara ?? null;

            if ($kategori === 'domestik') {
                return $asal === $tujuan;
            }

            if ($kategori === 'internasional') {
                return $asal !== $tujuan;
            }

            return true;
        });

        return view('travels.flights', [
            'flights'       => $flights,
            'returnFlights' => $returnFlights,
            'kategori'      => $kategori
        ]);
    }

    /**
     * ===========================
     * CREATE (GABUNG KEDUANYA)
     * ===========================
     */
    public function create()
    {
        $bandara = Bandara::all();
        $maskapai = Maskapai::all(); // agar fitur kamu tetap jalan

        // tetap pakai view kamu
        return view('travels.create', compact('bandara', 'maskapai'));
    }



    /**
     * ===========================
     * STORE 
     * ===========================
     */
    public function store(Request $r)
    {
        $r->validate([
            'maskapai_id'   => 'required|exists:maskapai,id',
            'harga'         => 'required|numeric',
            'asal_id'       => 'required',
            'tujuan_id'     => 'required',
            'tanggal'       => 'required|date',
            'jam_berangkat' => 'required',
            'jam_tiba'      => 'required',
        ]);

        Penerbangan::create([
            'maskapai_id'        => $r->maskapai_id,
            'harga'              => $r->harga,
            'id_bandara_asal'    => $r->asal_id,
            'id_bandara_tujuan'  => $r->tujuan_id,
            'tanggal'            => $r->tanggal,
            'jam_berangkat'      => $r->jam_berangkat,
            'jam_tiba'           => $r->jam_tiba,
        ]);

        return redirect()->route('travels.index')
                         ->with('success', 'Data penerbangan berhasil ditambahkan!');
    }

}
