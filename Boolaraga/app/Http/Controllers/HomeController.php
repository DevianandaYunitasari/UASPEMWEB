<?php

namespace App\Http\Controllers;

use App\Models\Pemesanan;
use App\Models\Jadwal;
use App\Models\Lapangan;
use App\Models\User;
use Illuminate\Http\Request;

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
        $jadwal = Jadwal::count();
        $pendapatan = Pemesanan::where('status', 'Sudah Bayar')->sum('total');
        $lapangan = Lapangan::count();
        $user = User::count();
        return view('server.home', compact('jadwal', 'pendapatan', 'lapangan', 'user'));
    }
}
