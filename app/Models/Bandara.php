<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bandara extends Model
{
    protected $table = 'bandara';

    protected $fillable = [
        'nama_bandara',
        'kode_iata',
        'negara',
        'kota',
    ];

    public function penerbanganAsal()
    {
        return $this->hasMany(Penerbangan::class, 'id_bandara_asal');
    }

    public function penerbanganTujuan()
    {
        return $this->hasMany(Penerbangan::class, 'id_bandara_tujuan');
    }
}
