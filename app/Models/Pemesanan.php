<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pemesanan extends Model
{
    protected $table = 'pemesanan';

    protected $fillable = [
        'id_users',
        'id_penerbangan',
        'kode',
        'jumlah_tiket',
        'total',
        'tipe_kelas',
        'status',
    ];

    protected $casts = [
        'status' => 'string',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_users');
    }

    public function penerbangan()
    {
        return $this->belongsTo(Penerbangan::class, 'id_penerbangan');
    }

    public function tiket()
    {
        return $this->hasMany(Tiket::class, 'id_pemesanan');
    }
}
