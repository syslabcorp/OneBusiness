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
    <link href="{{ asset('css/app.css') }}" rel="stylesheet"> 
	<link href="{{ URL('/css/bootstrap.min.css') }}" rel="stylesheet">
	<link href="{{ URL('/biomertic-login/assets/css/ajaxmask.css') }}" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.10.15/css/dataTables.bootstrap.min.css" rel="stylesheet">
	<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-sweetalert/1.0.1/sweetalert.css" rel="stylesheet">
	
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700" rel="stylesheet">
    <link rel="stylesheet" href="{{ URL('/css/bootstrap.min.css') }}" />
    <link href="{{ asset('css/colorpicker.css') }}" rel="stylesheet">
	<link href="{{ URL('/biomertic-login/assets/css/ajaxmask.css') }}" rel="stylesheet">
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/jquery-ui.min.js') }}"></script>

	<link href="{{ URL('/css/bootstrap.min.css') }}" rel="stylesheet">
	<link href="{{ URL('/biomertic-login/assets/css/ajaxmask.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css" integrity="sha384-DNOHZ68U8hZfKXOrtjWvjxusGo9WQnrNx2sqG0tfsghAvtVlRW3tvkXWZh58N9jp" crossorigin="anonymous">
	
	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// --->
	<!--[if lt IE 9]>
	<script src="{{ URL('/biomertic-login/assets/js/html5shiv.min.js') }}"></script>
	<script src="{{ URL('/biomertic-login/assets/js/respond.min.js') }}"></script>
	<![endif]-->

	<link rel="stylesheet" href="{{ URL('/css/bootstrap-treeview.min.css') }}" />

        @yield('header_styles')
	<style>
		.dispnone{display:none !important}
		.pull-right.forgot-password {margin: 1% 27% 0 0;}
        #sidebar-togle-sidebar-sec {
            z-index: 100 !important;
        }
	</style>

    @yield('header-scripts')
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-default navbar-static-top">
          <div class="navbar-header">
              <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                  <span class="sr-only">Toggle Navigation</span>
                  <span class="icon-bar"></span>
                  <span class="icon-bar"></span>
                  <span class="icon-bar"></span>
              </button>
          <a href="{{ url('/') }}">
            <img class="business-one-logo" src="{{ URL('img/logo.png') }}" />
          </a>
          </div>
          <div class="collapse navbar-collapse" id="app-navbar-collapse">
              <ul class="nav navbar-nav">
                  &nbsp;
              </ul>
              <ul class="nav navbar-nav navbar-right">
                  @if (Auth::guest())
                      <li><a href="{{ URL('/username') }}">Login</a></li>
                      <li><a href="{{ route('register') }}">Register</a></li>
                  @else
                      <li class="dropdown">
                          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                            <i class="far fa-user"></i>
                            {{ Auth::user()->UserName }} 
                            <i class="fas fa-angle-down"></i>
                          </a>
                          <ul class="dropdown-menu" role="menu">
                              <li><a href="{{ URL('logout') }}">Logout</a></li>
                          </ul>
                      </li>
                  @endif
              </ul>
          </div>
        </nav>

        @yield('content')
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
	<script type="text/javascript">
    (() => {
      getItemMenuHTML = (item) => {
        let resultHTML = 
          '<li> \
            <a href="' + (item.href || '#') + '">'

        if(item.icon) {
          resultHTML += '<i class="' + item.icon  + '"></i>'
        }
        
        resultHTML += item.text

        if(item.nodes && item.nodes.length) {
          resultHTML += '<span class="arrow"> \
            <i class="fas fa-chevron-down"></i> \
            <i class="fas fa-chevron-up"></i> \
            </span>'
        }
        
        resultHTML += '</a>'

        if(item.nodes && item.nodes.length) {
          resultHTML += '<ul style="display: none;">'
          for(let index = 0; index < item.nodes.length; index++) {
            resultHTML += getItemMenuHTML(item.nodes[index])
          }
          resultHTML += '</ul>'
        }
        resultHTML += '</li>'
        return resultHTML
        
      }

      $('.sidebar-nav').on('click', 'a', function(event) {
        if(!$(this).hasClass('active')) {
          $(this).closest('ul').find('li ul').slideUp(400)
          $(this).closest('ul').find('a').removeClass('active')
        }
        $(this).closest('li').find('>ul').slideToggle(400)
        $(this).toggleClass('active')
      })

      setActiveMenu = () => {
        $('.sidebar-nav a[href]').each((index, el) => {
          let pathname = location.pathname.replace(/\/OneBusiness\//, '').replace(/^\//, '').replace(/\/[\w\D]*/i, '')
          el = $(el)

          if(el.attr('href').match(new RegExp(location.pathname + '{{ isset(request()->corpID) ? ".*corpID=" . request()->corpID  : '$' }}')) || 
            el.attr('href').match(new RegExp('\/' + pathname + '{{ isset(request()->corpID) ? ".*corpID=" . request()->corpID  : '($|/)' }}')) || 
            !el.attr('href').match(/corpID/) && el.attr('href').match(new RegExp(pathname + '$'))) {
            el.addClass('active')
            openMenu(el)
          }
        })
      }

      openMenu = (el) => {
        if(!el.closest('ul').closest('li').length) {
          return
        }

        el.closest('ul').slideDown(400)
        el.closest('ul').closest('li').find('>a').addClass('active')

        openMenu(el.closest('ul').closest('li').find('>a'))
      }

      $.ajax({
        url: '{{ route('listmenu') }}',
        data: {_token:$("meta[name='csrf-token']").attr('content')},
        type: 'post',
        cache: false,
        success: (res) => {
          let jsonReponse = JSON.parse(res)

          for(let index = 0; index < jsonReponse.length; index++) {
            $('.sidebar-nav>ul').append(getItemMenuHTML(jsonReponse[index]))
          }

          setActiveMenu()
        }
      });
    })()
        $(document).ready(function() {
			$('.alertfade').fadeOut(5000); // 5 seconds x 1000 milisec = 5000 milisec
        });
	</script>
    @yield('footer-scripts')
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>
</body>
</html>
