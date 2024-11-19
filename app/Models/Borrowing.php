<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
        'report'
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

    
}
