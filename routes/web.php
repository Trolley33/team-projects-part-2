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

// Get routes. ==================================
Route::get('/', 'PagesController@index');
Route::get('/login', 'PagesController@login');
Route::get('/FAQ', 'PagesController@FAQ');

Route::get('/verify', function()
{
    return redirect('login');
});
Route::get('/register', 'PagesController@register');

// Operator pages.
Route::get('/operator/', 'PagesController@operator_homepage');
Route::get('/operator/homepage', 'PagesController@operator_homepage');
Route::get('/operator/caller-info', 'PagesController@caller_info');
Route::get('/operator/caller-info/{name}', ['uses' => 'PagesController@caller_info_from_name']);
Route::get('/operator/view-problems', 'PagesController@view_problems');
Route::get('/operator/log-call', 'PagesController@log_call');
Route::get('/operator/view-single-problem/{id}', ['uses' => 'PagesController@view_problem']);


// Specialist pages.
Route::get('/specialist/', 'PagesController@specialist_homepage');
Route::get('/specialist/homepage', 'PagesController@specialist_homepage');
Route::get('/specialist/view-problems', 'PagesController@view_problems');

// Analyst pages.
Route::get('/analyst/', 'PagesController@analyst_homepage');
Route::get('/analyst/homepage', 'PagesController@analyst_homepage');


// Problem pages.
Route::get('/problems/view-problems', 'PagesController@view_problems');


// Post routes. =================================
Route::post('/verify', 'PagesController@verify');
Route::post('/register', 'PagesController@registerPOST');



// Resource routes. =============================
Route::resource('users', 'UserController');