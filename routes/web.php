<?php
if (env('APP_ENV') === 'production') {
	URL::forceSchema('https');
}
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

//route to get the homepage. instead of the default laravel one this points to the welcome view
Route::get('/', function () {
	return view('welcome');
});

Auth::routes();
//route to log the user out
Route::get('/logout', '\App\Http\Controllers\Auth\LoginController@logout');
//account route to get the account view
Route::get('/account', 'AccountController@index')->name('account');
//edit account route to get the page
//edit account route to post information to the database
Route::get('/edit_account', 'EditAccountController@index')->name('edit_account');
Route::post('/edit_account', 'EditAccountController@update');
//admin controls route to get the page
//admin controls route to post pathogens to the database
//admin controls route to post food to the database
//admin controls route to post user levels to the database (promote and demote)
Route::get('/admin_controls', 'AccountController@adminControls')->name('admin_controls');
Route::post('/admin_controls/pathogen', 'AccountController@addPathogen')->name('admin_controls/pathogen');
Route::post('/admin_controls/food', 'AccountController@addFood')->name('admin_controls/food');
Route::post('/admin_controls/promote', 'AccountController@promote')->name('admin_controls/promote');
Route::post('/admin_controls/demote', 'AccountController@demote')->name('admin_controls/demote');
Route::post('/admin_controls/delete_pathogen', 'AccountController@deletePathogen')->name('admin_controls/delete_pathogen');
Route::post('/admin_controls/delete_food', 'AccountController@deleteFood')->name('admin_controls/delete_food');
Route::post('/admin_controls/editfood', 'AccountController@editFood')->name('admin_controls/editfood');
Route::post('/admin_controls/editpathogen', 'AccountController@editPathogen')->name('admin_controls/editpathogen');
//simulations route to get to the page
Route::get('/simulations', 'SimulationsController@index');
Route::post('getTemperatures', 'SimulationsController@getTemperatures')->name('getTemperatures');
Route::post('updateTotalSimsRun', 'SimulationsController@updateTotalSimsRun')->name('updateTotalSimsRun');
Route::post('saveSimulation', 'SimulationsController@saveSimulation')->name('saveSimulation');
Route::get('/saved_simulations', 'SimulationsController@getSavedSimulations')->name('saved_simulation');
Route::get('delete_saved_sim', 'SimulationsController@deleteSavedSimulations')->name('delete_saved_sim');
Route::post('collectData', 'SimulationsController@collectData')->name('collectData');