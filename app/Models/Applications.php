<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Applications extends Model
{
    use HasFactory;

    protected $table = 'applications';
    protected $primaryKey = 'application_id';
    public $timestamps = true;

    protected $fillable = [
        'user_id',
        'vacancy_id',
        'application_date',
        'status',
        'document',
        'created_at',
        'updated_at'
    ];

    // Relasi ke internship
    public function internship()
    {
        return $this->belongsTo(Internship::class, 'internship_id', 'internship_id');
    }

    // Relasi ke vacancy
    public function vacancy()
    {
        return $this->belongsTo(Vacancy::class, 'vacancy_id', 'vacancy_id');
    }

    public function student()
    {
        return $this->belongsTo(Students::class, 'student_id', 'student_id');
    }
}
