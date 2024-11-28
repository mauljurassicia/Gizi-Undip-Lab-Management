<?php

namespace App\Repositories;

use App\User;
use Webcore\Generator\Common\BaseRepository;

/**
 * Class UserRepository
 * @package App\Repositories
 * @version March 30, 2021, 4:39 am UTC
 *
 * @method User findWithoutFail($id, $columns = ['*'])
 * @method User find($id, $columns = ['*'])
 * @method User first($columns = ['*'])
*/
class UserRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'email',
        'identity_number',
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return User::class;
    }

    public function getTeacherCount(){
        return User::whereHas('roles', function ($query) {
            $query->where('name', 'teacher'); 
        })->count();
    }

    public function getStudentCount(){
        return User::whereHas('roles', function ($query) {
            $query->where('name', 'student'); 
        })->count();
    }

    public function getGuestCount(){
        return User::whereHas('roles', function ($query) {
            $query->where('name', 'guest'); 
        })->count();
    }

    public function getLaborantCount(){
        return User::whereHas('roles', function ($query) {
            $query->where('name', 'laborant'); 
        })->count();
    }

    public function getTotalCountExceptAdmin(){
        return User::whereHas('roles', function ($query) {
            $query->where('name', '!=', 'administrator'); 
        })->count();
    }
}
