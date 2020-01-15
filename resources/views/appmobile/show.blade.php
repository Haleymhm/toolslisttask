<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">

  <title>{{ config('app.name', 'BuntTech 2019') }} | BuntTech 2019</title>
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
  <link rel="stylesheet" href="{{ asset('dist/css/skins/skin-black-light.css')}}">
  <link rel="stylesheet" href="{{ asset('bower_components/select2/dist/css/select2.min.css')}}">

  <link rel="stylesheet" href="{{ asset('bower_components/bootstrap-daterangepicker/daterangepicker.css')}}">
  <link rel="stylesheet" href="{{ asset('bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css')}}">
  <link rel="stylesheet" href="{{ asset('bower_components/timepicker/timepicker.css')}}">
  <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap2-toggle.min.css" rel="stylesheet">
  <style>
    .toggle.ios, .toggle-on.ios, .toggle-off.ios { border-radius: 20px; }
    .toggle.ios .toggle-handle { border-radius: 20px; }
  </style>
  @yield('libraries')

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
  <link rel="stylesheet" href="{{ asset('bower_components/colorbox/colorbox.css')}}">
  <style>
    .toggle.ios, .toggle-on.ios, .toggle-off.ios { border-radius: 20px; }
    .toggle.ios .toggle-handle { border-radius: 20px; }
  </style>
  <!-- Google Font -->
  <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>

<body class="hold-transition skin-green-light sidebar-mini">
@foreach ($Tipoacts as $item)
    {{$item->titulo }}
@endforeach


<div class="box box-solid box-default">

		<form class="form-horizontal"  autocomplete="off" method="POST" enctype="multipart/form-data" accept-charset="UTF-8" files="true" >

			<div class="box box-solid box-default box-body">

				@foreach ($contenidos as $key => $conte)

					@if ($conte->tipodato == "text")
						<div class="form-group col-md-6 col-sd-12 col-xs-12">
							<div class="col-md-12 col-sd-12 col-xs-12">
						      <label for="tipoActividadDescrip">{{$conte->etiqueta}}</label>
						      <input type="text" class="form-control " id="{{$conte->id}}" name="valortext[]" value="{{$conte->valortexto}}" />
						    </div>
					    	<input type="hidden" name="registro[]" value="{{$conte->id}}">
					    	<input type="hidden" name="tipodato[]" value="{{$conte->tipodato}}">
					    </div>
				    @endif

					@if ($conte->tipodato == "textarea")
						<div class="form-group col-md-12 col-sd-12 col-xs-12">
							<div class="col-md-12 col-sd-12 col-xs-12">
							    <label for="tipoActividadDescrip">{{$conte->etiqueta}}</label>
							    <textarea name="valortext[]" class="form-control" id="{{$conte->id}}">{{$conte->valortexto}}</textarea>
					    	</div>
					      <input type="hidden" name="registro[]" value="{{$conte->id}}">
					      <input type="hidden" name="tipodato[]" value="{{$conte->tipodato}}">
					  </div>
                    @endif

				    @if ($conte->tipodato == "numeric")
						<div class="form-group col-md-6 col-sd-12 col-xs-12">
							<div class="col-md-12 col-sd-12 col-xs-12">
							    <label for="tipoActividadDescrip">{{$conte->etiqueta}}</label>
							    <input type="numeric" class="form-control "  pattern="[0-9]+" id="{{$conte->id}}" name="valortext[]" value="<?php echo number_format($conte->valornumero); ?>" />
							</div>
						    <input type="hidden" name="registro[]" value="{{$conte->id}}">
						    <input type="hidden" name="tipodato[]" value="{{$conte->tipodato}}">
					  </div>
                    @endif

				    @if ($conte->tipodato == "date")
						<div class="form-group col-md-4 col-sm-6 col-xs-6">
							<div class="col-md-12 col-sd-12 col-xs-12">
									<label for="tipoActividadDescrip">{{$conte->etiqueta}}</label>

									<div class="input-group date">
										<input type="text" name="valortext[]" class="form-control" value="<?php $d=date_create($conte->valorfecha); echo date_format($d,'d-m-Y'); ?>" id="date{{$conte->id}}">
										<div class="input-group-addon"> <span class="glyphicon glyphicon-calendar"></span> </div>
									</div>

							   <!-- <input type="date" class="form-control " id="tipoActividadNombre" name="valortext[]" value="{{$conte->valorfecha}}" />-->
					    	</div>
					    	<input type="hidden" name="registro[]" value="{{$conte->id}}">
					    	<input type="hidden" name="tipodato[]" value="{{$conte->tipodato}}">
					    </div>
                    @endif

				    @if ($conte->tipodato == "monto")
						<div class="form-group col-md-6 col-sd-12 col-xs-12">
							<div class="col-md-12 col-sd-12 col-xs-12">
							    <label for="tipoActividadDescrip">{{$conte->etiqueta}}</label>
							    <input type="numeric" class="form-control" pattern="[0-9.]+"  id="{{$conte->id}}" name="valortext[]" value="<?php echo number_format($conte->valornumero,2); ?>" />
							</div>
							<input type="hidden" name="registro[]" value="{{$conte->id}}">
							<input type="hidden" name="tipodato[]" value="{{$conte->tipodato}}">

					  </div>
                    @endif


				    @if ($conte->tipodato == "lista")
						<div class="form-group col-md-6 col-sd-12 col-xs-12">
							<div class="col-md-12 col-sd-12 col-xs-12">
						      <label for="tipoActividadDescrip">{{$conte->etiqueta}}</label>
						      <select class="form-control select2" name="valortext[]" id="{{$conte->id}}">
						      	<option value="0">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option>
						      		@foreach ($elementos as $elemento)
										@if($elemento->listadouid==$conte->idlista)
											<option value="{{$elemento->id}}" @if($elemento->id==$conte->valorlista) {{ "selected" }} @endif > {{$elemento->elemnombre }} </option>
										@endif
						      		@endforeach
						      </select>
					    	</div>
					      <input type="hidden" name="registro[]" value="{{$conte->id}}">
					      <input type="hidden" name="tipodato[]" value="{{$conte->tipodato}}">
					    </div>
                    @endif


					@if ($conte->tipodato == "documento")
						<br class="clearfix col-md-12 col-sm-12 col-xs-12" />
						<div class="col-md-12 col-sm-12 col-xs-12">
                            <label id="documentFile{{$conte->id}}" id_cont="{{$conte->id}}" class="file_upload btn btn-primary  pull-right"><i class="fa fa-plus"></i> Examinar</label>
                            <h4 for="tipoActividadDescrip" class="box-title">{{$conte->etiqueta}}</h4>
                        </div>
                        <input type="hidden" name="registro[]" value="{{$conte->id}}">
						<input type="hidden" name="tipodato[]" value="{{$conte->tipodato}}">
                    @endif

                    @if ($conte->tipodato == "titulo")
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <h3 class="box-title"> {{$conte->etiqueta}}</h3>
                        </div>
                    @endif

                    @if ($conte->tipodato == "desing")
                        <br class="clearfix col-md-12 col-sm-12 col-xs-12" />
                    @endif

				@endforeach



			</div>
			<div class="box-footer">

				<a href="" class="btn btn-default">Volver</a>
				<button type="submit" class="btn btn-primary pull-right">Guardar</button>
			</div><!-- /.box-footer-->
			</form>

		</div>
		<!-- /.box-body -->

	</div>





</div>
<script src="{{ asset('bower_components/colorbox/colorbox.js')}}"></script>

<script>

	$( function() {



        @foreach ($contenidos as $key => $conte)
            @if ($conte->tipodato == "date")
                $('#date{{$conte->id}}').datepicker({
                    autoclose: true,
                    format: 'dd-mm-yyyy',
                    language: 'es'

                    });
            @endif
        @endforeach
    });

</script>

<!-- REQUIRED JS SCRIPTS -->

<!-- jQuery 3 -->
<script src="{{ asset('bower_components/jquery/dist/jquery.min.js')}}"></script>
<!-- <script src="http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.js"></script> -->

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

<script src="{{ asset('bower_components/timepicker/timepicker.js')}}"></script>
<script src="{{ asset('dist/js/alert.js')}}"></script>
<script src="http://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap2-toggle.min.js"></script>

<script>
  $( function() {

    $.material.init();

    $('.select2').select2();

    $('#dateinicioAC').datepicker({
      autoclose: true,
      format: 'dd-mm-yyyy',
      language: 'es'
    })

    $('#datefinAC').datepicker({
      autoclose: true,
      format: 'dd-mm-yyyy',
      language: 'es',
     })



  });


</script>

<!-- AdminLTE App -->
<script src="{{ asset('dist/js/adminlte.js')}}"></script>



</body>
</html>
