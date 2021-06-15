<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AppController extends Controller
{
    // Needs to redirect router (vue) to correct place

    public function index() {
        
        return view('home');

    }
}
