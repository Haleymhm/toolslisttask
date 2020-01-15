<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">

  <title>{{ config('app.name') }} -
        @if (config('app.env')=="testing")
            Versión de Pruebas
        @else
            Versión de Producción
        @endif
  </title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" >

  <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
  <link rel="icon" href="{{ asset('dist/img/favicon.ico')}}" type="image/x-icon">
  <link rel="stylesheet" href="{{ asset('bower_components/bootstrap/dist/css/bootstrap.min.css')}}">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ asset('bower_components/font-awesome/css/font-awesome.min.css')}}">
  <!-- Ionicons -->
  <link rel="stylesheet" href="{{ asset('bower_components/Ionicons/css/ionicons.min.css')}}">
  @yield('cssDataTables')
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
  <style>
        #loader{ visibility:hidden;  }

}
        </style>
  @yield('libraries')

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!--Google Font -->
  <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Mina:300,400,600,700,300italic,400italic,600italic&display=swap" >
</head>

<body class="hold-transition skin-green-light sidebar-mini">
<div class="wrapper">

  <!-- Main Header -->
  <header class="main-header">

    <!-- Logo -->
    <a href="{{ asset('cpanel/admin')}}" class="logo" style="font-family: 'Mina', sans-serif;font-size:36px;font-weight:bold;">

      <!-- mini logo for sidebar mini 50x50 pixels <img src="{{ asset('dist/img/logo_siglas.png')}}" alt="">-->
      <span class="logo-mini"><strong>IT</strong></span>
      <!-- logo for regular state and mobile devices <img src="{{ asset('dist/img/logo_text.png')}}" alt="">-->
      <span class="logo-lg"><strong>{{ config('app.name', 'BuntTech 2019') }}</strong></span>
    </a>

    <!-- Header Navbar -->
    <nav class="navbar navbar-static-top" role="navigation">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>
      <!-- Navbar Right Menu -->
      <div class="navbar-custom-menu">

        <ul class="nav navbar-nav">

        <!-- NOTIFICACIONES BARRA SUPERIOR -->
          @yield('notificaciones')
        <!-- FIN NOTIFICACIONES BARRA SUPERIOR -->
          <!-- Tasks Menu -->



          <!-- User Account Menu -->
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <img src="{{ asset('upload/avatar').Auth::user()->photo}}" class="user-image" alt="User Image">
              <span class="hidden-xs">{{ Auth::user()->name }}</span>
            </a>
            <ul class="dropdown-menu">
              <!-- The user image in the menu -->
              <li class="user-header">
                <img src="{{ asset('upload/avatar').Auth::user()->photo}}" class="img-thumbnail" alt="User Image">
                <p>
                  <strong> {{ Auth::user()->name }} X</strong><br />
                  <small><strong>{{ Auth::user()->cargo }}</strong><br />{{ Auth::user()->email }}</small>
                </p>
              </li>
              <!-- Menu Body -->
              <li class="user-body">
                <div class="row">
                  <div class="col-xs-4 text-center">

                  </div>
                  <div class="col-xs-4 text-center">

                  </div>
                  <div class="col-xs-4 text-center">

                  </div>
                </div>
                <!-- /.row -->
              </li>
              <!-- Menu Footer-->
              <li class="user-footer">
                <div class="pull-left">
                    <a href="{{route('perfil.edit',Auth::user()->id)}}" class="btn btn-default btn-flat">Perfil</a>
                </div>
                <div class="pull-right">
                  <a class="btn btn-default btn-flat" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>
                 <!-- <a href="#" class="btn btn-default btn-flat">Salir</a> -->
                 <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                 </form>
                </div>
              </li>
            </ul>
          </li>
          <!-- Control Sidebar Toggle Button -->
          <li>
            <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
          </li>
        </ul>
      </div>
    </nav>
  </header>
  <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">

    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">

      <!-- Sidebar Menu -->
      <ul class="sidebar-menu" data-widget="tree">
        <!--  NINICIO MENU IZQUIERDA -->
        <label>
            @if (config('app.env')=="testing")
                <br />
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <strong>*  Versión  de Pruebas  *</strong>
            @endif
        </label>
        <label>&nbsp;&nbsp;&nbsp;</label>
        <li>
            <a href="{{ asset('cpanel/admin')}}" >
                <i class="fa fa-university"></i><span> Empresas</span>
            </a>
        </li>
        <li>
            <a href="{{ route('roles.index') }}">
                <i class="fa fa-users"></i><span> Roles</span>
            </a>
        </li>
        <!--  FIN DE MENU IZQUIERDA -->
      </ul><!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">

    <!-- Main content -->
    <section class="content">
      <!--| contenedor central de la aplicacion PUNTO DE CONTROL-->
       <!--  INICIO DE NOTIFICACIONES -->
       <!--  FIN DE NOTIFICACIONES -->

        @yield('content')

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- Main Footer -->
  <footer class="main-footer no-print">
    <!-- To the right -->
    <div class="pull-right hidden-xs">
      Desarrollo web
    </div>
    <!-- Default to the left -->
    <strong>Copyright &copy; 2019 <a href="#">BuntTech</a>.</strong> Todos los derechos reservados.
  </footer>


  <!--  INICIO DE SIDEBAR -->
  <!--  FIN DE SIDEBAR -->

  <div class="control-sidebar-bg"></div>
</div>



<!-- ./wrapper -->

@yield('formoculto')

<!-- REQUIRED JS SCRIPTS -->

<!-- jQuery 3 -->
<script src="{{ asset('bower_components/jquery/dist/jquery.min.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.1/bootstrap3-typeahead.min.js"></script>

<!-- Bootstrap 3.3.7 -->
<script src="{{ asset('bower_components/bootstrap/dist/js/bootstrap.min.js')}}"></script>
<script src="{{ asset('js/appselect.js')}}"></script>
<!-- Material Design -->
<script src="{{ asset('dist/js/material.min.js')}}"></script>
<script src="{{ asset('dist/js/ripples.min.js')}}"></script>
<script src="{{ asset('bower_components/select2/dist/js/select2.full.min.js')}}"></script>

<script src="{{ asset('bower_components/moment/min/moment.min.js')}}"></script>
<script src="{{ asset('bower_components/bootstrap-datepicker/js/bootstrap-datepicker.js')}}"></script>
<script src="{{ asset('bower_components/bootstrap-datepicker/js/locales/bootstrap-datepicker.all.js')}}"></script>
<!-- iCheck 1.0.1 -->
<script src="{{ asset('dist/plugins/iCheck/icheck.min.js')}}"></script>

<script src="{{ asset('dist/js/alert.js')}}"></script>
<script src="http://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap2-toggle.min.js"></script>

<script>

  $( function() {

    $.material.init();

    $('.select2').select2();

     //Flat red color scheme for iCheck
    $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
      checkboxClass: 'icheckbox_flat-green',
      radioClass   : 'iradio_flat-green'
    })

  });


</script>
@yield('implemantations')


<!-- AdminLTE App -->
<script src="{{ asset('dist/js/adminlte.js')}}"></script>
<script src="{{ asset('js/cpanel.js')}}"></script>

@yield('datagrafics')
@yield('jsDataTables')


</body>
</html>
