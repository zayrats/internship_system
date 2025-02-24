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
        'description',
        'start_date',
        'end_date',
        'position',
        'feedback',
        'book_kp',
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
}
