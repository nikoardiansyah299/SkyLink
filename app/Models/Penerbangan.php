<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penerbangan extends Model
{
    protected $table = 'penerbangan';

    protected $fillable = [
        'maskapai_id',
        'harga',
        'id_bandara_asal',
        'id_bandara_tujuan',
        'tanggal',
        'jam_berangkat',
        'jam_tiba',
    ];

    public function bandaraAsal()
    {
        return $this->belongsTo(Bandara::class, 'id_bandara_asal');
    }

    public function bandaraTujuan()
    {
        return $this->belongsTo(Bandara::class, 'id_bandara_tujuan');
    }

    public function pemesanan()
    {
        return $this->hasMany(Pemesanan::class, 'id_penerbangan');
    }

    public function maskapai()
    {
        return $this->belongsTo(Maskapai::class, 'maskapai_id');
    }
}
