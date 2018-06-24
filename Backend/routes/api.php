<?php

use Illuminate\Http\Request;
use \Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

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

Route::prefix('v1')->group(function () {


    Route::prefix('auth')->group(function () {
        Route::post('/me', 'AuthController@me');
        Route::post('/logout', 'AuthController@logout');
        Route::post('/login', 'AuthController@login');
        Route::post('/register', 'AuthController@register');
        Route::post('/resetPassword', 'AuthController@resetPassword');
        Route::post('/resetPasswordCallback', 'Auth\ResetPasswordController@reset');
    });

    Route::apiResource('courses', 'CourseController');
//    Route::apiResource('sections', 'SectionController');
//    Route::apiResource('videos', 'VideoController');
//    Route::apiResource('documents', 'DocumentController');
    
    Route::post('courses/{course}/submitTest', 'CourseController@submitTest');
    Route::post('courses/{course}/enroll', 'CourseController@enroll');
    Route::post('courses/{course}/review', 'CourseController@reviewCourse');

    Route::get('users/{user}', 'UserController@show');
    Route::get('users', 'UserController@index');
    Route::post('users/{user}', 'UserController@update');
    Route::post('changePassword', 'UserController@changePassword');

    Route::get('getUnverifiedTeachers', 'UserController@getUnverifiedTeachers');
    Route::post('acceptTeacher', 'UserController@acceptTeacher');
    Route::post('declineTeacher', 'UserController@declineTeacher');

    Route::get('dashboard', 'DashboardController@getDashboardData');

    
    
    
    
    
    
    Route::get('/test', function (Request $request) {
        return response(json_encode([1, 2, 3, 4]), 200);
    });

    Route::get('/testStorage', function (Request $request) {

//        $path = storage_path('app/public/test.png');
//        return Image::make($path)->response('png');

//        return Storage::download('public/test.png');

        return Storage::url('test.png');
    });

    Route::post('/testUpload', function (Request $request) {

//        $path = $request->file('avatar')->store('avatars');
        $file = $request->file('avatar');
        $path = Storage::disk('public')->putFile('avatars', $file);

        return Storage::url($path);
    });
});


