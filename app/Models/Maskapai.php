<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Maskapai extends Model
{
    protected $table = 'maskapai';

    protected $fillable = [
        'nama_maskapai',
        'logo',
    ];
}
