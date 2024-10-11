<?php

namespace App\Repositories;

use App\Models\Equipment;
use Webcore\Generator\Common\BaseRepository;

/**
 * Class EquipmentRepository
 * @package App\Repositories
 * @version October 9, 2024, 2:59 am UTC
 *
 * @method Equipment findWithoutFail($id, $columns = ['*'])
 * @method Equipment find($id, $columns = ['*'])
 * @method Equipment first($columns = ['*'])
*/
class EquipmentRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'price',
        'type_id'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Equipment::class;
    }
}
