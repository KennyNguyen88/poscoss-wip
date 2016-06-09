<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', ['as' => 'wip-close','uses' => 'PageController@index']);

Route::get('/search', ['as' => 'wip-close-search','uses' => 'PageController@search']);

Route::get('/search/ifStatus/{fromDate}/{toDate}', ['as' => 'wip-close-searchIfStatus','uses' => 'PageController@searchIfStatus']);

Route::get('/search/oit', ['as' => 'wip-close-searchIfStatus','uses' => 'PageController@searchOIT']);

Route::get('/search/oit/check', ['as' => 'wip-close-searchIfStatus','uses' => 'PageController@searchOITCheck']);

Route::get('/step/{fromDate}/{toDate}', ['as' => 'wip-close-step','uses' => 'PageController@step']);

Route::get('/step/{id}/{fromDate}/{toDate}/{update}', ['as' => 'wip-close-step-id','uses' => 'PageController@step_id']);

Route::get('/ifNotSend/{fromDate}/{toDate}', ['as' => 'wip-close-ifNotSend','uses' => 'PageController@ifNotSend']);

Route::get('/minus/{fromDate}', ['as' => 'wip-close-minus','uses' => 'PageController@minus']);

//Route::get('/prdRsl/{fromDate}', ['as' => 'wip-close-prdRsl','uses' => 'ProductionResultController@index']);

Route::get('/prdRsl', ['as' => 'wip-close-prdRsl-index','uses' => 'ProductionResultController@index']);

Route::get('/prdRsl/search/', ['as' => 'wip-close-prdRsl-search','uses' => 'ProductionResultController@search']);

Route::get('/onhand', ['as' => 'onhand','uses' => 'OnhandController@index']);

Route::get('/onhand/search/{chain}/{dateCheck}/{lotno}', ['as' => 'onhand-search','uses' => 'OnhandController@search']);

//ext id detail

Route::get('/test', ['as' => 'test','uses' => 'PageController@test']);

