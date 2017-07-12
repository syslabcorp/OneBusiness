<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!--title>{{ config('app.name', 'Laravel') }}</title-->
    @if(\View::hasSection('head'))
        @yield('head')
    @else
        <title>Web Login System</title>
    @endif

    <!-- Styles -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
	<link href="{{ URL('/biomertic-login/assets/css/ajaxmask.css') }}" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.10.15/css/dataTables.bootstrap.min.css" rel="stylesheet">
	<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-sweetalert/1.0.1/sweetalert.css" rel="stylesheet">
    
	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
	<script src="{{ URL('/biomertic-login/assets/js/html5shiv.min.js') }}"></script>
	<script src="{{ URL('/biomertic-login/assets/js/respond.min.js') }}"></script>
	<![endif]-->

	<style>
		.dispnone{display:none !important}
		.pull-right.forgot-password {margin: 1% 27% 0 0;}
	</style>
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-default navbar-static-top">
            <div class="container-fluid">
                <div class="navbar-header">
                    <!-- Collapsed Hamburger -->
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                        <span class="sr-only">Toggle Navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <!-- Branding Image -->
					<a href="{{ url('/') }}">
					<img class="business-one-logo" src="{{ URL('img/logo.png') }}" />
					</a>
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
							<li><a href="{{ URL('list_template') }}">Manage Templates</a></li>
							<li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                    Manage Masters <span class="caret"></span>
                                </a>
                                <ul class="dropdown-menu" role="menu">
                                    <li><a href="{{ URL('list_corporation') }}">Corporations</a></li>
                                    <li><a href="{{ URL('list_module') }}">Modules</a></li>
                                    <li><a href="{{ URL('list_feature') }}">Features</a></li>
                                </ul>
                            </li>
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
    @if(\Session::get('error'))
    <div class="alert alert-danger auto-close">
        {{ \Session::get('error') }}
    </div>
    @elseif(\Session::get('success'))
        <div class="alert alert-success auto-close">
            {{ \Session::get('success') }}
        </div>
    @endif
    <div class="container-fluid">
        <div class="row">
            @if(\Auth::user())
            <div class="col-sm-3 col-md-2 sidebar">
                <ul class="nav nav-sidebar" id="main-menu">
                    {{-- <li class="dropdown"><a href="#submenu1" class="dropdown-toggle" data-toggle="collapse" data-parent="main-menu" >NETXPRESS<b class="caret"></b></a>
                        <ul class= "collapse" id="submenu1">
                            <li>
                                <a href="{{ route('branchs.index') }}">{{ __('Branchs') }}</a>
                            </li>
                        </ul>
                    </li> --}}
                    <li>
                        <a href="{{ route('branchs.index') }}">{{ __('BRANCHS') }}</a>
                    </li>
                </ul>
            </div>
            @endif
            <div class="col-sm-9 col-md-10 main">
                @yield('content')
            </div>
        </div>
    </div>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
	<script src="{{ URL('/biomertic-login/assets/js/jquery.timer.js') }}"></script>
	<!-- Include all compiled plugins (below), or include individual files as needed -->
	<script src="{{ URL('/biomertic-login/assets/js/bootstrap.min.js') }}"></script>
	<script src="{{ URL('/biomertic-login/assets/js/ajaxmask.js') }}"></script>

	<script src="{{ URL('/biomertic-login/assets/js/custom.js') }}"></script>

    <!-- Scripts -->
    <script>
        window.Laravel = {!! json_encode([
            'csrfToken' => csrf_token(),
        ]) !!};
		var ajax_url = "{{ URL('/') }}";
		var biometric_url = "{{ URL('/biomertic-login') }}";
    </script>
	<script src="{{ asset('js/login.js') }}"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.16.0/jquery.validate.js"></script>
    <script src="https://cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.15/js/dataTables.bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-sweetalert/1.0.1/sweetalert.js"></script>
    
</body>
</html>
