<?php

namespace App\Repositories;

use App\Models\Borrowing;
use Carbon\Carbon;
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


    public function getQuantityByRoomAndEquipment($room, $equipment, $startDate, $endDate)
    {
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

    public function getQuantityByRoomAndEquipmentExceptId($room, $equipment, $startDate, $endDate, $id)
    {
        return $this->model
            ->selectRaw('SUM(quantity) as total_quantity')
            ->where('room_id', $room)
            ->where('id', '!=', $id)
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

    public function getBorrowingCountPerDayThisWeek()
    {
        $dateTrans = [
            'Sunday' => 'minggu',
            'Monday' => 'senin',
            'Tuesday' => 'selasa',
            'Wednesday' => 'rabu',
            'Thursday' => 'kamis',
            'Friday' => 'jumat',
            'Saturday' => 'sabtu',
        ];

        $end = Carbon::now('Asia/Jakarta')->endOfDay();
        $start = $end->copy()->subDays(6)->startOfDay();

        $borrowing = Borrowing::whereBetween('start_date', [$start, $end])
            ->selectRaw("DATE(start_date) as date, COUNT(*) as count")
            ->groupBy('date')
            ->get();


        $allDate = [];
        for ($i = 0; $i < 7; $i++) {
            $date = $start->copy()->addDays($i);
            $allDate[] = [
                'date' => $date->format('Y-m-d'),
                'day' => $dateTrans[$date->format('l')],
                'count' => $borrowing->where('date', $date->format('Y-m-d'))->first() ? $borrowing->where('date', $date->format('Y-m-d'))->first()->count : 0
            ];
        }

        $borrowing = collect($allDate)->sortBy('date');

        return $borrowing;
    }

    public function getBorrowingCountPerDayThisMonth()
    {
        $end = Carbon::now('Asia/Jakarta')->endOfDay();
        $start = $end->copy()->subDays(30)->startOfDay();

        $borrowing = Borrowing::whereBetween('start_date', [$start, $end])
            ->selectRaw("DATE(start_date) as date, COUNT(*) as count")
            ->groupBy('date')
            ->get();

        $allDate = [];
        for ($i = 0; $i < 30; $i++) {
            $date = $end->copy()->subDays($i);
            $allDate[] = [
                'date' => $date->format('Y-m-d'),
                'count' => $borrowing->where('date', $date->format('Y-m-d'))->first() ? $borrowing->where('date', $date->format('Y-m-d'))->first()->count : 0
            ];
        }

        $borrowing = collect($allDate)->sortBy('date');

        return $borrowing;
    }


    public function getBorrowingCountPerMonthThisYear(){
        $end = Carbon::now('Asia/Jakarta')->endOfDay();
        $start = $end->copy()->subDays(365)->startOfDay();
        
        $borrowing = Borrowing::whereBetween('start_date', [$start, $end])
            ->selectRaw("MONTH(start_date) as month, COUNT(*) as count")
            ->groupBy('month')
            ->get();
        
        $allMonth = [];
        for ($i = 0; $i < 12; $i++) {
            $month = $end->copy()->subMonths($i);
            $allMonth[] = [
                'month' => $month->format('M'),
                'month_index' => $month,
                'count' => $borrowing->where('month', $month->format('m'))->first() ? $borrowing->where('month', $month->format('m'))->first()->count : 0
            ];
        }
        
        $borrowing = collect($allMonth)->sortBy('month_index');
        
        return $borrowing;
    }
}
