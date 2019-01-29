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
Route::get   ('/problems/create','ProblemController@create')->name('problems.create');
Route::get   ('/problems/create/{user_id}','ProblemController@select_problem_type')->name('problems.select_problem_type');
Route::get   ('/problems/create/{user_id}/{problem_type_id}','ProblemController@add_problem_details')->name('problems.add_problem_details');
Route::post('/problems/create/{user_id}/{problem_type_id}', 'ProblemController@select_specialist_for_problem')->name('problems.select_specialist_for_problem');

Route::post('/problems/store', 'ProblemController@store')->name('problems.store');

Route::get   ('/problems/{id}/add_specialist/{specialist_id}', 'ProblemController@add_specialist')->name('problems.add_specialist');
Route::get   ('/problems/{id}/add_operator', 'ProblemController@add_operator')->name('problems.add_operator');

Route::get   ('/problems/{id}/edit', 'ProblemController@edit')->name('problems.edit');
Route::get   ('/problems/{id}/add_call/{caller_id}', 'ProblemController@add_call')->name('problems.add_call');

Route::get   ('/problems/{id}/edit_problem_type', 'ProblemController@edit_problem_type')->name('problems.edit_problem_type');
Route::post ('/problems/{id}/remove_specialist', 'ReassignmentController@request_reassignment');
Route::get   ('/problems/{problem_id}/add_problem_type/{type_id}', 'ProblemController@add_problem_type')->name('problems.add_problem_type');

Route::get   ('/problems/{id}/edit_specialist', 'ProblemController@edit_specialist')->name('problems.edit_specialist');
Route::get   ('/problems/{id}/solve/compact', 'ProblemController@solve_compact')->name('problems.solve_compact');

Route::post ('/problems/{id}/solve', 'ProblemController@solve_problem')->name('problems.solve_problem');

// Add calls to problem
Route::get   ('/problems/{id}/add_call', 'ProblemController@select_user_for_call')->name('problems.select_user_for_call');

// Equipment + problems
Route::get   ('/problems/{id}/add_equipment', 'ProblemController@select_equipment_to_add')->name('problems.select_equipment_to_add');
Route::post  ('/problems/{id}/equipment', 'ProblemController@append_equipment')->name('problems.append_equipment');

Route::get   ('/problems/{id}/remove_equipment', 'ProblemController@select_equipment_to_remove')->name('problems.select_equipment_to_remove');
Route::delete('/problems/{id}/equipment', 'ProblemController@delete_equipment')->name('problems.delete_equipment');

// Software + problems
Route::get   ('/problems/{id}/add_software', 'ProblemController@select_software_to_add')->name('problems.select_software_to_add');
Route::post  ('/problems/{id}/software', 'ProblemController@append_software')->name('problems.append_software');

Route::get   ('/problems/{id}/remove_software', 'ProblemController@select_software_to_remove')->name('problems.select_software_to_remove');
Route::delete('/problems/{id}/software', 'ProblemController@delete_software')->name('problems.delete_software');

// Normal
Route::put   ('/problems/{id}', 'ProblemController@update')->name('problems.update');
Route::get   ('/problems/{id}', 'ProblemController@show')->name('problems.show');
Route::post  ('/problems', 'ProblemController@store')->name('problems.store');
Route::get   ('/problems','ProblemController@index')->name('problems.index');
Route::delete('/problems/{id}', 'ProblemController@destroy')->name('problems.destroy');

// Post routes. =================================
Route::post('/verify', 'PagesController@verify');



// User routes. =============================
Route::get   ('/users/create/caller','UserController@create_caller')->name('users.create_caller');

Route::get   ('/users/create/tech-support','UserController@create_tech_support')->name('users.create_tech_support');

Route::get   ('/users/{id}/edit', 'UserController@edit')->name('users.edit');
Route::get ('/users/{id}/edit_specialism', 'UserController@edit_specialism')->name('users.edit_specialism');
Route::get ('/users/{user_id}/add_specialism/{pt_id}', 'UserController@add_specialism')->name('users.add_specialism');
Route::get('/users/{id}/compact', 'UserController@show_compact')->name('users.show.compact');

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

// Equipment routes. =============================
Route::get   ('/equipment/create','EquipmentController@create')->name('equipment.create');

Route::get   ('/equipment/{id}/edit', 'EquipmentController@edit')->name('equipment.edit');
Route::put   ('/equipment/{id}', 'EquipmentController@update')->name('equipment.update');
Route::get   ('/equipment/{id}', 'EquipmentController@show')->name('equipment.show');
Route::post  ('/equipment', 'EquipmentController@store')->name('equipment.store');
Route::get   ('/equipment','EquipmentController@index')->name('equipment.index');
Route::delete('/equipment/{id}', 'EquipmentController@destroy')->name('equipment.destroy');

// Software routes. =============================
Route::get   ('/software/create','SoftwareController@create')->name('Software.create');

Route::get   ('/software/{id}/edit', 'SoftwareController@edit')->name('software.edit');
Route::put   ('/software/{id}', 'SoftwareController@update')->name('software.update');
Route::get   ('/software/{id}', 'SoftwareController@show')->name('software.show');
Route::post  ('/software', 'SoftwareController@store')->name('software.store');
Route::get   ('/software','SoftwareController@index')->name('software.index');
Route::delete('/software/{id}', 'SoftwareController@destroy')->name('software.destroy');

// Speciality routes. =============================
Route::get   ('/specialities/create','SpecialityController@create')->name('specialities.create');

Route::get   ('/specialities/{id}/edit', 'SpecialityController@edit')->name('specialities.edit');
Route::put   ('/specialities/{id}', 'SpecialityController@update')->name('specialities.update');
Route::get   ('/specialities/{id}', 'SpecialityController@show')->name('specialities.show');
Route::post  ('/specialities', 'SpecialityController@store')->name('specialities.store');
Route::get   ('/specialities','SpecialityController@index')->name('specialities.index');
Route::delete('/specialities/{id}', 'SpecialityController@destroy')->name('specialities.destroy');

// Problem type routes. =============================
Route::get   ('/problem_types/create','ProblemTypeController@create')->name('problem_types.create');
Route::get('/problem_types/{id}/compact', 'ProblemTypeController@show_compact')->name('problem_types.show.compact');
Route::get   ('/problem_types/{id}/edit', 'ProblemTypeController@edit')->name('problem_types.edit');
Route::put   ('/problem_types/{id}', 'ProblemTypeController@update')->name('problem_types.update');
Route::get   ('/problem_types/{id}', 'ProblemTypeController@show')->name('problem_types.show');
Route::post  ('/problem_types', 'ProblemTypeController@store')->name('problem_types.store');
Route::get   ('/problem_types','ProblemTypeController@index')->name('problem_types.index');
Route::delete('/problem_types/{id}', 'ProblemTypeController@destroy')->name('problem_types.destroy');


// Calls routes. =============================
Route::get   ('/calls/create','CallsController@create')->name('calls.create');

Route::get   ('/calls/{id}/edit', 'CallsController@edit')->name('calls.edit');
Route::put   ('/calls/{id}', 'CallsController@update')->name('calls.update');
Route::get   ('/calls/{id}', 'CallsController@show')->name('calls.show');
Route::post  ('/calls', 'CallsController@store')->name('calls.store');
Route::get   ('/calls','CallsController@index')->name('calls.index');
Route::delete('/calls/{id}', 'CallsController@destroy')->name('calls.destroy');