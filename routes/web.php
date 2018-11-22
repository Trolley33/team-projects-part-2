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

Route::get('/', 'PagesController@index');
Route::get('/login', 'PagesController@login');
Route::get('/FAQ', 'PagesController@FAQ');

Route::get('/verify', function()
{
    return redirect('login');
});
Route::get('/operator/homepage', 'PagesController@operator_homepage');
Route::get('/register', 'PagesController@register');

Route::post('/verify', 'PagesController@verify');
Route::post('/register', 'PagesController@registerPOST');
