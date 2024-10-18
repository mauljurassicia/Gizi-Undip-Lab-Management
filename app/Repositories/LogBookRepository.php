<?php

namespace App\Repositories;

use App\Models\LogBook;
use Webcore\Generator\Common\BaseRepository;

/**
 * Class LogBookRepository
 * @package App\Repositories
 * @version October 18, 2024, 6:36 am UTC
 *
 * @method LogBook findWithoutFail($id, $columns = ['*'])
 * @method LogBook find($id, $columns = ['*'])
 * @method LogBook first($columns = ['*'])
*/
class LogBookRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'user_id',
        'room_id',
        'type',
        'time',
        'report'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return LogBook::class;
    }
}
