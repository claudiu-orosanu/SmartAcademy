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

    Route::middleware('auth:api')->get('/user', function (Request $request) {
        return $request->user();
    });

    Route::apiResource('courses', 'CourseController');
    Route::apiResource('sections', 'SectionController');
    Route::apiResource('videos', 'VideoController');
    Route::apiResource('documents', 'DocumentController');

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


