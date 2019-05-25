<?php

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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::post('/login', 'UserController@login');
Route::get('/home', 'HomeController@index')->name('home');

Route::group(['prefix' => 'emails'], function () {
    Route::get('/', 'EmailController@store');
    Route::get('/search', 'EmailController@search');
    Route::post('/remove/{id}', 'EmailController@destroy')
        ->where('id', '[0-9]+');
    Route::post('/create', 'EmailController@create');

    Route::get('/{id}/content', 'EmailController@getContent')
        ->where('id', '[0-9]+');
    Route::get('/{id}/url_details', 'EmailController@getUrlDetail')
        ->where('id', '[0-9]+');
    Route::get('/{id}/smtp_details', 'EmailController@getSMTPDetail')
        ->where('id', '[0-9]+');

    Route::post('/send', 'EmailController@sendEmail');

});

Route::get('/emails/tracking', function () {
    $emails = \App\Models\Email::all();
    return view('vendor.emailTrakingViews.index', ['emails' => $emails]);
});

Route::group(['prefix' => 'sms'], function () {
   Route::get('/', 'SMSController@store');
   Route::get('/{id}', 'SMSController@info');
   Route::post('/create', 'SMSController@create');
});

Route::group(['prefix' => 'users'], function () {
    Route::get('/', 'UserController@store');
    Route::get('/search', 'UserController@search');
    Route::get('/info/{id}', 'UserController@info');
    Route::get('/{id}', 'UserController@edit');
    Route::post('/create', 'UserController@create');
    Route::post('/delete/{id}', 'UserController@remove')
        ->where('id', '[0-9]+');
    Route::post('/update/{id}', 'UserController@update')
        ->where('id', '[0-9]+');;
});