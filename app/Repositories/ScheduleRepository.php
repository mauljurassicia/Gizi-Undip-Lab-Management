<?php

namespace App\Repositories;

use App\Models\Schedule;
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
}
