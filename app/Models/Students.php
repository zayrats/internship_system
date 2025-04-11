<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Students extends Model
{
    use HasFactory;

    protected $table = 'students';
    protected $primaryKey = 'student_id';
    public $timestamps = true; // Jika tidak menggunakan created_at & updated_at, ubah ke false

    protected $fillable = [
        'user_id',
        'name',
        'student_number',
        'email',
        'password',
        'prodi',
        'department',
        'year',
        'phone',
        'address',
        'instagram',
        'birthdate',
        'gender',
        'profile_picture',
        'cv',
    ];

    protected $hidden = [
        'password',
    ];

    // Relasi ke internship
    public function internships()
    {
        return $this->hasMany(Internship::class, 'student_id', 'student_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}
