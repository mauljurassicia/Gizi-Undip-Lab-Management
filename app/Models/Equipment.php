<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @SWG\Definition(
 *      definition="Equipment",
 *      required={""},
 *      @SWG\Property(
 *          property="id",
 *          description="id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="name",
 *          description="name",
 *          type="string"
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
class Equipment extends Model
{
    use SoftDeletes;

    public $table = 'equipment';
    

    protected $dates = ['deleted_at'];
    
    public static $type = [
        'chemical' => 'Bahan Kimia',
        'electronic' => 'Elektronik',
        'breakable' => 'Barang Pecah Belah',
        'consumables' => 'Barang Habis Pakai',
        'utility' => 'Barang Perlengkapan',
        'other' => 'Lainnya'
    ];

    public static $unitTypes = [
        'gram' => 'Gram',
        'kg' => 'Kilogram',
        'unit' => 'Unit',
        'pcs' => 'Pcs',
        'liter' => 'Liter',
        'boxes' => 'Box',
    ];

    public $fillable = [
        'name',
        'price',
        'type',
        'image',
        'status',
        'description',
        'unit_type'
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

    
}
