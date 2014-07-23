function JournalController ($scope, $http) {
	$scope.questions = [];

	$scope.newQuestion = {};
	$scope.newQuestion.question = "";
	$scope.newQuestion.answers = [{}];
	$scope.newQuestion.sabotages = [{}];

	$http.get('/questions').success(function(questions)
	{
		$scope.questions = questions;
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
			$scope.newQuestion = {};
			$scope.newQuestion.answers = [{}];
			$scope.newQuestion.sabotages = [{}];
		}
	}
}