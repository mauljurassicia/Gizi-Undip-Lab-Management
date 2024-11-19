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


    public function getQuantityByRoomAndEquipment($room, $equipment, $startDate, $endDate) {
        return $this->model
            ->selectRaw('SUM(quantity) as total_quantity')
            ->where('room_id', $room)
            ->where('equipment_id', $equipment)
            ->where(function ($query) use ($startDate, $endDate) {
                $query->where(function ($query) use ($startDate, $endDate) {
                    $query->where('start_date', '<=', $startDate)
                        ->where('end_date', '>=', $startDate);
                })->orWhere(function ($query) use ($startDate, $endDate) {
                    $query->where('start_date', '<=', $endDate)
                        ->where('end_date', '>=', $endDate);
                })->orWhere(function ($query) use ($startDate, $endDate) {
                    $query->where('start_date', '>=', $startDate)
                        ->where('end_date', '<=', $endDate);
                });
            })
            ->value('total_quantity') ?? 0;
    }
}
