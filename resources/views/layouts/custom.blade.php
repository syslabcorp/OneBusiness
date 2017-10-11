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
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/font-awesome.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ URL('/css/bootstrap.min.css') }}" />
    <link href="{{ asset('css/colorpicker.css') }}" rel="stylesheet">
	<link href="{{ URL('/biomertic-login/assets/css/ajaxmask.css') }}" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.10.15/css/dataTables.bootstrap.min.css" rel="stylesheet">
	<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-sweetalert/1.0.1/sweetalert.css" rel="stylesheet">
    <script src="{{ asset('js/app.js') }}"></script>
	
	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
	<script src="{{ URL('/biomertic-login/assets/js/html5shiv.min.js') }}"></script>
	<script src="{{ URL('/biomertic-login/assets/js/respond.min.js') }}"></script>
	<![endif]-->

	<link rel="stylesheet" href="{{ URL('/css/bootstrap-treeview.min.css') }}" />

	<style>
		.dispnone{display:none !important}
		.pull-right.forgot-password {margin: 1% 27% 0 0;}
	</style>
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
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                    {{ Auth::user()->UserName }} <span class="caret"></span>
                                </a>
                                <ul class="dropdown-menu" role="menu">
                                    <li><a href="{{ URL('logout') }}">Logout</a></li>
                                </ul>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </nav>

        <div class="container-fluid">
          <div class="row">
            <div id="togle-sidebar-sec" class="active">
              <div id="sidebar-togle-sidebar-sec">
                <ul id="sidebar_menu" class="sidebar-nav">
                    <li class="sidebar-brand"><a id="menu-toggle" href="#">Menu<span id="main_icon" class="glyphicon glyphicon-align-justify"></span></a></li>
                </ul>
                <div class="sidebar-nav" id="sidebar">     
                    <div id="treeview_json"></div>
                </div>
              </div>
              <div id="page-content-togle-sidebar-sec">
                @if(\Session::get('error') || \Session::get('success'))
                  <div class="row">
                      @if(\Session::get('error'))
                      <div class="alert alert-danger col-md-8 col-md-offset-2 {{ \Session::get('error') == "You don't have permission" ? "no-close" : ""}}" style="border-radius: 3px;">
                          <span class="fa fa-close"></span> <em>{{ \Session::get('error') }}</em>
                      </div>
                      @elseif(\Session::get('success'))
                          <div class="alert alert-success col-md-8 col-md-offset-2" style="border-radius: 3px;">
                              <span class="fa fa-close"></span> <em>{{ \Session::get('success') }}</em>
                          </div>
                      @endif
                  </div>
                @endif
                @yield('content')
              </div>
            </div>
          </div>
        </div>
    </div>
    </div>

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
	<script src="{{ URL('/js/bootstrap-treeview.js') }}"></script>
    <script src="{{ URL('/js/colorpicker.js') }}"></script>
	<script>
		if($("#treeview_json").length){
			/* var curr_url  = window.location.href;
			var open_menu = curr_url.split('#')[1];
			if( typeof open_menu !== "undefined"){
				$(".table-responsive a").each(function(){
					if($(this).children().hasClass("glyphicon-pencil")){
						alert($(this).attr("href"));
					}
				});
			} */
			
			$("#menu-toggle").click(function(e) {
					e.preventDefault();
					$("#togle-sidebar-sec").toggleClass("active");
			});
			$.ajax({
				url: ajax_url+'/list_menu',
				data: {_token:$("meta[name='csrf-token']").attr('content')},
				type: 'post',
				cache: false,
				success: function(response){
					var jsoncode = JSON.parse(response);   
					$('#treeview_json').treeview({data: jsoncode});
                  if(document.location.hash){ 
                         var document_id=document.location.hash;
                          var document_id=document_id.replace('#','');
                          document_id=parseInt(document_id);
                           $('#treeview_json').treeview('revealNode', [ document_id, { silent: true } ]);
                          $('#treeview_json').treeview('selectNode', [ document_id, { silent: true } ]);
                      }
				}
			});
		}
        $(document).on('click','.node-treeview_json',function(event) {
			var obj=$(this).find('a');
			var href=obj.attr('href');
			var val=$(this).attr('data-nodeid');
			var path = window.location.href;
			path = path.split('#')[0];
			history.pushState(null, null, path + '#' + val);
			obj.attr('href',href+'#'+val);
        });
        $(document).ready(function() {
			$('.alertfade').fadeOut(5000); // 5 seconds x 1000 milisec = 5000 milisec
        });
	</script>
</body>
</html>
