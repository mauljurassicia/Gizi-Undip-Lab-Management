<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @SWG\Definition(
 *      definition="Room",
 *      required={""},
 *      @SWG\Property(
 *          property="id",
 *          description="id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="created_at",
 *          description="created_at",
 *          type="string",
 *          format="date-time"
 *      ),
 *      @SWG\Property(
 *          property="updated_at",
 *          description="updated_at",
 *          type="string",
 *          format="date-time"
 *      )
 * )
 */
class Room extends Model
{
    use SoftDeletes;

    public $table = 'rooms';
    

    protected $dates = ['deleted_at'];

    
    public static $types = [
        'kitchen' => 'Dapur',
        'medical' => 'Medis',
        'chemical' => 'Kimia',
        'food-science' => 'Ilmu Pangan',
        'other'    => 'Lainnya' 
    ];
    


    public $fillable = [
        'name',
        'volume',
        'status',
        'pic_id',
        'operational_days',
        'floor',
        'code',
        'image',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'operational_days' => 'array'
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

    public function pic() {
        return $this->belongsTo(User::class, 'pic_id');
    }

    public function equipment() {
        return $this->belongsToMany(Equipment::class, 'equipment_rooms', 'room_id', 'equipment_id');
    }

    public function individuals() {
        return $this->morphedByMany('App/User', 'appointments',  'appointments', 'room_id', 'appointee_id');
    }

    public function groups() {
        return $this->morphedByMany('App/Group', 'appointments', 'appointments', 'room_id', 'appointee_id');
    }



    
}
