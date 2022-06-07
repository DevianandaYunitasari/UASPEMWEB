<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lapangan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'kode',
        'jumlah',
        'category_id'
    ];

    public function category()
    {
        return $this->belongsTo('App\Models\Category', 'category_id');
    }

    public function antri($id)
    {
        $data = json_decode($id, true);
        $antri = Pemesanan::where('jadwal_id', $data['jadwal'])->where('fasilitas', $data['fasilitas'])->where('antri', $data['antri'])->count();
        if ($antri > 0) {
            return null;
        } else {
            return $id;
        }
    }

    protected $table = 'lapangan';
}
