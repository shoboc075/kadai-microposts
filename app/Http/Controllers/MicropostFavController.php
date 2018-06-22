<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MicropostFavController extends Controller
{
    public function store($id)
    {
        \Auth::user()->fav($id);
        return redirect()->back();
    }
    
    public function destroy($id)
    {
        \Auth::user()->unfav($id);
        return redirect()->back();
    }
    
    
    
}
