@extends('layouts.app')
@section('notificaciones')
    @include('partials.notify-vencida')
    @include('partials.notify-day')
@endsection
@section('cbouniope')
  @include('partials.cbouniope')
  @include('partials.menuta')
@endsection
@section('content')

<div class="box box-solid with-border">
	<div class="box-header with-border"> <h3 class="box-title">Nueva Unidad Operativa</h3> </div>
<!-- /.box-header -->
<!-- form start -->
	<form class="form-horizontal" action="{{ route('uniop.store') }}" autocomplete="oN" method="POST">
		@csrf
		<div class="box-body">
			<input type="hidden" class="form-control" name="empresauid" id="empresauid" value=" {{Auth::user()->uidempresa }}">

		    <div class="form-group">
		      <label for="unidadoNombre" class="col-sm-2 control-label">Nombre de la Unidad</label>

		      <div class="col-sm-10">
		        <input type="text" class="form-control" id="unidadopnombre" name="unidadopnombre" placeholder="Nombre de la Unidad Operativa">
		      </div>
		    </div>


		    <!-- /.box-body -->
			  <div class="box-footer">
			    <a href="/uniop" class="btn btn-default">Volver</a>
			    <button type="submit" class="btn btn-primary pull-right">Guardar</button>
			  </div>
			  <!-- /.box-footer -->

		</div>
	</form>
</div>

@endsection
