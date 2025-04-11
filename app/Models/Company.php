<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $table = 'companies';
    protected $primaryKey = 'company_id';
    public $timestamps = true;

    protected $fillable = [
        'name',
        'address',
        'logo',
        'user_id',
        'contact_email',
        'contact_phone',
        'description',
        'x_coordinate',
        'y_coordinate',
    ];

    // Relasi ke internship
    public function internships()
    {
        return $this->hasMany(Internship::class, 'company_id', 'company_id');
    }
}
