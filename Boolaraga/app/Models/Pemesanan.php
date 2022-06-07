<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pemesanan extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode',
        'antri',
        'jadwal',
        'total',
        'status',
        'jadwal_id',
        'pelanggan_id',
        'petugas_id'
    ];

    public function jadwal()
    {
        return $this->belongsTo('App\Models\Jadwal', 'jadwal_id');
    }

    public function pelanggan()
    {
        return $this->belongsTo('App\Models\User', 'pelanggan_id');
    }

    public function petugas()
    {
        return $this->belongsTo('App\Models\User', 'petugas_id');
    }

    protected $table = 'pemesanan';
}
