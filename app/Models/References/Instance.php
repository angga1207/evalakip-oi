<?php

namespace App\Models\References;

use App\Searchable;
use App\Models\Data\Grade;
use App\Models\Data\Jawaban;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Instance extends Model
{
    use HasFactory, Searchable;
    protected $table = 'instances';
    protected $fillable = [
        'id_eoffice',
        'unit_id',
        'name',
        'alias',
        'code',
        'logo',
        'status',
        'description',
        'address',
        'phone',
        'fax',
        'email',
        'website',
        'facebook',
        'instagram',
        'youtube',
    ];

    protected $searchable = [
        'name',
        'alias',
        'code',
        'status',
        'description',
        'address',
        'phone',
        'fax',
        'email',
        'website',
        'facebook',
        'instagram',
        'youtube',
    ];

    function Unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }

    function GetSkor($periodeId = null)
    {
        if (!$periodeId) {
            $periodeId = Periode::where('is_active', true)->first()->id;
        }

        $skor = DB::table('instance_skor')
            ->where('instance_id', $this->id)
            ->where('periode_id', $periodeId)
            ->value('skor');

        return $skor;
    }

    function GetGrade()
    {
        $skor = $this->GetSkor() ?? 0;
        $grade = Grade::where('nilai', '>=', $skor)
            ->orderBy('nilai', 'asc')
            ->first();
        return $grade ? $grade->predikat : 'E';
    }

    function CalculateSkor($periodeId = null)
    {
        $skor = 0;
        if (!$periodeId) {
            $periodeId = Periode::where('is_active', true)->first()->id;
        }

        $jawaban = Jawaban::where('instance_id', $this->id)
            ->where('ref_periode_id', $periodeId)
            ->get();

        $skor = $jawaban->sum('skor');
        return $skor;
    }

    function Penilaian($periodeId = null)
    {
        $penilaian = [];
        if (!$periodeId) {
            $periodeId = Periode::where('is_active', true)->first()->id;
        }

        $jawaban = Jawaban::where('instance_id', $this->id)
            ->where('ref_periode_id', $periodeId)
            ->get();

        foreach ($jawaban as $j) {
            $penilaian[] = [
                'user' => $j->user,
                'skor' => $j->skor,
                'is_submitted' => $j->is_submitted,
                'is_verified' => $j->is_verified,
            ];
        }

        return $penilaian;
    }
}
