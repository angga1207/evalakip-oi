<?php

namespace App\Models\Components;

use App\Searchable;
use App\Models\Data\Kriteria;
use App\Models\References\Periode;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Component extends Model
{
    use HasFactory, Searchable;
    protected $table = 'components';
    protected $fillable = [
        'parent_id',
        'ref_periode_id',
        'nama',
        'bobot',
        'keterangan',
    ];

    protected $searchable = [
        'nama',
        'Children.nama',
    ];

    public function RefPeriode()
    {
        return $this->belongsTo(Periode::class);
    }

    public function Children()
    {
        return $this->hasMany(Component::class, 'parent_id');
    }

    public function Parent()
    {
        return $this->belongsTo(Component::class, 'parent_id');
    }

    function Criterias()
    {
        return $this->hasMany(Kriteria::class, 'component_id');
    }
}
