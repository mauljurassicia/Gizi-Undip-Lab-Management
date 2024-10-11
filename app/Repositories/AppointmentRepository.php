<?php

namespace App\Repositories;

use App\Models\Appointment;
use Webcore\Generator\Common\BaseRepository;

/**
 * Class AppointmentRepository
 * @package App\Repositories
 * @version October 9, 2024, 3:12 am UTC
 *
 * @method Appointment findWithoutFail($id, $columns = ['*'])
 * @method Appointment find($id, $columns = ['*'])
 * @method Appointment first($columns = ['*'])
*/
class AppointmentRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'room_id',
        'appointee_id',
        'appointee_type'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Appointment::class;
    }
}
