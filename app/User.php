<?php

namespace App;

use App\Models\Borrowing;
use App\Models\LogBook;
use App\Models\Schedule;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use SoftDeletes;
    use HasRoles;
    use Notifiable;

    public static $roleType = [
        'admin' => 'Admin',
        'teacher' => 'Teacher',
        'student' => 'Student',
        'laborant' => 'Laborant',
        'guest' => 'Guest',
    ];

    protected $guard_name = 'web';
    public $table = 'users';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];


    public $fillable = [
        'name',
        'email',
        'password',
        'image',
        'identity_number',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'name' => 'string',
        'email' => 'string',
        'password' => 'string',
        'image' => 'string',
        'remember_token' => 'string',
        'identity_number' => 'string',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [];

    public function getTableColumns()
    {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
    }
    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = bcrypt($password);
    }

    public function appointments()
    {
        return $this->morphToMany('App\Models\Room', 'appointments');
    }

    public function groups()
    {
        return $this->belongsToMany('App\Models\Group', 'users_groups');
    }

    public function schedules()
    {
        return $this->morphToMany(Schedule::class, 'scheduleable', 'scheduleables', 'scheduleable_id', 'schedule_id');
    }
    

    public function groupSchedules()
    {
        if ($this->groups->isEmpty()) {
            return Schedule::query()->whereNull('id');
        }

        // Start with the schedules of the first group
        $query = $this->groups->first()->schedules();

        // Union schedules from subsequent groups
        foreach ($this->groups->slice(1) as $group) {
            $query = $query->union($group->schedules());
        }

        return $query;
    }

    public function allSchedules()
    {
        // Start with the schedules for the user
        $query = $this->schedules()->select(
            'schedules.id', 
            'schedules.name', 
            'scheduleables.scheduleable_id as pivot_scheduleable_id', 
            'scheduleables.schedule_id as pivot_schedule_id', 
            'scheduleables.scheduleable_type as pivot_scheduleable_type'
        );
    
        // Add schedules from all groups
        if ($this->groups->isNotEmpty()) {
            foreach ($this->groups as $group) {
                $query = $query->union(
                    $group->schedules()->select(
                        'schedules.id', 
                        'schedules.name', 
                        'scheduleables.scheduleable_id as pivot_scheduleable_id', 
                        'scheduleables.schedule_id as pivot_schedule_id', 
                        'scheduleables.scheduleable_type as pivot_scheduleable_type'
                    )
                );
            }
        }
    
        return $query;
    }
    
    

    public function logBooks()
    {
        return $this->morphMany(LogBook::class, 'userable');
    }

    public function borrowings()
    {
        return $this->morphMany(Borrowing::class, 'userable');
    }

    public function groupBorrowings()
    {
        if ($this->groups->isEmpty()) {
            return Borrowing::query()->whereNull('id');
        }

        // Start with the borrowings of the first group
        $query = $this->groups->first()->borrowings();

        // Union borrowings from subsequent groups
        foreach ($this->groups->slice(1) as $group) {
            $query = $query->union($group->borrowings());
        }

        return $query;
    }

    public function allBorrowings()
    {
        $query = $this->borrowings();

        // Add borrowings from all groups
        if ($this->groups->isNotEmpty()) {
            foreach ($this->groups as $group) {
                $query = $query->union($group->borrowings());
            }
        }

        return $query;
    }
}
