<?php

namespace App\Repositories;

use App\Models\CoverLetter;
use Webcore\Generator\Common\BaseRepository;

/**
 * Class CoverLetterRepository
 * @package App\Repositories
 * @version December 3, 2024, 3:16 am UTC
 *
 * @method CoverLetter findWithoutFail($id, $columns = ['*'])
 * @method CoverLetter find($id, $columns = ['*'])
 * @method CoverLetter first($columns = ['*'])
*/
class CoverLetterRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'cover_letterable_type',
        'cover_letterable_id',
        'image'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return CoverLetter::class;
    }
}
