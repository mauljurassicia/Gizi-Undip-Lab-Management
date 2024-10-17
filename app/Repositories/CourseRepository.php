<?php

namespace App\Repositories;

use App\Models\Course;
use Webcore\Generator\Common\BaseRepository;

/**
 * Class CourseRepository
 * @package App\Repositories
 * @version October 13, 2024, 3:38 am UTC
 *
 * @method Course findWithoutFail($id, $columns = ['*'])
 * @method Course find($id, $columns = ['*'])
 * @method Course first($columns = ['*'])
*/
class CourseRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'description',
        'banner',
        'thumbnail'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Course::class;
    }
}