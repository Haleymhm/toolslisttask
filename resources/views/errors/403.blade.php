<!DOCTYPE html>

<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">

        <title>{{ config('app.name', 'BuntTech 2019') }}</title>
        <!-- Tell the browser to be responsive to screen width -->
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
        <link rel="icon" href="{{ asset('dist/img/favicon.ico')}}" type="image/x-icon">
        <link rel="stylesheet" href="{{ asset('bower_components/bootstrap/dist/css/bootstrap.min.css')}}">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="{{ asset('bower_components/font-awesome/css/font-awesome.min.css')}}">
        <!-- Ionicons -->
        <link rel="stylesheet" href="{{ asset('bower_components/Ionicons/css/ionicons.min.css')}}">
        <!-- Theme style -->
        <link rel="stylesheet" href="{{ asset('dist/css/AdminLTE.css')}}">


        <!-- Material Design -->
        <link rel="stylesheet" href="{{ asset('dist/css/bootstrap-material-design.css')}}">
        <link rel="stylesheet" href="{{ asset('dist/css/ripples.min.css')}}">
        <link rel="stylesheet" href="{{ asset('dist/css/MaterialAdminLTE.css')}}">
        <!-- AdminLTE Skins. We have chosen the skin-blue for this starter
                page. However, you can choose any other skin. Make sure you
                apply the skin class to the body tag so the changes take effect. -->
        <!-- iCheck for checkboxes and radio inputs -->
        <link rel="stylesheet" href="{{ asset('dist/plugins/iCheck/all.css')}}">
        <link rel="stylesheet" href="{{ asset('dist/css/skins/all-md-skins.css')}}">
        <link rel="stylesheet" href="{{ asset('bower_components/select2/dist/css/select2.min.css')}}">
        <link rel="stylesheet" href="{{ asset('bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.css')}}">
        <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap2-toggle.min.css" rel="stylesheet">

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->

        <!--Google Font -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Mina:300,400,600,700,300italic,400italic,600italic&display=swap">
    </head>

    <body class="hold-transition skin-green-light sidebar-mini">
        <div class="wrapper">
                <header class="main-header">
                        <!-- Logo -->
                        <a href="{{ route('home.index') }}" class="logo" style="font-family: 'Mina', sans-serif;font-size:36px;font-weight:bold;">
                            <!-- mini logo for sidebar mini 50x50 pixels <img src="{{ asset('dist/img/logo_siglas.png')}}" alt="">-->
                            <span class="logo-mini"><strong>IT</strong></span>
                            <!-- logo for regular state and mobile devices <img src="{{ asset('dist/img/logo_text.png')}}" alt="">-->
                            <span class="logo-lg"><strong>{{ config('app.name', 'BuntTech 2019') }}</strong></span>
                        </a>

                        <nav class="navbar navbar-static-top" role="navigation">
                            <div class="navbar-custom-menu">
                            <ul class="nav navbar-nav">
                                <li class="dropdown user user-menu">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                    <img src="{{ asset('upload/avatar').Auth::user()->photo}}" class="user-image" alt="User Image">
                                    <span class="hidden-xs">{{ Auth::user()->name }}</span>
                                </a>
                                </li>
                            </ul>
                            </div>
                        </nav>
                    </header>

            <div class="error-page">
            <h2 class="headline text-red">Directiva 403</h2>
            <div class="error-content">
                <h3><i class="fa fa-expeditedssl text-red"></i> <strong>Detente! acceso no autorizado.</strong></h3>
                <p>Por favor comun&iacute;cate con el administrador del sistema.</p>
                <a href="/actividad" class="text-light-blue"><strong>Ir al inico</strong> </a>
            </div>
            <!-- /.error-content -->
            </div>
        </div>
    </body>
</html>


