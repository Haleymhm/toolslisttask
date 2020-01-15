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
	<div class="box-header with-border"> <h3 class="box-title">Editar Unidad Operativa</h3> </div>
<!-- /.box-header -->
<!-- form start -->
	<form class="form-horizontal" action="{{ route('uniop.update',$unidadops->id) }}" autocomplete="off" method="POST">
		@csrf
		@method('PUT')
		<div class="box-body">
			<input type="hidden" class="form-control" name="empresauid" id="empresauid"value="{{ $unidadops->unidadopuid }}">

		    <div class="form-group">
		      <label for="unidadoNombre" class="col-sm-2 control-label">Nombre de la Unidad</label>

		      <div class="col-sm-10">
		        <input type="text" class="form-control" id="unidadopnombre" name="unidadopnombre" value="{{ $unidadops->unidadopnombre }}">
		      </div>
		    </div>
		    <div class="form-group">
		      <label for="unidadstatus" class="col-sm-2 control-label">Estatus de la Unidad</label>

		      <div class="col-sm-10">
		        <select name="unidadopstatus" class="form-control select2">
							@if ($unidadops->unidadopstatus == 'A' )
							<option value="A" selected>Activo</option>
							@else
								<option value="A"  >Activo</option>
							@endif

		        	@if ($unidadops->unidadopstatus == 'I' )
							<option value="I" selected>Inactivo</option>
							@else
								<option value="I"  >Inactivo</option>
							@endif

		        </select>
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
