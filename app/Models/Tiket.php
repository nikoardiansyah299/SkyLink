<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tiket extends Model
{
    protected $table = 'tiket';

    protected $fillable = [
        'nama_penumpang',
        'nik',
        'id_pemesanan',
        'id_penerbangan',
        'seat',
        'kelas',
    ];

    public function pemesanan()
    {
        return $this->belongsTo(Pemesanan::class, 'id_pemesanan');
    }

    public function penerbangan()
    {
        return $this->belongsTo(Penerbangan::class, 'id_penerbangan');
    }
}
