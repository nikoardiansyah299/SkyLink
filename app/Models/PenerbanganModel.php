<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PenerbanganModel extends Model
{
    protected $table = 'penerbangan';

    public function asal()
    {
        return $this->belongsTo(Bandara::class, 'id_bandara_asal');
    }

    public function tujuan()
    {
        return $this->belongsTo(Bandara::class, 'id_bandara_tujuan');
    }
}
