<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Concerns\HasTimestamps;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasTimestamps;

    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'birthday',
        'is_teacher',
        'status',
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
        'birthday' => 'datetime',
    ];

    public $timestamps = true;

    // get-set format attribute
    public function getBirthdayAttribute($value)
    {
        return date('d/m/Y', strtotime($value));
    }

    public function setBirthdayAttribute($value)
    {
        $this->attributes['birthday'] = date('H:i:s d/m/y', strtotime($value));
    }

    public function getCreatedAtAttribute($value)
    {
        return date('d/m/Y', strtotime($value));
    }

    public function setCreatedAtAttribute($value)
    {
        $this->attributes['created_at'] = date('H:i:s d/m/y', strtotime($value));
    }

    public function getUpdatedAtAttribute($value)
    {
        return date('d/m/Y', strtotime($value));
    }

    public function setUpdatedAtAttribute($value)
    {
        $this->attributes['updated_at'] = date('H:i:s d/m/y', strtotime($value));
    }

}
