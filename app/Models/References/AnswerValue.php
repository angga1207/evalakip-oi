<?php

namespace App\Models\References;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnswerValue extends Model
{
    use HasFactory;
    protected $table = 'ref_jawaban_value';
    protected $fillable = [
        'ref_jawaban_id',
        'label',
        'nilai',
    ];

    public function Parent()
    {
        return $this->belongsTo(Answer::class, 'ref_jawaban_id');
    }
}
