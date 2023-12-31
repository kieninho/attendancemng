<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\DB;


class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'birthday',
        'is_teacher',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'birthday' => 'datetime',
    ];

    public function getBirthdayAttribute($value)
    {
        if (!empty($value)) {
            return date('d/m/Y', strtotime($value));
        } else {
            return null;
        }
    }

    public function getCreatedAtAttribute($value)
    {
        return date('d/m/Y', strtotime($value));
    }


    public function lessons()
    {
        return $this->belongsToMany(Lesson::class, 'teacher_lesson', 'teacher_id', 'lesson_id')->where('status', 1);
    }


    public function classes()
    {
        return $this->lessons()->with('classes')->get()->pluck('classes')->unique();
    }

    public function countClasses()
    {
        $queryResult = DB::table('users as teachers')
            ->leftJoin('teacher_lesson', 'teacher_lesson.teacher_id', '=', 'teachers.id')
            ->leftJoin('lessons', 'lessons.id', '=', 'teacher_lesson.lesson_id')
            ->leftJoin('classes', 'classes.id', '=', 'lessons.class_id')
            ->where('teachers.id', $this->id)
            ->where('lessons.status', 1)
            ->where('classes.status', 1)
            ->groupBy('classes.id')
            ->select('classes.id')
            ->get();

        return count($queryResult);
    }

    public static function searchAdmin($keyword, $records_per_page)
    {
        $result = User::where(function ($query) use ($keyword) {
            $query->where('name', 'like', "%$keyword%")
                ->orWhere('email', 'like', "%$keyword%");
        })->where('status', 1)->where('is_teacher',0)
        ->orderBy('created_at','desc')->paginate($records_per_page);

        return $result;
    }

    public static function searchTeacher($keyword, $records_per_page)
    {
        $result = User::where(function ($query) use ($keyword) {
            $query->where('name', 'like', "%$keyword%")
                ->orWhere('email', 'like', "%$keyword%");
        })->where('status', 1)->where('is_teacher',1)
        ->orderBy('created_at','desc')->paginate($records_per_page);

        return $result;
    }

    public static function getTeachers(){
        return User::where('is_teacher',1)->where('status',1);
    }

    public static function getTeachersToExport(){
        return User::where('is_teacher', 1)->where('status', 1)->get()->sortByDesc('created_at');
    }

    public static function getUsersToExport(){
        return User::where('is_teacher', 0)->where('status', 1)->get()->sortByDesc('created_at');
    }

    public static function deleteById($id){
        $record = User::findOrFail($id);
        if(!empty($record)){
            $record->status = 0;
            $record->save();
        }
    }
}
