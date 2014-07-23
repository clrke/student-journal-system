function JournalController ($scope, $http) {
	$scope.quoteList = [ 
		{
			quote: "I have just three things to teach: simplicity, patience, compassion. These three are your greatest treasures.",
			source: "Lao Tzu"
		},
		{
			quote: "In character, in manner, in style, in all things, the supreme excellence is simplicity.",
			source: "Henry Wadsworth Longfellow"
		},
		{
			quote: "If we don't discipline ourselves, the world will do it for us.",
			source: "William Feather"
		},
		{
			quote: "Rule your mind or it will rule you.",
			source: "Horace"
		},
		{
			quote: "All that we are is the result of what we have thought.",
			source: "Buddha"
		},
		{
			quote: "Doing just a little bit during the time we have available puts you that much further ahead than if you took no action at all.",
			source: "Pulsifer, Take Action; Don't Procrastinate"
		},
		{
			quote: "Never leave that till tomorrow which you can do today.",
			source: "Benjamin Franklin"
		},
		{
			quote: "Procrastination is like a credit card: it's a lot of fun until you get the bill.",
			source: "Christopher Parker"
		},
		{
			quote: "Someday is not a day of the week.",
			source: "Author Unknown"
		},
		{
			quote: "Tomorrow is often the busiest day of the week.",
			source: "Spanish Proverb"
		},
		{
			quote: "I can accept failure, everyone fails at something. But I can't accept not trying.",
			source: "Michael Jordan"
		},
		{
			quote: "There’s a myth that time is money. In fact, time is more precious than money. It’s a nonrenewable resource. Once you’ve spent it, and if you’ve spent it badly, it’s gone forever.",
			source: "Neil A. Fiore"
		},
		{
			quote: "Nothing can stop the man with the right mental attitude from achieving his goal; nothing on earth can help the man with the wrong mental attitude.",
			source: "Thomas Jefferson"
		},
		{
			quote: "There is only one success--to be able to spend your life in your own way.",
			source: "Christopher Morley"
		},
		{
			quote: "Success is the good fortune that comes from aspiration, desperation, perspiration and inspiration.",
			source: "Evan Esar"
		},
		{
			quote: "We are still masters of our fate. We are still captains of our souls.",
			source: "Winston Churchill"
		},
		{
			quote: "Our truest life is when we are in dreams awake.",
			source: "Henry David Thoreau"
		},
		{
			quote: "The best way to make your dreams come true is to wake up.",
			source: "Paul Valery"
		},
		{
			quote: "Life without endeavor is like entering a jewel mine and coming out with empty hands.",
			source: "Japanese Proverb"
		},
		{
			quote: "Happiness does not consist in pastimes and amusements but in virtuous activities.",
			source: "Aristotle"
		},
	    {
	        quote: "By constant self-discipline and self-control, you can develop greatness of character.",
	        source: "Grenville Kleiser"
	    },
	    {
	        quote: "The difference between a successful person and others is not a lack of strength, not a lack of knowledge, but rather a lack in will.",
	        source: "Vince Lombardi Jr."
	    },
	    {
	        quote: "At the end of the day, let there be no excuses, no explanations, no regrets.",
	        source: "Steve Maraboli"
	    },
	    {
	        quote: "Inaction will cause a man to sink into the slough of despond and vanish without a trace.",
	        source: "Farley Mowat"
	    },
	    {
	        quote: "True freedom is impossible without a mind made free by discipline.",
	        source: "Mortimer J. Adler"
	    },
	    {
	        quote: "The most powerful control we can ever attain, is to be in control of ourselves.",
	        source: "Chris Page"
	    },
	    {
	        quote: "Idleness is only the refuge of weak minds, and the holiday of fools.",
	        source: "Philip Dormer Stanhope"
	    },
	    {
	        quote: "This is your life and it's ending one minute at a time.",
	        source: "Tyler Durden, Fight Club"
	    },
		{
			quote: "You create opportunities by performing, not complaining.",
			source: "Muriel Siebert"
		},
		{
			quote: "Great achievement is usually born of great sacrifice, and is never the result of selfishness.",
			source: "Napoleon Hill"
		},

		{
			quote: "Whether you think you can, or you think you can't, you're right.",
			source: "Henry Ford"
		},
		{
			quote: "Even if I knew that tomorrow the world would go to pieces, I would still plant my apple tree.",
			source: "Martin Luther"
		}
	];
	
	$scope.subjects = [];
	$scope.newDeadline = {subject_id: 1};
	$scope.deadlines = [];
	$scope.days = 1;
	$scope.type1 = false;
	$scope.type2 = true;

	$scope.shuffleArray = function(array) {
	    for (var i = array.length - 1; i > 0; i--) {
	        var j = Math.floor(Math.random() * (i + 1));
	        var temp = array[i];
	        array[i] = array[j];
	        array[j] = temp;
	    }
	    return array;
	}
	$scope.shuffleArray($scope.quoteList);


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