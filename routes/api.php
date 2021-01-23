<?php
use Illuminate\Http\Request;

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
/**
 * 吃喝
 */
Route::group(['namespace' => 'Api\ChiHe', 'prefix' => 'chiheyouhui'], function ($router) {
    Route::get('/project', 'PublicController@project');
    Route::post('/login', 'PublicController@login');
    Route::get('/send-msg', 'PublicController@sendMsg');
    Route::get('/link', 'LinkController@index');


    Route::group([
        'middleware' => [\App\Http\Middleware\ChiHe\UserAuth::class],
    ], function () {
        Route::post('/add-subscribe', 'IndexController@addSubscribe');
        Route::post('/add-coupon-log', 'IndexController@addCouponLog');
    });
});
