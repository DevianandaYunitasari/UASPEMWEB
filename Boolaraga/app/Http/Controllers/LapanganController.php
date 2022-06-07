<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Lapangan;
use Illuminate\Http\Request;

class LapanganController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $category = Category::orderBy('name')->get();
        $lapangan = Lapangan::with('category')->orderBy('kode')->orderBy('name')->get();
        return view('server.lapangan.index', compact('category', 'lapangan'));
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
        $this->validate($request, [
            'name' => 'required',
            'kode' => 'required',
            'jumlah' => 'required',
            'category_id' => 'required'
        ]);

        Lapangan::updateOrCreate(
            [
                'id' => $request->id
            ],
            [
                'name' => $request->name,
                'kode' => strtoupper($request->kode),
                'jumlah' => $request->jumlah,
                'category_id' => $request->category_id,
            ]
        );

        if ($request->id) {
            return redirect()->route('lapangan.index')->with('success', 'Sukses Memperbarui Lapangan!');
        } else {
            return redirect()->back()->with('success', 'Sukses Menambahkan Lapangan!');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $category = Category::orderBy('name')->get();
        $lapangan = Lapangan::find($id);
        return view('server.lapangan.edit', compact('category', 'lapangan'));
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
        Lapangan::find($id)->delete();
        return redirect()->back()->with('success', 'Sukses Menghapus Lapangan!');
    }
}
