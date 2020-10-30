<?php

namespace App\Http\Controllers;

use App\Models\Researcher;
use Illuminate\Http\Request;

class PagesController extends Controller
{

    public function researchers(){
        return view('pages.researchers');
    }
}
