<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jadwal extends Model
{
    use HasFactory;

    protected $fillable = [
        'tujuan',
        'start',
        'end',
        'harga',
        'jam',
        'lapangan_id'
    ];

    public function lapangan()
    {
        return $this->belongsTo('App\Models\Lapangan', 'lapangan_id');
    }

    protected $table = 'jadwal';
}
