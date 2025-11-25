<?php

namespace App\Models;

use App\Models\BandaraModel;
use Illuminate\Database\Eloquent\Model;

class PenerbanganModel extends Model
{
    protected $table = 'penerbangan';

    protected $fillable = [
        'nama_maskapai', 'gambar', 'harga', 'id_bandara_asal', 'id_bandara_tujuan', 'tanggal', 'jam_berangkat', 'jam_tiba'
    ];

    public function asal()
    {
        return $this->belongsTo(BandaraModel::class, 'id_bandara_asal');
    }

    public function tujuan()
    {
        return $this->belongsTo(BandaraModel::class, 'id_bandara_tujuan');
    }


}
