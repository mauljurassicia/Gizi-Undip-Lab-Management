<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @SWG\Definition(
 *      definition="LogBook",
 *      required={""},
 *      @SWG\Property(
 *          property="type",
 *          description="type",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="report",
 *          description="report",
 *          type="string"
 *      )
 * )
 */
class LogBook extends Model
{

    public $table = 'logbooks';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];


    public $fillable = [
        'user_id',
        'type',
        'time',
        'report',
        'logbookable_id',
        'logbookable_type'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'type' => 'string',
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

    public function logbookable()
    {
        return $this->morphTo();
    }

    public function userable()
    {
        return $this->morphTo();
    }

    
}
