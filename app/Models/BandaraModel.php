<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BandaraModel extends Model
{
    use HasFactory;
    protected $table = 'bandara';

    public function penerbanganAsal() {
        return $this->hasMany(Penerbangan::class, 'id_bandara_asal');
    }

    public function penerbanganTujuan() {
        return $this->hasMany(Penerbangan::class, 'id_bandara_tujan');
    }

    // protected $fillable = [
    //     'nama_bandara', 'negara', 'kota'
    // ];
}
