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
		<div class="box box-solid box-default">
			<div class="box-header with-border"> <h3 class="box-title">Crear Empresa</h3> </div>
		<!-- /.box-header -->
		<!-- form start -->
			<form class="form-horizontal" action="{{ route('empresa.store') }}" autocomplete="on" method="POST">
				@csrf
				<div class="box-body">
					<div class="form-group">
				      <label for="empresaNombre" class="col-sm-1 control-label">N° Serie:</label>

				      <div class="col-sm-11">
				        <input type="text" class="form-control" id="txtEmpreasNombre" name="uiduser"  value="{{ Auth::user()->id }}" readonly />
				      </div>
				    </div>

				    <div class="form-group">
				      <label for="empresaNombre" class="col-sm-1 control-label">Nombre:</label>

				      <div class="col-sm-11">
				        <input type="text" class="form-control" id="txtEmpreasNombre" name="empresanombre"  value="{{ old('empresanombre') }}" required />
				      </div>
				    </div>

				    <div class="form-group">
				      <label for="empresaNombre" class="col-sm-1 control-label">Dirección:</label>

				      <div class="col-sm-11">
				      	<textarea class="form-control" name="empresadireccion" required />{{ old('empresadireccion') }}</textarea>
				      </div>
				    </div>


					<div class="form-group">
				    	<label for="empresaNombre" class="col-sm-1 control-label">Telefono:</label>
				    	<div class="col-sm-2">
				      		<input type="text" name="empresatelefono" class="form-control" pattern="[0-9]{2}-[0-9]{3}-[0-9]{3}-[0-9]{4}" placeholder="00-000-000-0000" value="{{ old('empresatelefono') }}" required />
				      	</div>

				      <label for="empresastatus" class="col-sm-1 control-label">Email:</label>

				      <div class="col-sm-4">
				        <input type="email" name="empresaemail" class="form-control" value="{{ old('empresaemail') }}"  required />
				      </div>

				      <label for="empresalogo" class="col-sm-1 control-label">logo:</label>

				      <div class="col-sm-3">

				      </div>
				    </div>

				    <!-- /.box-body -->
					  <div class="box-footer">
					    <a href="/empresa" class="btn btn-default">Volver</a>
					    <button type="submit" class="btn btn-primary pull-right">Guardar</button>
					  </div>
					  <!-- /.box-footer -->

				</div>
			</form>
		</div>

@endsection

