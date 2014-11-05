var JournalApp = angular.module('JournalApp', ['hexavigesimal']);

JournalApp.controller('JournalController', ['$scope', '$http', '$timeout', '$interval', function($scope, $http, $timeout, $interval) {

	$scope.questions = [];
	$scope.newQuestion = {};
	$scope.newQuestion.subject_id = 1;
	$scope.newQuestion.question = "";
	$scope.newQuestion.lesson = "";
	$scope.newQuestion.answers = [{}];
	$scope.newQuestion.sabotages = [{}];
	
	$scope.currLoad = 0;
	$scope.MAX_LOAD = 1;

	$scope.initProgress = 0;
	$scope.loadingComplete = false;

	$http.get('/questions').success(function(questions)
	{
		$scope.questions = questions;
		$scope.currLoad++;
	});

	$scope.$watch('currLoad', function(newValue, oldValue) {
		$scope.initProgress = $scope.currLoad / $scope.MAX_LOAD * 100;
		
		$interval(function () {
			$scope.initProgress += 1;
		}, 100, 20);

		if($scope.currLoad == $scope.MAX_LOAD)
			$timeout(function () {
				$scope.loadingComplete = true;
			}, 3000);
	});

	$scope.addAnswer = function()
	{
		$scope.newQuestion.answers.push({});
	}

	$scope.deleteAnswer = function(question, answer)
	{
		question.answers = _.filter(question.answers, function(answer2) {
			return answer != answer2;
		});
	}

	$scope.addSabotage = function()
	{
		$scope.newQuestion.sabotages.push({});
	}

	$scope.deleteSabotage = function(question, answer)
	{
		question.sabotages = _.filter(question.sabotages, function(answer2) {
			return answer != answer2;
		});
	}

	$scope.addQuestion = function()
	{
		if($scope.newQuestion.question && $scope.newQuestion.answers[0].answer)
		{
			$http.post('/questions', $scope.newQuestion);

			$scope.questions.unshift($scope.newQuestion);

			var subject_id = $scope.newQuestion.subject_id;
			var lesson = $scope.newQuestion.lesson;

			$scope.newQuestion = {};
			$scope.newQuestion.subject_id = subject_id;
			$scope.newQuestion.lesson = lesson;
			$scope.newQuestion.answers = [{}];
			$scope.newQuestion.sabotages = [{}];
		}
	}
}]);