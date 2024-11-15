<?php

namespace App\Repositories;

use App\Models\Borrowing;
use Webcore\Generator\Common\BaseRepository;

/**
 * Class BorrowingRepository
 * @package App\Repositories
 * @version November 15, 2024, 4:37 am UTC
 *
 * @method Borrowing findWithoutFail($id, $columns = ['*'])
 * @method Borrowing find($id, $columns = ['*'])
 * @method Borrowing first($columns = ['*'])
*/
class BorrowingRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
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
     * Configure the Model
     **/
    public function model()
    {
        return Borrowing::class;
    }
}
