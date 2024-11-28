<?php

namespace App\Models;

use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

/**
 * @SWG\Definition(
 *      definition="Schedule",
 *      required={""},
 *      @SWG\Property(
 *          property="userable_type",
 *          description="userable_type",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="name",
 *          description="name",
 *          type="string"
 *      )
 * )
 */
class Schedule extends Model
{

    public $table = 'schedules';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];


    public $fillable = [
        'room_id',
        'name',
        'start_schedule',
        'end_schedule',
        'course_id',
        'associated_info',
        'schedule_type',
        'grouped_schedule_code'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'name' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];

    public function getTableColumns() {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
    }

    public function room() {
        return $this->belongsTo('App\Models\Room');
    }

    public function course() {
        return $this->belongsTo('App\Models\Course');
    }

    public function users() {
        return $this->morphedByMany(User::class, 'scheduleable', 'scheduleables', 'schedule_id', 'scheduleable_id', 'id', 'id');
    }

    public function groups() {
        return $this->morphedByMany(Group::class, 'scheduleable', 'scheduleables', 'schedule_id', 'scheduleable_id', 'id', 'id');
    }

    public function logBooks() {
        return $this->morphMany(LogBook::class, 'logbookable');
    }

    public function getLogBookOutAttribute(){
        /** @var User $user */
        $user = Auth::user();
        $userExist = $this->logBooks()
        ->where('type', 'out')
        ->whereHasMorph('userable', [User::class, Group::class], function ($query) use ($user) {
            $query->when($query->getModel() instanceof User, function ($q) use ($user) {
                $q->where('id', $user->id);
            })->when($query->getModel() instanceof Group, function ($q) use ($user) {
                $q->whereHas('users', function ($subQuery) use ($user) {
                    $subQuery->where('users.id', $user->id);
                });
            });
        })->exists();

        return $userExist;
    }

    public function getLogBookInAttribute(){
        /** @var User $user */
        $user = Auth::user();

        $userExist = $this->logBooks()
        ->where('type', 'in')
        ->whereHasMorph('userable', [User::class, Group::class], function ($query) use ($user) {
            $query->when($query->getModel() instanceof User, function ($q) use ($user) {
                $q->where('id', $user->id);
            })->when($query->getModel() instanceof Group, function ($q) use ($user) {
                $q->whereHas('users', function ($subQuery) use ($user) {
                    $subQuery->where('users.id', $user->id);
                });
            });
        })->exists();

        return $userExist;
    }

    public function getNotAllowedAttribute() {
        $user = Auth::user();
        $groups = $this->groups()->whereHas('users', function ($query) use ($user) {
            $query->where('users.id', $user->id);
        })->first();

        $user = $this->users()->where('users.id', $user->id)->first();

        return !$user && !$groups;
    }

    
}
