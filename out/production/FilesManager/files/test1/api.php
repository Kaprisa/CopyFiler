<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/register', 'Auth\RegisterController@register');
Route::post('/login', 'Auth\LoginController@login');
Route::post('/logout', 'Auth\LoginController@logout');

Route::post('/files/{dir}/upload', 'ApiController@upload');
Route::post('/files/{dir}/remove', 'ApiController@removeFile');
Route::post('/video/{dir}', 'ApiController@upload_video');
Route::post('/question/{id}/answer', 'ApiController@answer');
Route::post('/lesson/{id}/answers', 'ApiController@getAnswers');

Route::get('/courses/add/info', 'CoursesController@getAddFormInfo');

Route::post('/categories/add', 'CategoriesController@add');
Route::get('/categories', 'CategoriesController@getAll');
Route::delete('/categories/{id}', 'CategoriesController@delete');

Route::post('/courses/add', 'CoursesController@add');
Route::get('/courses', 'CoursesController@getAll');
Route::get('/course/{id}', 'CoursesController@get');
Route::delete('/courses/{id}', 'CoursesController@delete');

Route::post('/courses/{id}/blocks/save', 'BlocksController@save');
Route::get('/block/{id}', 'BlocksController@get');
Route::delete('/blocks/{id}', 'BlocksController@delete');

Route::post('/blocks/{id}/lessons/save', 'LessonsController@save');
Route::get('/lesson/{id}', 'LessonsController@get');
Route::delete('/lessons/{id}', 'LessonsController@delete');

Route::post('/sql/query', 'DataController@sql_query');
Route::post('/php/query', 'DataController@php_query');
//Route::get('/tables', 'DataController@getTables');

Route::get('/user', 'UsersController@get');
Route::get('/users', 'UsersController@getAll');
Route::post('/user/avatar', 'UsersController@avatar');
Route::post('/user/test', 'UsersController@test');
Route::put('/user/profile', 'UsersController@profile');
Route::post('/user/courses/add', 'UsersController@addCourse');
Route::delete('/user/courses/{id}', 'UsersController@removeCourse');

Route::get('/roles', 'SecurityController@roles');
Route::post('/roles/save', 'SecurityController@saveRole');
Route::delete('/roles/{id}', 'SecurityController@removeRole');

Route::post('/tasks/{category_id}/add', 'TasksController@add');
Route::get('/task/categories', 'TasksController@getCategories');
Route::delete('/tasks/{id}', 'TasksController@delete');

Route::get('/tags', 'ApiController@tags');

Route::get('/settings', 'SettingsController@getAll');
Route::post('/settings/save', 'SettingsController@save');
Route::delete('/settings/{id}', 'SettingsController@delete');

Route::get('/themes', 'ThemesController@getAll');
Route::delete('/themes/{id}', 'ThemesController@delete');
Route::post('/themes/save', 'ThemesController@save');
Route::post('/{user}/themes/save', 'ThemesController@save');

Route::get('/directories', 'FilesController@getDirectories');
Route::post('/files/read', 'FilesController@getFile');
Route::post('/files/remove', 'FilesController@removeFile');

Route::group(['middleware' => 'auth:api'], function() {
    Route::get('/check', function() {
        $user = Auth::guard('api')->user();
        return response()->json($user, 200);
    });
});
