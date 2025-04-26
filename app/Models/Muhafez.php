<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Student;

class Muhafez extends Model
{
    use HasFactory;

    protected $table = 'muhafezs';

    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'status'
    ];

    /**
     * Get the students assigned to this muhafez.
     */
    public function students()
    {
        return $this->hasMany(Student::class);
    }
}
