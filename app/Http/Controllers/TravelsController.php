<?php

namespace App\Http\Controllers;

use App\Models\PenerbanganModel;
use Illuminate\Http\Request;

class TravelsController extends Controller
{
    public function index() {
        $flights = PenerbanganModel::with(['asal', 'tujuan'])->get();
        // return $flights;
        return view('/travels/flights', compact('flights'));
    }

    public function create(){
        return view('/travels/create');
    }
}
