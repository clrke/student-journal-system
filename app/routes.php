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

use Carbon\Carbon;

Route::get('/', function()
{
	$days = [];
	for($day = Carbon::now()->hour(0)->minute(0)->second(0); $day > Carbon::now()->subWeeks(2); $day->subDay())
		array_push($days, clone($day));

	$timelines = Timeline::all()->lists('timeline', 'id');
	$subjects = Timeline::current()->subjects;
	$subjectsList = Timeline::current()->subjects->lists('subject', 'id');
	$week = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
	$daysOfWeek = [];
	for ($i=date("N"); $i < 7; $i++) { 
		$daysOfWeek[$i] = $week[$i];
	}	
	for ($i=0; $i < date("N"); $i++) {
		$daysOfWeek[$i] = $week[$i];
	}
	$debug = false;

	return View::make('index', compact('timelines', 'subjects', 'subjectsList', 'days', 'daysOfWeek', 'debug'));
});

Route::get('/debug', function()
{
	$days = [];

	for($day = Carbon::now()->hour(0)->minute(0)->second(0); $day > Carbon::now()->subWeeks(2); $day->subDay())
		array_push($days, clone($day));

	$timelines = Timeline::all()->lists('timeline', 'id');
	$subjects = Timeline::current()->subjects;
	$subjectsList = Timeline::current()->subjects->lists('subject', 'id');
	$week = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
	$daysOfWeek = [];
	for ($i=date("N"); $i < 7; $i++) { 
		$daysOfWeek[$i] = $week[$i];
	}
	for ($i=0; $i < date("N"); $i++) {
		$daysOfWeek[$i] = $week[$i];
	}
	$debug = true;

	return View::make('index', compact('timelines', 'subjects', 'subjectsList', 'days', 'daysOfWeek', 'debug'));
});

Route::get('timelines', function()
{
	return Timeline::all();
});

Route::post('timelines/current', function()
{
	Timeline::whereFlag(1)->update(['flag' => 0]);
	Timeline::whereId(Input::get('timeline'))->update(['flag' => 1]);
	return Redirect::back();
});

Route::post('timelines', function()
{
	Timeline::whereFlag(1)->update(['flag' => 0]);
	Timeline::create(Input::all());
	return Redirect::to('/debug');
});

Route::get('timelines/current/id', function()
{
	return Timeline::current()->id;
});

Route::get('subjects', function()
{
	return Timeline::current()->subjects()->with('schedules')->get();
});

Route::post('subjects', function()
{
	return Subject::create(Input::all());
});

Route::get('subjects/{id}', function($id)
{
	return Subject::find($id)->subject;
});

Route::post('subjects/{id}/edit', function($id)
{
	return Subject::find($id)->update(Input::all());
});

Route::get('subjects/{id}/activities/{day}', function($id, $day)
{
	$activity = Activity::whereHappenedAt($day)->whereSubjectId($id)->first();

	return $activity ? $activity->activity : '';
});
Route::post('subjects/{id}/activities/{day}', function($id, $day)
{
	if($activity = Activity::whereHappenedAt($day)->whereSubjectId($id)->first())
		$activity->update(Input::all());
	else
		$activity = Activity::create(Input::all());

	return $activity;
});
Route::resource('schedules', 'SchedulesController');

Route::get('diffForHumans/{date}', function($date)
{
	$date = new Carbon($date);

	$diffInHours = $date->diffInHours(Carbon::now(), false);
	
	if($diffInHours <= 24)
	{
		if($diffInHours >= 0)
			return 'Today';
		else
			return $date->addDay(1)->diffForHumans();
	}
	else return $date->diffForHumans();
});

Route::get('deadlines', function()
{
	return Timeline::current()->deadlines()->with('subject')->with('checklists')->get();
});

Route::post('deadlines', function()
{
	return Deadline::create(Input::all());
});

Route::delete('deadlines/{id}', function($id) 
{
	return Deadline::destroy($id);
});

Route::get('/questions/add', function()
{
	$subjects = Timeline::current()->subjects()->lists('subject', 'id');
	return View::make('question', compact('subjects'));
});

Route::get('/questions', function()
{
	return Question::with('answers')->with('sabotages')->get();
});

Route::post('/questions', function()
{	
	$question = Question::create(Input::all());

	foreach(Input::get('answers') as $answer)
		if(isset($answer['answer']))
			Answer::create(['question_id' => $question->id, 'answer' => $answer['answer'], 'correct' => 1]);
	
	foreach(Input::get('sabotages') as $sabotage)
		if(isset($sabotage['answer']))
			Answer::create(['question_id' => $question->id, 'answer' => $sabotage['answer'], 'correct' => 0]);	

	return Question::whereId($question->id)->with('answers')->first();
});

Route::get('/quotes', function()
{
	return File::get('public/quotes.json');
});

Route::get('/activities', function()
{
	return Activity::with('subject')->get();
});

