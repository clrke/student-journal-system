<html ng-app="JournalApp">
	<head>
		<title> Student Journal System </title>
		{{ HTML::style('/css/bootstrap.min.css') }}
  		{{ HTML::style('/css/journal.css') }}
  		{{ HTML::style('/css/grg.css')}}
  		{{ HTML::style('font-awesome-4.1.0/css/font-awesome.css')}}
	</head>

	<body class="container" ng-controller="JournalController">
		<div ng-show="currLoad == MAX_LOAD">
			@yield('content')
		</div>
		<div ng-hide="currLoad == MAX_LOAD">
			<br/>
			<br/>
			<div class="progress">
				<div class="progress-bar progress-bar-success progress-bar-striped active"  role="progressbar" aria-valuenow="@{{currLoad / MAX_LOAD * 100}}" aria-valuemin="0" aria-valuemax="100" style="width: @{{currLoad / MAX_LOAD * 100}}%">
					<span class="sr-only">@{{currLoad / MAX_LOAD * 100}}% Complete</span>
				</div>
			</div>
		</div>
		{{ HTML::script('/js/jquery.min.js')}}
		{{ HTML::script('/js/underscore.min.js')}}
		{{ HTML::script('/js/bootstrap.min.js')}}
		@yield('scripts')
		</script>
	</body>
</html>