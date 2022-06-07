<?php

namespace App\Http\Controllers;

use App\Models\Jadwal;
use App\Models\Category;
use App\Models\Pemesanan;
use App\Models\Lapangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class PemesananController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $jadwalAwal = Jadwal::orderBy('start')->get()->groupBy('start');
        if (count($jadwalAwal) > 0) {
            foreach ($jadwalAwal as $key => $value) {
                $data['start'][] = $key;
            }
        } else {
            $data['start'] = [];
        }
        $jadwalAkhir = Jadwal::orderBy('end')->get()->groupBy('end');
        if (count($jadwalAkhir) > 0) {
            foreach ($jadwalAkhir as $key => $value) {
                $data['end'][] = $key;
            }
        } else {
            $data['end'] = [];
        }
        $category = Category::orderBy('name')->get();
        return view('client.index', compact('data', 'category'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($request->category) {
            $category = Category::find($request->category);
            $data = [
                'start' => $request->start,
                'end' => $request->end,
                'category' => $category->id,
                'fasilitas' => $request->fasilitas,
            ];
            $data = Crypt::encrypt($data);
            return redirect()->route('show', ['id' => $category->slug, 'data' => $data]);
        } else {
            $this->validate($request, [
                'jadwal_id' => 'required',
                'fasilitas' => 'required',
            ]);

            $huruf = "ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";
            $kodePemesanan = strtoupper(substr(str_shuffle($huruf), 0, 7));

            $jadwal = Jadwal::with('lapangan.category')->find($request->jadwal_id);
            // $jumlah_antri = $jadwal->lapangan->jumlah + 2;
            // $antri = (int) floor($jumlah_antri / 5);
            // $kode = "ABCDE";
            // $kodeAntri = strtoupper(substr(str_shuffle($kode), 0, 1) . rand(1, $antri));

            $fasilitas = $request->fasilitas . " " . $jadwal->jam;

            Pemesanan::Create([
                'kode' => $kodePemesanan,
                // 'antri' => $request,
                'fasilitas' => $fasilitas,
                'total' => $jadwal->harga,
                'jadwal_id' => $jadwal->id,
                'pelanggan_id' => Auth::user()->id
            ]);

            return redirect()->back()->with('success', 'Pemesanan Tiket Boolaraga ' . $jadwal->lapangan->category->name . ' Sukses!');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, $data)
    {
        $data = Crypt::decrypt($data);
        $category = Category::find($data['category']);
        $jadwal = Jadwal::with('lapangan')->where('start', $data['start'])->where('end', $data['end'])->get();
        if ($jadwal->count() > 0) {
            foreach ($jadwal as $val) {
                $pemesanan = Pemesanan::where('jadwal_id', $val->id)->where('fasilitas')->count();
                if ($val->lapangan) {
                    $antri = Lapangan::find($val->lapangan_id)->jumlah - $pemesanan;
                    if ($val->lapangan->category_id == $category->id) {
                        $dataJadwal[] = [
                            'harga' => $val->harga,
                            'start' => $val->start,
                            'end' => $val->end,
                            'tujuan' => $val->tujuan,
                            'lapangan' => $val->lapangan->name,
                            'kode' => $val->lapangan->kode,
                            'antri' => $antri,
                            'fasilitas' => $data['fasilitas'],
                            'id' => $val->id,
                        ];
                    }
                }
            }
            sort($dataJadwal);
        } else {
            $dataJadwal = [];
        }
        $id = $category->name;
        return view('client.show', compact('id', 'dataJadwal'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = Crypt::decrypt($id);
        $jadwal = Jadwal::find($data['id']);
        $lapangan = Lapangan::find($jadwal->lapangan_id);
        return view('client.antri', compact('data', 'lapangan'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function pesan($antri, $data)
    {
        $d = Crypt::decrypt($data);
        $huruf = "ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";
        $kodePemesanan = strtoupper(substr(str_shuffle($huruf), 0, 7));

        $jadwal = Jadwal::with('lapangan.category')->find($d['id']);

        $fasilitas = $d['fasilitas'] . " " . $jadwal->jam;

        Pemesanan::Create([
            'kode' => $kodePemesanan,
            'antri' => $antri,
            'fasilitas' => $fasilitas,
            'total' => $jadwal->harga,
            'jadwal_id' => $jadwal->id,
            'pelanggan_id' => Auth::user()->id
        ]);

        return redirect('/')->with('success', 'Pemesanan Tiket Boolaraga ' . $jadwal->lapangan->category->name . ' Sukses!');
    }
}
