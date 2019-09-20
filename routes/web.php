<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/
Route::options(
  '/{any:.*}', 
  [
    function (){ 
      return response(['status' => 'success']); 
    }
  ]
);

Route::group(['prefix' => 'api'], function () use ($router) {
  //Auth
  Route::post('/login', 'UserController@login');
  Route::get('/logout', 'UserController@logout');

  //Check
  Route::get('/healthCheck', 'UserController@check');

 //Activity
 Route::get('/activities', 'ActivityController@getActivities');

  // Photo
  //get
  Route::get('/photos/get', 'PhotoController@reponse');
  Route::post('/photos/post', 'PhotoController@store');


  Route::group(['middleware' => 'auth'], function () use ($router) {
    //User
    Route::put('/user/signup', 'UserController@postNewClient');
    Route::get('/user', 'UserController@getUserContext');
    Route::post('/user', 'UserController@upsertUser');
  });
});
