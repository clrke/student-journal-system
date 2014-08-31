@extends('layouts.master')

@section('content')
	<div class="col-md-offset-3 col-md-6 panel panel-default" ng-controller="HexavigesimalController as hexavigesimal">
		<h1> Questions </h1>

		<div class="panel panel-info">
			<div class="panel-heading">
				<h3 class="panel-title">
					Settings
				</h3>
			</div>
			<div class="panel-body">
				<div class="form-group" ng-init="$parent.numbering = 2">
					<label> Numbering: </label>
					{{ Form::radio('numbering', '1', 0, ['ng-model' => '$parent.numbering', 'ng-value' => '1'])}} Decimal
					{{ Form::radio('numbering', '2', 1, ['ng-model' => '$parent.numbering', 'ng-value' => '2'])}} Hexavigesimal
				</div>
			</div>
		</div>

		<div class="panel panel-info">
			<div class="panel-heading">
				<h3 class="panel-title">
					New Question
				</h3>
			</div>

			<div class="panel-body">
				<form class="form-horizontal" ng-submit="addQuestion()">
					<div class="form-group row">
						<div class="col-md-4">{{ Form::label('Subject *')}}</div>
						<div class="col-md-8">{{ Form::select('subject_id', $subjects, '', ['class' => 'form-control', 'ng-model' => 'newQuestion.subject_id'])}}</div>
					</div>
					<div class="form-group row">
						<div class="col-md-4">{{ Form::label('Lesson')}}</div>
						<div class="col-md-8">{{ Form::text('lesson', '', ['class' => 'form-control', 'ng-model' => 'newQuestion.lesson'])}}</div>
					</div>
					<div class="form-group row">
						<div class="col-md-4">{{ Form::label('question *')}}</div>
						<div class="col-md-8">{{ Form::text('question', '', ['class' => 'form-control', 'ng-model' => 'newQuestion.question']) }}</div>
					</div>
					<div class="form-group row">
						<div class="col-md-4">{{ Form::label('Image')}}</div>
						<div class="col-md-8">{{ Form::text('image', '', ['class' => 'form-control', 'accept' => 'image/*', 'ng-model' => 'newQuestion.image']) }}</div>
					</div>
					<div class="form-group row">
						<div class="col-md-4">{{ Form::label('answers *') }}</div>
						<div ng-repeat="answer in newQuestion.answers">
							<div class="col-md-4" ng-if=" ! $first"> &nbsp; </div>
							<div class="col-md-8">
								<div class="input-group">
									<span class="input-group-addon" ng-if="newQuestion.answers.length > 1"> 
										@{{ numbering == 2? hexavigesimal.convert($index+1) : $index+1 }} 
									</span>
									<input type="text" class="form-control" ng-model="answer.answer" required>
									<span class="btn btn-danger input-group-addon" ng-if=" ! $first" ng-click="deleteAnswer(newQuestion, answer)">
										<i class="fa fa-minus"> </i>
									</span>
									<span class="btn btn-primary input-group-addon" ng-click="addAnswer(newQuestion)"> 
										<i class="fa fa-plus"> </i>
									</span>
								</div>
							</div>
						</div>
					</div>
					<div class="form-group row">
						<div class="col-md-4"> {{ Form::label('sabotage') }} </div>
						<div ng-repeat="sabotage in newQuestion.sabotages">
							<div class="col-md-4" ng-if=" ! $first"> &nbsp; </div>
							<div class="col-md-8">
								<div class="input-group">
									<input class="form-control" ng-model="sabotage.answer">
									<span class="btn btn-danger input-group-addon" ng-if=" ! $first" ng-click="deleteSabotage(newQuestion, sabotage)">
										<i class="fa fa-minus"> </i>
									</span>
									<span class="btn btn-primary input-group-addon" ng-click="addSabotage(newQuestion)"> 
										<i class="fa fa-plus"> </i>
									</span>
								</div>
							</div>
						</div>
					</div>
					<button type="submit" class="btn btn-primary pull-right">
						Create
					</button>
				</form>
			</div>
		</div>

		<div class="panel panel-info" ng-repeat="question in questions | filter: {subject_id: newQuestion.subject_id} | filter: newQuestion.lesson">
			<div class="panel-heading">
				<h3 class="panel-title">
					<b ng-hide="quizLesson !== '' || question.lesson === ''"> @{{ question.lesson }}: </b> @{{ question.question }}
					<center ng-if="question.image">
						<img ng-src="/img/@{{question.image}}" class="img-responsive img-rounded img-thumbnail">
					</center>
				</h3>
			</div>
			<div class="panel-body">
				<ul class="list-group">
					<li class="list-group-item" ng-repeat="answer in question.answers">
						@{{question.answers.length > 1? (numbering == 2? hexavigesimal.convert($index+1) : $index+1)+'. ' : '-'}} @{{ answer.answer }}
					</li>
				</ul>
			</div>
			<br/>
		</div>
	</div>
@stop

@section('scripts')
	{{ HTML::script('/js/angular.js')}}
	{{ HTML::script('/js/question.js')}}
	{{ HTML::script('/js/helpers/hexavigesimal.js')}}
@stop