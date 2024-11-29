<?php

namespace App\Repositories;

use App\Models\BrokenEquipment;
use Webcore\Generator\Common\BaseRepository;

/**
 * Class BrokenEquipmentRepository
 * @package App\Repositories
 * @version November 15, 2024, 4:42 am UTC
 *
 * @method BrokenEquipment findWithoutFail($id, $columns = ['*'])
 * @method BrokenEquipment find($id, $columns = ['*'])
 * @method BrokenEquipment first($columns = ['*'])
*/
class BrokenEquipmentRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'room_id',
        'user_id',
        'equipment_id',
        'quantity',
        'broken_date',
        'return_date'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return BrokenEquipment::class;
    }

    public function getBrokenEquipmentCount() {
        return $this->model->sum('quantity');
    }
}
