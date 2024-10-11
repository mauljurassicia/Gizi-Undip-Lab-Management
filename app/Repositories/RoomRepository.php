<?php

namespace App\Repositories;

use App\Models\Room;
use Webcore\Generator\Common\BaseRepository;

/**
 * Class RoomRepository
 * @package App\Repositories
 * @version October 9, 2024, 3:03 am UTC
 *
 * @method Room findWithoutFail($id, $columns = ['*'])
 * @method Room find($id, $columns = ['*'])
 * @method Room first($columns = ['*'])
*/
class RoomRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'type_room_id',
        'volume',
        'status',
        'pic_id'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Room::class;
    }
}
