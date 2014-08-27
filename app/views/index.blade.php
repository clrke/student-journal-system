@extends('layouts.master')

@section('content')
	<br/>
	<br/>
	<div class="col-md-12 panel panel-default" style="margin: 0px">
		<h3> 
			<button class="btn btn-default pull-right" ng-click="shuffleArray(quoteList);">Refresh</button> 
			@{{ quoteList[0].quote }}
		</h3>
		<h4> - @{{ quoteList[1].source }} </h4>
	</div>
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
	<div class="col-md-8 panel panel-default">
		<div class="panel-body">
			<div class="btn-group btn-group-justified">
				<div class="btn-group">
					<button type="button" class="btn btn-lg" ng-class="tab=='activities'? 'btn-primary' : 'btn-default'" ng-click="tab='activities'">Activities</button>
				</div>
				<div class="btn-group">
					<button type="button" class="btn btn-lg" ng-class="tab=='reviewer'? 'btn-primary' : 'btn-default'" ng-click="tab='reviewer'">Reviewer</button>
				</div>
				<div class="btn-group">
					<button type="button" class="btn btn-lg" ng-class="tab=='deadlines'? 'btn-primary' : 'btn-default'" ng-click="tab='deadlines'">Deadline</button>
				</div>
			</div>
		</div>
		<div ng-show="tab=='activities'">
			<div class="panel-body">
				<div class="panel panel-info">
					<div class="panel-body">
						<div class="btn btn-default btn-block">@{{year}}</div>
						<div class="form-group btn-group btn-group-justified">
							<div class="btn-group">
								<button type="button" class="btn btn-default" ng-click="subMonth();">@{{ prevMonth }}</button>
							</div>
							<div class="btn-group">
								<button type="button" class="btn btn-primary">@{{ currMonth }}</button>
							</div>
							<div class="btn-group">
								<button type="button" class="btn btn-default" ng-click="addMonth();">@{{ nextMonth }}</button>
							</div>
						</div>
						<div class="btn-group btn-group-justified" ng-repeat="week in [0, 1, 2, 3, 4, 5]">
							<div class="btn-group" ng-repeat="day in daysInWeek(currMonth, week, year)">
								<button type="button" class="btn btn-default" ng-class="currDay == day? 'active' : ''"
									ng-click="day < 1 || day > lastDay? '' : setDay(day);" ng-style="{'background-color':getActivityDayColor(year+'-'+('0' + (month+1)).slice(-2)+'-'+('0' + day).slice(-2))}">
									@{{ day < 1 || day > lastDay ? '&nbsp;' : day }}
								</button>
							</div>
						</div>
						<div ng-repeat="activity in activities|filter:{'happened_at':year+'-'+('0' + (month+1)).slice(-2)+'-'+('0' + currDay).slice(-2)}">
							<b> @{{activity.subject.subject}} </b> <br/>
							<p style="white-space: pre;"> @{{activity.activity}}</p>
						</div>
					</div>
				</div>
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
												{{nl2br($schedule->subject->activities()->whereHappenedAt($day)->first()->activity)}}
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
		</div>
		<div ng-show="tab=='reviewer'">
			<div class="panel-body">
				<div class="panel panel-info">
					<div class="panel-heading">
						<h3 class="panel-title"> Settings </h3>
					</div>
					<div class="panel-body">
						<form ng-submit="questionsQueue = []; refreshQuestion()"> 
							<div class="pull-right">
								<button class="btn btn-primary"> Apply </button>
							</div>
							<div class="form-group">
								<label> Type: </label> 
								{{ Form::checkbox('name', 'value', 0, ['ng-model' => 'type1'])}} Multiple Choice
								{{ Form::checkbox('name', 'value', 0, ['ng-model' => 'type2'])}} Identification
								{{ Form::checkbox('name', 'value', 0, ['ng-model' => 'type3'])}} Hard Mode
							</div>
							<div class="form-group input-group">
								<span class="input-group-addon"> Subject: </span>
								{{ Form::select('name', $subjectsList, 0, ['class' => 'form-control', 'ng-model' => 'quizSubject'])}}
							</div>
							<div class="form-group input-group">
								<span class="input-group-addon"> Lesson(s): </span>
								<input type="text" class="form-control" ng-model="quizLesson">
							</div>
						</form>
					</div>
				</div>
				<form ng-submit="correct ? refreshQuestion() : popQuizSubmit()">
					<div ng-class="question.noData? 'panel panel-danger' : 'panel panel-primary'">
						<div class="panel-heading">
							<h3 class="panel-title">
								<b ng-hide="quizLesson !== '' || question.lesson === ''"> @{{ question.lesson }}: </b> @{{ question.question }}
								<center ng-if="question.image">
									<img ng-src="/img/@{{question.image}}" class="img-responsive img-rounded img-thumbnail">
								</center>
							</h3>
						</div>
						<div class="panel-body" ng-show="type1 && !question.noData">
							<div class="row">
								<div class="form-group col-md-6" ng-repeat="answer in question.allAnswers">	
									{{ Form::checkbox('name', 'value', 0, ['ng-model' => 'answer.chosen', 'ng-show' => 'question.answers.length > 1'])}}
									{{ Form::radio('answer_chosen', '', 0, ['ng-model' => '$parent.answer_chosen', 'ng-value' => 'answer.id', 'ng-show' => 'question.answers.length == 1'])}}
									@{{ answer.answer }}
									<b> @{{ answer.judge }} </b>
								</div>
							</div> 
						</div>
						<div class="panel-body" ng-show="type2 && !question.noData">
							<div class="row">
								<div ng-class="[answer.status, question.answers.length==1? 'col-md-12':'col-md-6']" class="form-group" ng-repeat="answer in question.answers">
									<div ng-class="question.answers.length > 1 ? 'input-group' : ''">
										<span class="input-group-addon" ng-if="question.answers.length > 1"> @{{ $index+1 }} </span>
										{{ Form::text('name', '', ['class' => ' form-control', 'ng-model' => 'answer.try', 'focus-asap' => 'shouldFocus', 'ng-if' => '$first', 'ng-init' => 'shouldFocus=true;']) }}
										{{ Form::text('name', '', ['class' => ' form-control', 'ng-model' => 'answer.try', 'ng-if' => ' ! $first']) }}
									</div>
								</div>
							</div>
						</div>
						<div class="panel-body" ng-show="type3 && !question.noData">
							<div class="row">
								<div ng-class="[answer.status, question.answers.length==1? 'col-md-12':'col-md-6']" class="form-group" ng-repeat="answer in question.answers">
									<div ng-class="question.answers.length > 1 ? 'input-group' : ''">
										<span class="input-group-addon" ng-if="question.answers.length > 1"> @{{ $index+1 }} </span>
										{{ Form::text('name', '', ['class' => ' form-control', 'ng-model' => 'answer.try', 'focus-asap' => 'shouldFocus', 'ng-if' => '$first', 'ng-init' => 'shouldFocus=true;', 'ng-change' => 'checkAnswer(answer);', 'ng-trim' => 'false']) }}
										{{ Form::text('name', '', ['class' => ' form-control', 'ng-model' => 'answer.try', 'ng-if' => ' ! $first', 'ng-change' => 'checkAnswer(answer);', 'ng-trim' => 'false']) }}
									</div>
								</div>
							</div>
						</div>
						<div class="panel-body" ng-show="question.noData">
							<a class="form-control btn btn-danger" ng-show="question.noData" href="/questions/add"> Add New Questions </a>
						</div>
					</div>

					<button class="btn btn-primary" ng-hide="correct"> Submit </button>
					<button class="btn btn-default" ng-show="correct"> Refresh </button>
					<table class="pull-right">
						<tr>
							<td ng-style="{'color':comboColor}"><b> Combo: </b></td>
							<td ng-style="{'color':comboColor}"><b> @{{ combo }} </b></td>
						</tr>
						<tr>
							<td style="color: #00D000"><b> High score:&nbsp; </b></td>
							<td style="color: #00D000"><b> @{{ highscore }} </b></td>
						</tr>
					</table>
				</form>
			</div>
		</div>
		<div ng-show="tab=='deadlines'">
			<div class="panel-body">
				<div class="row">
					<div class="col-md-6" ng-repeat="deadline in deadlines | orderBy:'deadline.original'">
						<div class="panel panel-info">
							<div class="panel-heading">
								<h1 class="panel-title">
									<div class="pull-right">
										<a ng-click="deleteDeadline(deadline.id)" class='x'> x </a>
									</div>
									@{{deadline.subject.subject}}<br/>
									<small>@{{ deadline.deadline.original }} (@{{ deadline.deadline.diffForHumans }})</small>
								</h1>
							</div>
							<div class="panel-body">	
								<h4> @{{ deadline.caption }} </h4> 
								<!-- <div ng-repeat="checklist in deadline.checklists" class="form-group input-group">
									<span class="input-group-addon">
										<input type="checkbox" ng-model="checklist.done"> 
									</span>
									<input type="text" ng-model="checklist.caption" class="form-control">
									<span class="input-group-addon">
										<button ng-click="deleteChecklist(checklist)" class="btn btn-xs btn-danger">
											x
										</button> 
									</span>
								</div>
								<button ng-click="addChecklist(deadline)" class="btn btn-primary"> + </button> -->
							</div>
						</div>
					</div>
				</div>
				<div class="panel panel-info">
					<div class="panel-heading">
						<h3 class="panel-title"> New Deadline </h3>
					</div>
					<div class="panel-body">
						<form ng-submit="addDeadline()" class="form">
							<div class="form-group">
								{{ Form::select('subject', $subjectsList, null, ['class' => 'form-control', 'ng-model' => 'newDeadline.subject_id']) }}
								{{ Form::input('date', 'deadline', null, ['class' => 'form-control', 'ng-model' => 'newDeadline.deadline', 'required'])}}
								{{ Form::textarea('caption', null, ['class' => 'form-control', 'rows' => '3', 'placeholder' => 'Caption', 'ng-model' => 'newDeadline.caption', 'required'])}}
							</div>
							{{ Form::submit('New Deadline', ['class' => 'btn btn-primary form-control'])}}
						</form>
					</div>
				</div>
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
	{{ HTML::script('/js/main_jquery.js')}}
	{{ HTML::script('/js/angular.js')}}
	{{ HTML::script('/js/journal.js')}}
@stop