<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    private function greeting()
    {
        date_default_timezone_set('Asia/Jakarta');
        $jam = date('H');
        $name = Auth::user()->name;
        if ($jam >= 18) {
            $greeting = "Selamat Malam ". $name;
        } elseif ($jam >= 12) {
            $greeting = "Selamat Siang ". $name;
        } elseif ($jam < 12) {
            $greeting = "Selamat Pagi ". $name;
        }
        return $greeting;
    }

    public function dashboard()
    {
        $greeting = $this->greeting();
        
        return view('dashboard', compact('greeting'));
    }
}
