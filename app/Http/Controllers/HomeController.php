<?php

namespace App\Http\Controllers;

use App\Models\Flyer;
use App\Models\FlyerView;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HomeController extends Controller
{

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $role = auth()->user() !== null ? auth()->user()->getRoleNames()[0] : '';
        
        $isAdmin = in_array($role, ['admin', 'developer']);
        
        if($isAdmin){
            return view('dashboard.admin');
        }

        return view('dashboard.index');
    }

}
