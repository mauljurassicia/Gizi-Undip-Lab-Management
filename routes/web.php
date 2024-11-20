<?php

use Illuminate\Support\Facades\Route;

use League\Glide\ServerFactory;
use League\Glide\Responses\LaravelResponseFactory;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();


Route::get('clear-cache', function () {
    // Artisan::call('schedule:run');
    Artisan::call('view:clear');
    Artisan::call('cache:clear');
    Artisan::call('config:cache');
    Artisan::call('config:clear');
    Artisan::call('route:cache');
    Artisan::call('route:clear');
});

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', 'Webcore\HomeController@index')->name('dashboard');
Route::get('profile', 'Webcore\HomeController@profile')->name('profile');
Route::post('profile/submit', 'Webcore\HomeController@update_profile')->name('profile.submit');

Route::resource('permissiongroups', 'Webcore\PermissiongroupController');
Route::resource('permissions', 'Webcore\PermissionController');
Route::resource('roles', 'Webcore\RoleController');
Route::post('users/permissions', 'Webcore\UserController@permissions')->name('users.permissions');
Route::resource('users', 'Webcore\UserController');


Route::resource('equipment', 'EquipmentController');
// Route::post('importEquipment', 'EquipmentController@import');

Route::get('rooms/{room}/equipments', 'RoomController@getEquipmentByRoom')->name('rooms.equipments.table');
Route::post('rooms/{room}/equipments', 'RoomController@addEquipment')->name('rooms.equipments.store');
Route::get('rooms/equipments', 'RoomController@getEquipment')->name('rooms.equipments');
Route::resource('rooms', 'RoomController');

// Route::post('importRoom', 'RoomController@import');

Route::resource('courses', 'CourseController');
// Route::post('importCourse', 'CourseController@import');

Route::resource('teachers', 'TeacherController');
// Route::post('importTeacher', 'TeacherController@import');

Route::resource('guests', 'GuestController');
// Route::post('importGuest', 'GuestController@import');

Route::resource('students', 'StudentController');
// Route::post('importStudent', 'StudentController@import');

Route::get('groups/suggestion', 'GroupController@groupSuggestion')->name('groups.suggestion');
Route::resource('groups', 'GroupController');
Route::get('groups/members/sugestion', 'GroupController@memberSuggestion')->name('groups.members.suggestion');
Route::get('groups/{group}/members', 'GroupController@getMembers')->name('groups.members.table');
// Route::post('importGroup', 'GroupController@import');

Route::delete('schedules/{id}', 'ScheduleController@deleteSchedule')->name('schedules.delete');
Route::put('schedules/{id}', 'ScheduleController@updateSchedule')->name('schedules.update');
Route::resource('schedules', 'ScheduleController')->only(['index', 'edit', 'destroy']);
Route::get('schedules/room/{room}', 'ScheduleController@getScheduleByRoomAndDate')->name('schedules.rooms');
Route::get('schedules/room/{room}/operationalHours', 'ScheduleController@getOperationalHoursByRoomAndDate')->name('schedules.operationalHours');
Route::post('schedules/room/{room}', 'ScheduleController@addSchedule')->name('schedules.adds');
// Route::post('importSchedule', 'ScheduleController@import');

Route::resource('laborants', 'LaborantController');
// Route::post('importLaborant', 'LaborantController@import');

Route::resource('logBooks', 'LogBookController');
// Route::post('importLogBook', 'LogBookController@import');

Route::post('borrowings/add', 'BorrowingController@addBorrowing')->name('borrowings.add');
Route::get('borrowings/group', 'BorrowingController@getGroup')->name('borrowings.group');
Route::get('borrowings/getBorrowings', 'BorrowingController@getBorrowings')->name('borrowings.get');
Route::put('borrowings/{id}/update', 'BorrowingController@updateBorrowing')->name('borrowings.update');
Route::delete('borrowings/{id}/delete', 'BorrowingController@deleteBorrowing')->name('borrowings.delete');
Route::resource('borrowings', 'BorrowingController')->only(['index']);
Route::get('borrowings/{room}/equipments', 'BorrowingController@getEquipmentByRoom')->where('room', '[0-9]+',)->name('borrowings.room.equipments');
Route::get('borrowings/{room}/{equipment}/quantity', 'BorrowingController@getQuantityByRoomAndEquipment')->where('room', '[0-9]+')->where('equipment', '[0-9]+')->name('borrowings.room.equipment.quantity');
// Route::post('importBorrowing', 'BorrowingController@import');

Route::resource('brokenEquipments', 'BrokenEquipmentController');
// Route::post('importBrokenEquipment', 'BrokenEquipmentController@import');

Route::resource('returnReports', 'ReturnReportController');
// Route::post('importReturnReport', 'ReturnReportController@import');