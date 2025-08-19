<?php

namespace App\Models\Data;

use App\Searchable;
use App\Models\References\Periode;
use App\Models\Components\Component;
use App\Models\References\Answer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Kriteria extends Model
{
    use HasFactory, Searchable;
    protected $table = 'criterias';
    protected $fillable = [
        'component_id',
        'ref_periode_id',
        'nama',
        'penjelasan',
        'ref_jawaban_id',
        'bobot',
        'is_active',
    ];

    protected $searchable = [
        'nama',
        'penjelasan',
        'Component.nama',
    ];

    function Component()
    {
        return $this->belongsTo(Component::class, 'component_id');
    }

    function Periode()
    {
        return $this->belongsTo(Periode::class, 'ref_periode_id');
    }

    function Answer()
    {
        return $this->belongsTo(Answer::class, 'ref_jawaban_id');
    }

    function Jawaban()
    {
        return $this->belongsTo(Answer::class, 'ref_jawaban_id');
    }
}
