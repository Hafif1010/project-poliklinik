<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AntrianPoli extends Model
{
    protected $table = 'antrian_poli';

    protected $fillable = [
        'id_poli',
        'no_antrian_dipanggil',
    ];

    public function poli()
    {
        return $this->belongsTo(Poli::class, 'id_poli');
    }
}

