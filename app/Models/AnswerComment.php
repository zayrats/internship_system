<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnswerComment extends Model
{
    //
    use HasFactory;
    protected $fillable = [
        'answer_id',
        'user_id',
        'comment',
        'parent_id',
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function answer()
    {
        return $this->belongsTo(Answer::class);
    }
    public function parent()
    {
        return $this->belongsTo(AnswerComment::class, 'parent_id');
    }

    // Relasi ke reply (komentar anak)
    public function replies()
    {
        return $this->hasMany(AnswerComment::class, 'parent_id');
    }
}
