@extends('layouts.master')

@section('content')
	<div class="col-md-4 panel panel-default">
		<h1> Current Timeline</h1>
		<form ng-submit="changeTimeline()" class="form">
			{{ Form::select('timeline', $timelines, Timelines::current(), ['class' => 'form-control'])}}
		</form>
		<h1> Subjects</h1>
		<div>
			<ul id="subjects" class="list-group">
				<li class="list-group-item" ng-repeat="subject in subjects">
					<span class="current-text">
						@{{ subject.subject }}
					</span>
				</li>
			</ul>
		</div>
		<div>
			<form ng-submit="addSubject()" class="form">
				<div class="form-group">
					{{ Form::text('text', null, ['class' => 'form-control', 'placeholder' => 'Subject Name', 'ng-model' => 'newSubject'])}}
					
				</div>
				{{ Form::submit('Add Subject', ['class' => 'form-control btn btn-primary'])}}
			</form>
		</div>
	</div>
	<div class="col-md-8 panel panel-default">
		<h1> Subjects</h1>
		<div class="col-md-8">
			<ul id="subjects" class="list-group">
				<li class="list-group-item" ng-repeat="subject in subjects">
					<span class="current-text">
						@{{ subject.subject }}
					</span>
				</li>
			</ul>
		</div>

		<div class="col-md-4">
			<form ng-submit="addSubject()" class="form">
				<div class="form-group">
					{{ Form::text('text', null, ['class' => 'form-control', 'placeholder' => 'Subject Name', 'ng-model' => 'newSubject'])}}
					
				</div>
				{{ Form::submit('Add Subject', ['class' => 'form-control btn btn-primary'])}}
			</form>
		</div>
	</div>
@stop

@section('scripts')
	{{ HTML::script('/js/jquery.min.js')}}
	{{ HTML::script('/js/angular.min.js')}}
	{{ HTML::script('/js/main_jquery.js')}}
	{{ HTML::script('/js/journal.js')}}
@stop