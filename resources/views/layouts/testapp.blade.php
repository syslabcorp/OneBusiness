<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="{{ config('app.locale') }}">
<!--<![endif]-->
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="csrf-token" content="{{ csrf_token() }}">
		<title>Web Login System</title>
		<!-- Bootstrap -->
		<link href="{{ asset('css/app.css') }}" rel="stylesheet">
		<link href="{{ URL('/biomertic-login/assets/css/bootstrap.min.css') }}" rel="stylesheet">
		<link href="{{ URL('/biomertic-login/assets/css/ajaxmask.css') }}" rel="stylesheet">
		<link href="{{ URL('/biomertic-login/assets/css/style.css') }}" rel="stylesheet">

		<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
		<!--[if lt IE 9]>
		<script src="{{ URL('/biomertic-login/assets/js/html5shiv.min.js') }}"></script>
		<script src="{{ URL('/biomertic-login/assets/js/respond.min.js') }}"></script>
		<![endif]-->
		<script src="{{ URL('/biomertic-login/assets/js/jquery.min.js') }}"></script>
		<script>
			window.Laravel = {!! json_encode([
				'csrfToken' => csrf_token(),
			]) !!};
			var ajax_url = "{{ URL('/') }}";
			var biometric_url = "{{ URL('/biomertic-login') }}";
		</script>
	</head>
	<body>
		<div id="app">
			<nav class="navbar navbar-default navbar-static-top">
				<div class="container">
					<div class="navbar-header">

						<!-- Collapsed Hamburger -->
						<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
							<span class="sr-only">Toggle Navigation</span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
						</button>

						<!-- Branding Image -->
						<img class="business-one-logo" src="{{ URL('img/logo.png') }}" />
						<!--a class="navbar-brand" href="{{ url('/') }}">
							{{ config('app.name', 'Laravel') }}
						</a-->
					</div>

					<div class="collapse navbar-collapse" id="app-navbar-collapse">
						<!-- Left Side Of Navbar -->
						<ul class="nav navbar-nav">
							&nbsp;
						</ul>

						<!-- Right Side Of Navbar -->
						<ul class="nav navbar-nav navbar-right">
							<!-- Authentication Links -->
							@if (Auth::guest())
								<!--li><a href="{{ route('login') }}">Login</a></li-->
								<li><a href="{{ URL('/username') }}">Login</a></li>
								<li><a href="{{ route('register') }}">Register</a></li>
							@else
								<li class="dropdown">
									<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
										{{ Auth::user()->Full_Name }} <span class="caret"></span>
									</a>

									<ul class="dropdown-menu" role="menu">
										<li>
											<a href="{{ route('logout') }}"
												onclick="event.preventDefault();
														 document.getElementById('logout-form').submit();">
												Logout
											</a>

											<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
												{{ csrf_field() }}
											</form>
										</li>
									</ul>
								</li>
							@endif
						</ul>
					</div>
				</div>
			</nav>

			@yield('content')
		</div>
		<script src="{{ asset('js/app.js') }}"></script>
	</body>
</html>