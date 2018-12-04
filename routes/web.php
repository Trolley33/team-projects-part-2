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
Route::get('/login', 'PagesController@login')->name('login');
Route::get('/logout', 'PagesController@logout');
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

// Department routes. =============================
Route::get   ('/departments/create','DepartmentController@create')->name('departments.create');

Route::get   ('/departments/{id}/edit', 'DepartmentController@edit')->name('departments.edit');
Route::put   ('/departments/{id}', 'DepartmentController@update')->name('departments.update');
Route::get   ('/departments/{id}', 'DepartmentController@show')->name('departments.show');
Route::post  ('/departments', 'DepartmentController@store')->name('departments.store');
Route::get   ('/departments','DepartmentController@index')->name('departments.index');
Route::delete('/departments/{id}', 'DepartmentController@destroy')->name('departments.destroy');

// Job routes. =============================
Route::get   ('/jobs/create','JobController@create')->name('jobs.create');

Route::get   ('/jobs/{id}/edit', 'JobController@edit')->name('jobs.edit');
Route::put   ('/jobs/{id}', 'JobController@update')->name('jobs.update');
Route::get   ('/jobs/{id}', 'JobController@show')->name('jobs.show');
Route::post  ('/jobs', 'JobController@store')->name('jobs.store');
Route::get   ('/jobs','JobController@index')->name('jobs.index');
Route::delete('/jobs/{id}', 'JobController@destroy')->name('jobs.destroy');

