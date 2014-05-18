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
				<h3> {{$day}} </h3>
				<div class= "panel panel-default">
					<ul class="list-group">
						@foreach(Timeline::current()->schedules()->whereDayOfWeek($key)->get() as $schedule)
							<li class="list-group-item {{$first ? 'active' : ''}}">
								{{ $schedule->subject->subject }}
							</li>
						@endforeach
						@if($debug)
							<li class="list-group-item">
								{{ Form::open(['route' => 'schedules.store', 'class' => 'form'])}}
									{{ Form::select('subject_id', Timeline::current()->subjects()->lists('subject', 'id'), null, ['class' => 'form-control'])}}
									{{ Form::hidden('day_of_week', $key)}}
									{{ Form::submit('Add Subject', ['class' => 'form-control btn btn-primary']) }}
								{{ Form::close()}}
							</li>
						@endif
					</ul>
				</div>
				<?php $first = false; ?>
			@endif
		@endforeach
	</div>
	<div class="col-md-5 panel panel-default">
		<h1> Activities</h1>
			<div class="panel panel-info">
				@foreach($days as $day)
					@if(count(Timeline::current()->schedules()->whereDayOfWeek($day->dayOfWeek)->get()))
						<div class="panel-heading">
							<h3 class="panel-title">{{ $day->toFormattedDateString() . '<small> ' . $day->format('D') . '</small>' }} </h3>
						</div>
						@foreach(Timeline::current()->schedules()->whereDayOfWeek($day->dayOfWeek)->get() as $schedule)
							<div class="panel-body">
								{{ Form::open(['url' => URL::route('activities.update', ['day' => $day]), 'class' => 'form']) }}
									{{ Form::hidden('subject_id', $schedule->subject->id)}}
									{{ Form::hidden('happened_at', $day)}}
									{{ Form::label('activity', $schedule->subject->subject)}}
									@if($schedule->subject->activities()->whereHappenedAt($day)->first())
										{{ Form::textarea('activity', $schedule->subject->activities()->whereHappenedAt($day)->first()->activity, ['class' => 'form-control activity-text', 'rows' => 5, 'placeholder' => $schedule->subject->activities()->whereHappenedAt($day)->first()->activity]) }}
									@else
										{{ Form::textarea('activity', null, ['class' => 'form-control activity-text', 'rows' => 5]) }}
									@endif
								{{ Form::close()}}
							</div>
						@endforeach
					@endif
				@endforeach
			</div>
		</div>
	<div class="col-md-3 panel panel-default">
		<h1> Deadlines </h1>
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
	{{ HTML::script('/js/angular.min.js')}}
	{{ HTML::script('/js/main_jquery.js')}}
	{{ HTML::script('/js/journal.js')}}
@stop