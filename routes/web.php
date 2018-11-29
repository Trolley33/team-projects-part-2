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

// Operator pages.
Route::get('/operator/', 'PagesController@operator_homepage');
Route::get('/operator/caller-info', 'PagesController@caller_info');
Route::get('/operator/caller-info/{name}', ['uses' => 'PagesController@caller_info_from_name']);
//Route::get('/operator/view-problems', 'PagesController@view_problems');
//Route::get('/operator/log-call', 'PagesController@log_call');
//Route::get('/operator/view-single-problem/{id}', ['uses' => 'PagesController@view_problem']);


// Specialist pages.
Route::get('/specialist/', 'PagesController@specialist_homepage');
//Route::get('/specialist/view-problems', 'PagesController@view_problems');

// Analyst pages.
Route::get('/analyst/', 'PagesController@analyst_homepage');

// Problem pages.
Route::get   ('/problems/create','ProblemController@create')->name('pages.problems.create');
Route::get   ('/problems/{id}/edit', 'ProblemController@edit')->name('pages.problems.edit');
Route::put   ('/problems/{id}', 'ProblemController@update')->name('pages.problems.update');
Route::get   ('/problems/{id}', 'ProblemController@show')->name('pages.problems.show');
Route::post  ('/problems', 'ProblemController@store')->name('pages.problems.store');
Route::get   ('/problems','ProblemController@index')->name('pages.problems.index');
Route::delete('/problems/{id}', 'ProblemController@destroy')->name('pages.problems.destroy');

// Post routes. =================================
Route::post('/verify', 'PagesController@verify');



// User routes. =============================
Route::get   ('/users/create/caller','UserController@create_caller')->name('users.create_caller');

Route::get   ('/users/create/tech-support','UserController@create_tech_support')->name('users.create_tech_support');

Route::get   ('/users/{id}/edit', 'UserController@edit')->name('users.edit');
Route::put   ('/users/{id}', 'UserController@update')->name('users.update');
Route::get   ('/users/{id}', 'UserController@show')->name('users.show');
Route::post  ('/users', 'UserController@store')->name('users.store');
Route::get   ('/users','UserController@index')->name('users.index');
Route::delete('/users/{id}', 'UserController@destroy')->name('users.destroy');

