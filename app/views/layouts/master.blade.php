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
		<div ng-show="currLoad == MAX_LOAD">
			@yield('content')
		</div>
		<div ng-hide="currLoad == MAX_LOAD" class="col-md-offset-3 col-md-6">
			<div class="panel panel-info">
				<div class="panel-heading">
					<h3 class="panel-title">Loading...</h3>
				</div>
				<div class="panel-body">
					<div class="progress">
						<div class="progress-bar progress-bar-info progress-bar-striped active"  role="progressbar" aria-valuenow="@{{currLoad / MAX_LOAD * 100}}" aria-valuemin="0" aria-valuemax="100" style="width: @{{currLoad / MAX_LOAD * 100}}%">
							<span class="sr-only">@{{currLoad / MAX_LOAD * 100}}% Complete</span>
						</div>
					</div>
				</div>
			</div>
		</div>
		</script>
	</body>
</html>