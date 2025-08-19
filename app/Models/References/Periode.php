<?php

namespace App\Models\References;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Periode extends Model
{
    use HasFactory;
    protected $table = 'ref_periode';

    protected $fillable = [
        'label',
        'tanggal_mulai',
        'tanggal_selesai',
        'is_active',
    ];
}
