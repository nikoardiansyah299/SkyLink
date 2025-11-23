<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TravelsController extends Controller
{
    public function index() {
        return view('/travels/flights');
    }
}
