<?php

namespace App\Repositories;

use App\Models\Laborant;
use Webcore\Generator\Common\BaseRepository;

/**
 * Class LaborantRepository
 * @package App\Repositories
 * @version October 18, 2024, 3:47 am UTC
 *
 * @method Laborant findWithoutFail($id, $columns = ['*'])
 * @method Laborant find($id, $columns = ['*'])
 * @method Laborant first($columns = ['*'])
*/
class LaborantRepository extends BaseRepository
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
        return Laborant::class;
    }
}
