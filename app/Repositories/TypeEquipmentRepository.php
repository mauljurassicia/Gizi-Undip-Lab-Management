<?php

namespace App\Repositories;

use App\Models\TypeEquipment;
use Webcore\Generator\Common\BaseRepository;

/**
 * Class TypeEquipmentRepository
 * @package App\Repositories
 * @version October 9, 2024, 3:05 am UTC
 *
 * @method TypeEquipment findWithoutFail($id, $columns = ['*'])
 * @method TypeEquipment find($id, $columns = ['*'])
 * @method TypeEquipment first($columns = ['*'])
*/
class TypeEquipmentRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'status'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return TypeEquipment::class;
    }
}
