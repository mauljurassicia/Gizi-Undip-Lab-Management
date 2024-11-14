<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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
        'course_id'
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
        return $this->morphByMany('App\Models\User', 'scheduleable');
    }

    public function groups() {
        return $this->morphByMany('App\Models\Group', 'scheduleable');
    }

    
}
