<?php

namespace App\Repositories;

use App\Models\Schedule;
use Carbon\Carbon;
use Webcore\Generator\Common\BaseRepository;

/**
 * Class ScheduleRepository
 * @package App\Repositories
 * @version October 18, 2024, 3:42 am UTC
 *
 * @method Schedule findWithoutFail($id, $columns = ['*'])
 * @method Schedule find($id, $columns = ['*'])
 * @method Schedule first($columns = ['*'])
 */
class ScheduleRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'room_id',
        'userable_type',
        'userable_id',
        'name',
        'start_schedule',
        'end_schedule',
        'course_id'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Schedule::class;
    }

    public function getScheduleCountPerDayThisWeek()
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
        $schedules = Schedule::whereBetween('start_schedule', [$start, $end])
            ->selectRaw("DATE(start_schedule) as date, COUNT(*) as count")
            ->groupBy('date')
            ->get();

        $allDate = [];
        for ($i = 0; $i < 7; $i++) {
            $date = $start->copy()->addDays($i);
            $allDate[] = [
                'date' => $date->format('Y-m-d'),
                'day' => $dateTrans[$date->format('l')],
                'count' => $schedules->where('date', $date->format('Y-m-d'))->first() ? $schedules->where('date', $date->format('Y-m-d'))->first()->count : 0
            ];
        }

        $schedules = collect($allDate)->sortBy('date');

        return $schedules;
    }

    public function getScheduleCountPerDayThisMonth()
    {
        $end = Carbon::now('Asia/Jakarta')->endOfDay();
        $start = $end->copy()->subDays(30)->startOfDay();

        $schedules = Schedule::whereBetween('start_schedule', [$start, $end])
            ->selectRaw('DATE(start_schedule) as date, COUNT(*) as count')
            ->groupBy('date')
            ->get();

        $allDate = [];
        for ($i = 0; $i < 30; $i++) {
            $date = $end->copy()->subDays($i);
            $allDate[] = [
                'date' => $date->format('Y-m-d'),
                'count' => $schedules->where('date', $date->format('Y-m-d'))->first() ? $schedules->where('date', $date->format('Y-m-d'))->first()->count : 0
            ];
        }

        $schedules = collect($allDate)->sortBy('date');

        return $schedules;
    }

    public function getScheduleCountPerMonthThisYear()
    {
        $end = Carbon::now('Asia/Jakarta')->endOfDay();
        $start = $end->copy()->subDays(365);

        $schedules = Schedule::whereBetween('start_schedule', [$start, $end])
            ->selectRaw('MONTH(start_schedule) as month, COUNT(*) as count')
            ->groupBy('month')
            ->get();

        $allMonth = [];
        for ($i = 0; $i < 12; $i++) {
            $month = $end->copy()->subMonths($i);
            $allMonth[] = [
                'month' => $month->format('M'),
                'month_index' => $month,
                'count' => $schedules->where('month', $month->format('m'))->first() ? $schedules->where('month', $month->format('m'))->first()->count : 0
            ];
        }

        $schedules = collect($allMonth)->sortBy('month_index');

        return $schedules;
    }
}
