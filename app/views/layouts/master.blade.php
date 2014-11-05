<html ng-app="JournalApp">
	<head>
		<title> Student Journal System </title>
		<link rel="shortcut icon" href="{{ asset('opms.ico') }}">
		{{ HTML::style('/css/bootstrap.min.css') }}
  		{{ HTML::style('/css/journal.css') }}
  		{{ HTML::style('/css/grg.css')}}
  		{{ HTML::style('font-awesome-4.1.0/css/font-awesome.css')}}
		{{ HTML::script('/js/jquery.min.js')}}
		{{ HTML::script('/js/underscore.min.js')}}
		{{ HTML::script('/js/bootstrap.min.js')}}
		@yield('scripts')
	</head>

	<body class="container" ng-controller="JournalController">
		<br/>
		<br/>
		<div ng-if="loadingComplete">
			@yield('content')
		</div>
		<div ng-if=" ! loadingComplete" class="vertical-center">
			<div class="container">
				<div class="progress">
					<div class="progress-bar progress-bar-info"  role="progressbar" aria-valuenow="@{{initProgress}}" aria-valuemin="0" aria-valuemax="100" style="width: @{{initProgress}}%">
						<span class="sr-only">@{{currLoad / MAX_LOAD * 100}}% Complete</span>
					</div>
				</div>
				<h1> <center> Initializing the Student Journal System... </center> </h1>
			</div>
		</div>
	</body>
</html>