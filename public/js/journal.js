var JournalApp = angular.module('JournalApp', []);

JournalApp.controller('JournalController', ['$scope', '$http', function($scope, $http) {
	
	$scope.shuffleArray = function(array) {
	    for (var i = array.length - 1; i > 0; i--) {
	        var j = Math.floor(Math.random() * (i + 1));
	        var temp = array[i];
	        array[i] = array[j];
	        array[j] = temp;
	    }
	    return array;
	}

	$http.get('/quotes').success(function(quotes) {
		$scope.quoteList = $scope.shuffleArray(quotes);
	});

	$http.get('/questions').success(function(questions) {
		$scope.questions = questions;
		$scope.refreshQuestion();
	});
	
	$scope.subjects = [];
	$scope.newDeadline = {subject_id: 1};
	$scope.quizSubjects = ["1"];
	$scope.deadlines = [];
	$scope.days = 1;
	$scope.questionsQueue = [];
	$scope.type1 = false;
	$scope.type2 = true;

	$scope.popQuizSubmit = function()
	{
		if( ! $scope.question )
			return;

		$scope.correct = true;
		if($scope.type1)
		{
			for (var i = $scope.question.allAnswers.length - 1; i >= 0; i--) {
				answer = $scope.question.allAnswers[i];
				if(answer.correct && answer.chosen)
					answer.judge = "OK";
				else if( ! answer.correct && ! answer.chosen)
					answer.judge = "OK";
				else 
				{
					answer.judge = "X";
					$scope.correct = false;
				}
			};
		}

		if($scope.type2)
		{
			for (var i = $scope.question.allAnswers.length - 1; i >= 0; i--) {
				answer = $scope.question.allAnswers[i];

				if( ! answer.try || answer.try.toLowerCase() != answer.answer.toLowerCase())
					answer.status = "has-error";
				else answer.status = "has-success";

				answer.try = answer.answer;
			}
		}

	}

	$scope.refreshQuestion = function() 
	{
		if($scope.questionsQueue.length > 0)
			question = $scope.questionsQueue.shift();
		else
		{
			$scope.questionsQueue = $scope.questions.slice(0);

			limit = new Date();

			limit.setDate(limit.getDate() - $scope.days);

			$scope.questionsQueue = _.filter($scope.questionsQueue, function(question) {
				return new Date(question.created_at) > limit;
			});

			if($scope.questionsQueue.length == 0)
				$scope.questionsQueue = $scope.questions.slice(0);
			
			$scope.questionsQueue = _.filter($scope.questionsQueue, function(question) {
				return _.contains($scope.quizSubjects, question.subject_id.toString());
			});

			$scope.questionsQueue = $scope.shuffleArray($scope.questionsQueue);

			question = $scope.questionsQueue.shift();
		}
	
		var max = question.answers.length == 1 ? 4 : question.answers.length * 2;

		for (var i = question.answers.length + question.sabotages.length; i < max; i++) 
		{
			if($scope.questionsQueue[i+1])
			{
				var sabotage = $.extend({}, $scope.questionsQueue[i+1].answers[0]);
				sabotage.correct = 0;
				question.sabotages.push(sabotage);
			}
		}
		question.allAnswers = question.answers.concat(question.sabotages);
		$scope.shuffleArray(question.allAnswers);

		for (var i = 0; i < question.allAnswers.length; i++) {
			question.allAnswers[i].judge = null;
			question.allAnswers[i].try = null;
			question.allAnswers[i].status = null;
			question.allAnswers[i].chosen = null;
		};

		$scope.question = question;
		
		$scope.correct = false;
	}

	function refresh()
	{
		$http.get('subjects').success(function(subjects) {
			$scope.subjects = subjects;
		});

		$http.get('timelines/current/id').success(function(timeline_id) {
			$scope.timeline_id = timeline_id;
		});

		$http.get('deadlines').success(function(deadlines) {
			$scope.deadlines = deadlines;
		});
	}

	refresh();

	$scope.addSubject = function() {
		var subject = ({
			timeline_id: $scope.timeline_id,
			subject: $scope.newSubject
		});
		$scope.newSubject = "";

		$http.post('subjects', subject);
		$scope.subjects.push(subject);

		//refresh();
	}

	$scope.editSubject = function(id) {
		alert($scope.editSubjectText);

		var subject = ({
			subject: $scope.editSubjectText
		});

		$http.post('subjects/'+id+'/edit', subject);

		//refresh();
	}

	$scope.editActivity = function(day, id) {

		var activity = ({
			subject_id : id,
			happened_at : day,
			activity : $scope.edit
		});

		$scope.edit = '';
		$http.post('subjects/'+id+'/activities/'+day, activity);
	}

	$scope.getActivity = function(day, id) {
		$http.get('subjects/'+id+'/activities/'+day).success(function(activity){
			if(activity) $scope.edit = activity;
			else $scope.edit = ' ';
		});
	};

	$scope.addDeadline = function() {
		deadline = 
		{
			subject_id : $scope.newDeadline.subject_id,
			subject: {subject:''},
			caption : $scope.newDeadline.caption,
			deadline : $scope.newDeadline.deadline
		};

		$http.post('deadlines', deadline).success(function(data) {
			deadline.id = data.id;
			deadline.subject_id = data.subject_id;
			deadline.caption = data.caption;
			deadline.deadline = data.deadline;
		});

		$http.get('diffForHumans/'+$scope.newDeadline.deadline).success(function(diffForHumans) {
			deadline.deadline.diffForHumans = diffForHumans;
		});

		for (var i = 0; i < $scope.subjects.length; i++) 
		{
			if($scope.subjects[i].id == deadline.subject_id)
			{
				deadline.subject.subject = $scope.subjects[i].subject;
				break;
			}
		}

		$scope.deadlines.push(deadline);

		$scope.newDeadline.subject = 0;
		$scope.newDeadline.caption = '';
		$scope.newDeadline.deadline = '';
		
	};

	$scope.deleteDeadline = function(id) {
		if(confirm("Are you sure you want to delete?"))
		{
			$http.delete('deadlines/'+id);
			$scope.deadlines = _.filter($scope.deadlines, function(deadline) {
				return deadline.id != id;
			});
		}
	}

	$scope.addChecklist = function(deadline) {
		deadline.checklists.push({});
	}

	$scope.deleteChecklist = function(checklist) {

	}
}]);

JournalApp.directive('focusAsap', function($timeout) {
  return {
    link: function(scope, element, attrs) {
      scope.$watch(attrs.focusAsap, function(value) {
        if(value === true) { 
          //$timeout(function() {
            element[0].focus();
            scope[attrs.focusMe] = false;
          //});
        }
      });
    }
  };
});