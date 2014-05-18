<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/', function()
{
	$timelines = Timelines::all()->lists('timeline', 'id');
	return View::make('index', compact('timelines'));
});

Route::get('timelines', function()
{
	return Timelines::all();
});
Route::get('timelines/current/id', function()
{
	return Timelines::current()->id;
});

Route::get('subjects', function()
{
	return Subjects::all();
});

Route::post('subjects', function()
{
	return Subjects::create(Input::all());
});

Route::post('subjects/{id}/edit', function($id)
{
	return Subjects::update(Input::all());
});