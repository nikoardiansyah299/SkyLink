<?php

namespace App\Models;

use App\Models\Bandara;
use App\Models\Maskapai;
use Illuminate\Database\Eloquent\Model;

class PenerbanganModel extends Model
{
    protected $table = 'penerbangan';

    protected $fillable = [
        'nama_maskapai', 'gambar', 'harga', 'id_bandara_asal', 'id_bandara_tujuan', 'tanggal', 'jam_berangkat', 'jam_tiba'
    ];

    public function asal()
    {
        return $this->belongsTo(Bandara::class, 'id_bandara_asal');
    }

    public function tujuan()
    {
        return $this->belongsTo(Bandara::class, 'id_bandara_tujuan');
    }

    public function maskapai()
    {
        return $this->belongsTo(Maskapai::class, 'maskapai_id');
    }

}
