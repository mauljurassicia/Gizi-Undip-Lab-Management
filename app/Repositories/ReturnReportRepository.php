<?php

namespace App\Repositories;

use App\Models\ReturnReport;
use Webcore\Generator\Common\BaseRepository;

/**
 * Class ReturnReportRepository
 * @package App\Repositories
 * @version November 15, 2024, 4:42 am UTC
 *
 * @method ReturnReport findWithoutFail($id, $columns = ['*'])
 * @method ReturnReport find($id, $columns = ['*'])
 * @method ReturnReport first($columns = ['*'])
*/
class ReturnReportRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'room_id',
        'user_id',
        'equipment_id',
        'quantity',
        'price',
        'return_date',
        'additional'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return ReturnReport::class;
    }

    public function getReturnReportCount() {
        return $this->model->sum('quantity');
    }
}
