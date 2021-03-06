<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserFollowController extends Controller
{
    public function store($id) 
    {
        //$request
        
        \Auth::user()->follow($id);
        return redirect()->back();

    }
    
    public function destroy($id)
    {
        \Auth::user()->unfollow($id);
        return redirect()->back();
    }
}
