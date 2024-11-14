<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

/**
 * @SWG\Definition(
 *      definition="Group",
 *      required={""},
 *      @SWG\Property(
 *          property="name",
 *          description="name",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="status",
 *          description="status",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="description",
 *          description="description",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="banner",
 *          description="banner",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="thumbnail",
 *          description="thumbnail",
 *          type="string"
 *      )
 * )
 */
class Group extends Model
{

    public $table = 'groups';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];


    public $fillable = [
        'name',
        'status',
        'course_id',
        'description',
        'banner',
        'thumbnail'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'name' => 'string',
        'status' => 'string',
        'description' => 'string',
        'banner' => 'string',
        'thumbnail' => 'string'
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

    public function course() {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function users(){
        return $this->belongsToMany(User::class, 'users_groups', 'group_id', 'user_id');
    }

    public function schedules(){
        return $this->morphMany(Schedule::class, 'scheduleable');
    }
}
