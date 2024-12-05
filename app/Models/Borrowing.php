<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

/**
 * @SWG\Definition(
 *      definition="Borrowing",
 *      required={""},
 *      @SWG\Property(
 *          property="userable_type",
 *          description="userable_type",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="activity_name",
 *          description="activity_name",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="description",
 *          description="description",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="quantity",
 *          description="quantity",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="return_quantity",
 *          description="return_quantity",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="report",
 *          description="report",
 *          type="string"
 *      )
 * )
 */
class Borrowing extends Model
{

    public $table = 'borrowings';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];


    public $fillable = [
        'room_id',
        'userable_type',
        'userable_id',
        'activity_name',
        'description',
        'equipment_id',
        'quantity',
        'start_date',
        'end_date',
        'return_quantity',
        'return_date',
        'report',
        'status'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'userable_type' => 'string',
        'activity_name' => 'string',
        'description' => 'string',
        'quantity' => 'integer',
        'return_quantity' => 'integer',
        'report' => 'string'
    ];

    protected $appends = ['name'];

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

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function equipment()
    {
        return $this->belongsTo(Equipment::class);
    }

    public function logBooks()
    {
        return $this->morphMany(LogBook::class, 'logbookable');
    }


    public function userable()
    {
        return $this->morphTo();
    }

    public function getNameAttribute()
    {
        return $this->activity_name;
    }

    public function getLogBookInAttribute()
    {
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

    public function getLogBookOutAttribute()
    {
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

    public function getNotAllowedAttribute()
    {
        /** @var User $user */
        $user = Auth::user();

        if ($user->hasRole('administrator') || $user->hasRole('laborant')) {
            return true;
        }

        // Handle different userable types
        if ($this->userable_type === User::class) {
            return $this->userable_id !== $user->id;
        }

        if ($this->userable_type === Group::class) {
            return !$user->groups->contains($this->userable_id);
        }

        return true; // Not allowed if userable is invalid/null
    }

    public function coverLetter(){
        return $this->morphOne(CoverLetter::class, 'cover_letterable');
    }

    public function creator(){
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function getCreatorRoleAttribute(){

        /** @var User $creator */
        $creator = $this->creator;
        return $creator?->roles()->first()?->name ?? null;
    }
}
