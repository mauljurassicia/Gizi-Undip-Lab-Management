<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @SWG\Definition(
 *      definition="BrokenEquipment",
 *      required={""},
 *      @SWG\Property(
 *          property="quantity",
 *          description="quantity",
 *          type="integer",
 *          format="int32"
 *      )
 * )
 */
class BrokenEquipment extends Model
{
    public $table = 'broken_equipments';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];


    public $fillable = [
        'room_id',
        'user_id',
        'equipment_id',
        'quantity',
        'broken_date',
        'return_date',
        'logbook_id',
        'report',
        'image'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'quantity' => 'integer'
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

    public function logBook(){
        return $this->belongsTo(LogBook::class, 'logbook_id');
    }

    public function equipment(){
        return $this->belongsTo(Equipment::class, 'equipment_id');
    }

    public function room(){
        return $this->belongsTo(Room::class, 'room_id');
    }

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public function returnReport(){
        return $this->hasOne(ReturnReport::class, 'broken_equipment_id');
    }

    
}
