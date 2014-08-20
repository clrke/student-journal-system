<html ng-app="JournalApp">
	<head>
		<title> Student Journal System </title>
		{{ HTML::style('/css/bootstrap.min.css') }}
  		{{ HTML::style('/css/journal.css') }}
  		{{ HTML::style('/css/grg.css')}}
  		{{ HTML::style('font-awesome-4.1.0/css/font-awesome.css')}}
	</head>

	<body class="container" ng-controller="JournalController">
		@yield('content')
		{{ HTML::script('/js/jquery.min.js')}}
		{{ HTML::script('/js/underscore.min.js')}}
		{{ HTML::script('/js/bootstrap.min.js')}}
		@yield('scripts')
		</script>
	</body>
</html>