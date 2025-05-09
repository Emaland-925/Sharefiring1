<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'company_id',
        'role',
        'points',
        'level',
        'profile_image',
        'language_preference',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function managedCompany()
    {
        return $this->hasOne(Company::class, 'admin_id');
    }

    public function createdCourses()
    {
        return $this->hasMany(Course::class, 'creator_id');
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    public function enrolledCourses()
    {
        return $this->belongsToMany(Course::class, 'enrollments')
            ->withPivot('progress', 'completion_status')
            ->withTimestamps();
    }

    public function createdChallenges()
    {
        return $this->hasMany(Challenge::class, 'creator_id');
    }

    public function userAnswers()
    {
        return $this->hasMany(UserAnswer::class);
    }

    public function redeemedRewards()
    {
        return $this->belongsToMany(Reward::class, 'user_rewards')
            ->withPivot('redeemed_date');
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isCompanyAdmin()
    {
        return $this->role === 'company';
    }

    public function isEmployee()
    {
        return $this->role === 'employee';
    }
}