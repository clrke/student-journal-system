<html ng-app>
	<head>
		<title> Student Journal System </title>
  		{{ HTML::style('/css/bootstrap.min.css') }}
  		{{ HTML::style('/css/journal.css') }}
	</head>

	<body class="container" ng-controller="JournalController">
		@yield('content')
		@yield('scripts')
	</body>
</html>