<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Project A</title>

    <!-- Fonts -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css" rel='stylesheet'
          type='text/css'>
    <link href="https://fonts.googleapis.com/css?family=Lato:100,300,400,700" rel='stylesheet' type='text/css'>

    <!-- Styles -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ url('/') }}/css/nouislider.min.css" rel="stylesheet">
    <link href="{{ url('/') }}/css/site.css" rel="stylesheet">
    <link href="{{ url('/') }}/css/grid.css" rel="stylesheet">

    @yield('pageCss')

    {{-- <link href="{{ elixir('css/app.css') }}" rel="stylesheet"> --}}

    <style>
        body
        {
            font-family: 'Lato';
        }

        .fa-btn
        {
            margin-right: 6px;
        }
    </style>
</head>
<body id="app-layout">
<div id="wrapper">
    @yield('banner')
    <div id="content">
    <nav class="navbar navbar-default navbar-static-top">
        <div class="container">
            <div class="navbar-header">

                <!-- Collapsed Hamburger -->
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                        data-target="#app-navbar-collapse">
                    <span class="sr-only">Toggle Navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>

                    <!-- Branding Image -->
                    <a class="navbar-brand" href="{{ url('/') }}">
                        <img src="{{ url('/')}}/images/Tweekleurig_A-LOGO/sRGB/SVG/A_logo_RGB.svg"/>
                    </a>
                </div>

                <div class="collapse navbar-collapse" id="app-navbar-collapse">
                    <!-- Left Side Of Navbar -->
                    <ul class="nav navbar-nav">
                        <li><a href="{{ url('/home') }}">Home</a>
                        <li><a href="{{ url('/project/dashboard') }}">Projectbeheer</a></li>
                        <li><a href="{{ url('/info') }}">Info</a></li>
                        <li><a href="{{ url('/contact') }}">Contact</a></li>
                    </ul>

                <!-- Right Side Of Navbar -->
                <ul class="nav navbar-nav navbar-right">
                    <!-- Authentication Links -->
                    @if (Auth::guest())
                        <li><a href="{{ url('/inloggen') }}">Login</a></li>
                        <li><a href="{{ url('/registreer') }}">Register</a></li>
                    @else
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                {{ Auth::user()->getName() }} <span class="caret"></span>
                            </a>

                            <ul class="dropdown-menu" role="menu">
                                <li><a href="{{ url('/uitloggen') }}"><i class="fa fa-btn fa-sign-out"></i>Logout</a></li>
                            </ul>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>

    @yield('content')
    </div>
</div>



@yield('footer')
<!-- JavaScripts -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
<script src="{{ url('/') }}/scripts/jquery-ui.min.js"></script>
<script src="{{ url('/') }}/scripts/site.js"></script>
<script>

</script>

@yield('pageJs')
{{-- <script src="{{ elixir('js/app.js') }}"></script> --}}
</body>
</html>
