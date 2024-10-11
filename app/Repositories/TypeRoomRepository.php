<?php

namespace App\Repositories;

use App\Models\TypeRoom;
use Webcore\Generator\Common\BaseRepository;

/**
 * Class TypeRoomRepository
 * @package App\Repositories
 * @version October 9, 2024, 3:06 am UTC
 *
 * @method TypeRoom findWithoutFail($id, $columns = ['*'])
 * @method TypeRoom find($id, $columns = ['*'])
 * @method TypeRoom first($columns = ['*'])
*/
class TypeRoomRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'status'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return TypeRoom::class;
    }
}
