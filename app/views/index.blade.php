@extends('layouts.master')

@section('content')
	<br/>
	<br/>
	<div class="col-md-4 panel panel-default">
		<h1> Current Timeline</h1>
		<form ng-submit="changeTimeline()" class="form">
			{{ Form::select('timeline', $timelines, Timeline::current(), ['class' => 'form-control'])}}
		</form>
		<?php $first = true; ?>
		@foreach($daysOfWeek as $key => $day)
			@if( $debug || count(Timeline::current()->schedules()->whereDayOfWeek($key)->get()))
				@if($first)
					<h1> <b> {{$day}} </b> </h1>
				@else
					<h3> {{$day}} </h3>
				@endif
				<div class= "panel panel-default">
					<ul class="list-group">
						@foreach(Timeline::current()->schedules()->whereDayOfWeek($key)->get() as $schedule)
							<li class="list-group-item">
								{{ $schedule->subject->subject }}
							</li>
						@endforeach
						@if($debug)
							<li class="list-group-item">
								{{ Form::open(['route' => 'schedules.store', 'class' => 'form'])}}
									{{ Form::select('subject_id', $subjectsList, null, ['class' => 'form-control'])}}
									{{ Form::hidden('day_of_week', $key)}}
									{{ Form::submit('Add Subject', ['class' => 'form-control btn btn-primary']) }}
								{{ Form::close()}}
							</li>
						@endif
					</ul>
				</div>
			@endif
			<?php $first = false; ?>
		@endforeach
	</div>
	<div class="col-md-4 panel panel-default">
		<h1> Activities</h1>
		<div class="panel panel-info">
			@foreach($days as $day)
				@if(count(Timeline::current()->schedules()->whereDayOfWeek($day->dayOfWeek)->get()))
					<div class="panel-heading">
						<h3 class="panel-title">{{ $day->toFormattedDateString() . '<small> ' . $day->format('D') . '</small>' }} </h3>
					</div>
					@foreach(Timeline::current()->schedules()->whereDayOfWeek($day->dayOfWeek)->get() as $schedule)
						<div class="panel-body activity">
							@if($schedule->subject->activities()->whereHappenedAt($day)->first())
								{{ Form::label('activity', $schedule->subject->subject)}}
								<div class="panel panel-info click-activity" ng-click="getActivity('{{$day}}', '{{$schedule->subject->id}}')">
									<div class="panel-body">
										{{$schedule->subject->activities()->whereHappenedAt($day)->first()->activity}}
									</div>
								</div>
								{{ Form::textarea('activity', $schedule->subject->activities()->whereHappenedAt($day)->first()->activity, ['class' => 'form-control activity-text', 'rows' => 5, 'placeholder' => 'Loading...', 'ng-model' => 'edit', 'ng-blur' => "editActivity('$day', '".$schedule->subject->id."')"]) }}
							@else
								{{ Form::label('activity', $schedule->subject->subject)}}
								<div class="panel panel-info click-activity">
									<div class="panel-body">
										&nbsp;
									</div>
								</div>
								{{ Form::textarea('activity', null, ['class' => 'form-control activity-text', 'rows' => 5, 'ng-model' => 'edit', 'ng-blur' => "editActivity('$day', '".$schedule->subject->id."')"]) }}
							@endif
						</div>
					@endforeach
				@endif
			@endforeach
		</div>
	</div>
	<div class="col-md-4 panel panel-default">
		<h1> Deadlines </h1>
		<div class="panel panel-info" ng-repeat="deadline in deadlines | orderBy:'deadline.original'">
			<div class="panel-heading">
				<h3 class="panel-title">
					@{{deadline.subject.subject}}
					<small title="@{{ deadline.deadline.original }}">@{{ deadline.deadline.diffForHumans }}	</small>
					<div class="pull-right">
						
					</div>
				</h3>
			</div>
			<div class="panel-body">	
				@{{ deadline.caption }}
			</div>
		</div>
		<div class="panel panel-info">
			<div class="panel-body">
				<form ng-submit="addDeadline()" class="form">
					<div class="form-group">
						{{ Form::select('subject', $subjectsList, null, ['class' => 'form-control', 'ng-model' => 'newDeadline.subject_id']) }}
						{{ Form::input('date', 'deadline', null, ['class' => 'form-control', 'ng-model' => 'newDeadline.deadline', 'required'])}}
						{{ Form::textarea('caption', null, ['class' => 'form-control', 'rows' => '3', 'placeholder' => 'Caption', 'ng-model' => 'newDeadline.caption', 'required'])}}
					</div>
					{{ Form::submit('New Deadline', ['class' => 'btn btn-primary form-control'])}}
				{{ Form::close() }}
			</div>
		</div>
	</div>
	@if($debug)
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
	@endif
@stop

@section('scripts')
	{{ HTML::script('/js/jquery.min.js')}}
	{{ HTML::script('/js/angular.js')}}
	{{ HTML::script('/js/main_jquery.js')}}
	{{ HTML::script('/js/journal.js')}}
@stop