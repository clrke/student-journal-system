function JournalController ($scope, $http) {
	
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
	
	$scope.subjects = [];
	$scope.newDeadline = {subject_id: 1};
	$scope.deadlines = [];
	$scope.days = 1;
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
		$scope.question = null;
		$http.get('/randomquestion/' + $scope.days).success(function(question) {
			question.allAnswers = question.answers;
			question.allAnswers = question.allAnswers.concat(question.sabotages);

			question.allAnswers = $scope.shuffleArray(question.allAnswers);

			$scope.question = question;
		});

		$scope.correct = false;
	}

	$scope.refreshQuestion();

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
}