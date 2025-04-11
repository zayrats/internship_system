<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Vacancy extends Model
{
    use HasFactory;

    protected $table = 'vacancy'; // Nama tabel di database

    protected $primaryKey = 'vacancy_id'; // Primary key

    public $timestamps = true; // Mengaktifkan created_at & updated_at

    protected $fillable = [
        'company_id',
        'division',
        'duration',
        'type',
        'requirements',
        'start_date',
        'end_date',
    ];

    /**
     * Relasi ke model Company (1 lowongan dimiliki oleh 1 perusahaan)
     */
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }
    public function applications()
    {
        return $this->hasMany(Applications::class, 'vacancy_id', 'vacancy_id');
    }
}
