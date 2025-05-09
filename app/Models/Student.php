<?php

namespace App\Models;

use App\Models\Exam;
use App\Models\Research;
use App\Models\Summary;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Student extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $guard = 'student';

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'address',
        'date_of_birth',
        'muhafez_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'date_of_birth' => 'date',
    ];

    // Relationship with classes (many-to-many)
    public function classes()
    {
        return $this->belongsToMany(SchoolClass::class, 'class_student', 'student_id', 'class_id')
            ->using(ClassStudent::class)
            ->withPivot('enrolled_at', 'is_active')
            ->withTimestamps();
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    public function werds(): HasMany
    {
        return $this->hasMany(Werd::class);
    }

    public function examResults(): HasMany
    {
        return $this->hasMany(ExamResult::class);
    }

    public function quraans(): HasMany
    {
        return $this->hasMany(Quraan::class);
    }

    public function muhafez(): BelongsTo
    {
        return $this->belongsTo(Muhafez::class);
    }

    /**
     * Relationship with behaviors (many-to-many equivalent: records)
     */
    public function behaviors(): HasMany
    {
        return $this->hasMany(Behavior::class);
    }

    /**
     * Relationship with researchs (many records)
     */
    public function researchs(): HasMany
    {
        return $this->hasMany(Research::class);
    }

    /**
     * Relationship with exams (records)
     */
    public function exams(): HasMany
    {
        return $this->hasMany(Exam::class);
    }

    /**
     * Relationship with summaries
     */
    public function summaries(): HasMany
    {
        return $this->hasMany(Summary::class);
    }
}
