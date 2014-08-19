@extends('layouts.master')

@section('content')
	<div class="col-md-offset-3 col-md-6 panel panel-default">
		<h1> Questions </h1>

		<div class="panel panel-info">
			<div class="panel-heading">
				<h3 class="panel-title">
					New Question
				</h3>
			</div>
			<div class="panel-body">
				<form ng-submit="addQuestion()">
					<div class="form-group">
						{{ Form::label('Subject *')}}
						{{ Form::select('subject_id', $subjects, '', ['class' => 'form-control', 'ng-model' => 'newQuestion.subject_id'])}}
					</div>
					<div class="form-group">
						{{ Form::label('Lesson *')}}
						{{ Form::text('lesson', '', ['class' => 'form-control', 'ng-model' => 'newQuestion.lesson'])}}
					</div>
					<div class="form-group">
						{{ Form::label('question *')}}
						{{ Form::text('question', '', ['class' => 'form-control', 'ng-model' => 'newQuestion.question']) }}
					</div>
					<div class="form-group">
						{{ Form::label('answers *') }}
						<div class="form-group input-group" ng-repeat="answer in newQuestion.answers">
							<input class="form-control" ng-model="answer.answer">
							<span class="btn btn-primary input-group-addon" ng-click="addAnswer(newQuestion)"> 
								+ 
							</span>
							<span class="btn btn-danger input-group-addon" ng-hide="$first" ng-click="deleteAnswer(newQuestion, answer)">
								-
							</span>
						</div>
					</div>
					<div class="form-group">
						{{ Form::label('sabotage') }}
						<div class="form-group input-group" ng-repeat="sabotage in newQuestion.sabotages">
							<input class="form-control" ng-model="sabotage.answer">
							<span class="btn btn-primary input-group-addon" ng-click="addSabotage(newQuestion)"> 
								+ 
							</span>
							<span class="btn btn-danger input-group-addon" ng-hide="$first" ng-click="deleteSabotage(newQuestion, sabotage)">
								-
							</span>
						</div>
					</div>
					<button type="submit" class="btn btn-primary">
						Create
					</button>
				</form>
			</div>
		</div>

		<div class="panel panel-info" ng-repeat="question in questions | filter: {subject_id: newQuestion.subject_id} | filter: newQuestion.lesson">
			<div class="panel-heading">
				<h3 class="panel-title">
					@{{ question.question }}
				</h3>
			</div>
			<div class="panel-body">
				<ul class="list-group">
					<li class="list-group-item" ng-repeat="answer in question.answers">
						- @{{ answer.answer }}
					</li>
				</ul>
			</div>
			<br/>
		</div>
	</div>
@stop

@section('scripts')
	{{ HTML::style('/css/grg.css')}}
	{{ HTML::script('/js/angular.js')}}
	{{ HTML::script('/js/jquery.min.js')}}
	{{ HTML::script('/js/underscore.min.js')}}
	{{ HTML::script('/js/question.js')}}
@stop