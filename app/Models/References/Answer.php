<?php

namespace App\Models\References;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    use HasFactory;

    protected $table = 'ref_jawaban';

    protected $fillable = [
        'label',
    ];

    public function Values()
    {
        return $this->hasMany(AnswerValue::class, 'ref_jawaban_id');
    }
}
