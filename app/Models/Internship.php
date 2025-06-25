<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Internship extends Model
{
    use HasFactory;

    protected $table = 'internships';
    protected $primaryKey = 'internship_id';
    public $timestamps = true;

    protected $fillable = [
        'student_id',
        'company_id',
        'title',
        'start_date',
        'end_date',
        'position',
        'feedback',
        'kp_book',
        'book_status',
        'created_at',
        'updated_at',
        'rating',
        'application_id',
        'vacancy_id',
        'draft_kp_book',
        'message'
    ];

    // Relasi ke student
    public function student()
    {
        return $this->belongsTo(Students::class, 'student_id', 'student_id');
    }

    // Relasi ke company
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'company_id');
    }

    public function application()
    {
        return $this->belongsTo(Applications::class, 'application_id', 'application_id');
    }
    // public function vacancies()
    // {
    //     return $this->hasMany(Vacancy::class, 'internship_id', 'internship_id');
    // }
}
