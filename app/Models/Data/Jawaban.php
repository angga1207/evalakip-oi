<?php

namespace App\Models\Data;

use App\Models\User;
use App\Models\References\Answer;
use App\Models\References\Periode;
use App\Models\References\Instance;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Jawaban extends Model
{
    use HasFactory;
    protected $table = 'jawaban';
    protected $fillable = [
        'ref_periode_id',
        'criteria_id',
        'ref_jawaban_id',
        'user_id',
        'instance_id',
        'evaluator_id',
        'skor',
        'catatan',
        'evidence',
        'catatan_evaluator',
        'is_active',
        'is_submitted',
        'is_verified',
        'verified_at',
    ];

    public function Periode()
    {
        return $this->belongsTo(Periode::class, 'ref_periode_id');
    }

    public function Kriteria()
    {
        return $this->belongsTo(Kriteria::class, 'criteria_id');
    }

    public function RefJawaban()
    {
        return $this->belongsTo(Answer::class, 'ref_jawaban_id');
    }

    function Instance()
    {
        return $this->belongsTo(Instance::class, 'instance_id');
    }

    public function User()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function Evaluator()
    {
        return $this->belongsTo(User::class, 'evaluator_id');
    }

}
