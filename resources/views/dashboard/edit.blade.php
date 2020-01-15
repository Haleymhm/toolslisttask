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
<!-- INICIO FORMULARIO DE EDIAT RIPO DE ACTIVIDAD -->

		<div class="box">
			<div class="box-header with-border"> <h4 class="box-title">Editar Dashboard</h4> </div>
		<!-- /.box-header -->
		<!-- form start -->
			<form action="{{ route('dashboard.update',$dashboard->id) }}" autocomplete="off" method="POST">
				@csrf
				@method('PUT')

				<div class="box-body">
				    <div class="form-group col-xs-12 col-sd-12 col-md-12">
					    <label for="dbNombre" style="color:black;">Nombre</label>
				        <input type="text" class="form-control " id="dbividadNombre" name="dbnombre" value="{{ $dashboard->dbnom }}">
				    </div>

				    <div class="form-group col-xs-12 col-sd-12 col-md-12">
				      <label for="dbDescrip" style="color:black;">Descripcion</label>
                    <textarea class="form-control" id="tipoActividadDescrip" name="dbdescrip" id="dbdescrip" onkeyup="textAreaAdjust(this)" style="overflow:hidden"> {{$dashboard->dbdesc}} </textarea> <!--onkeyup="textAreaAdjust(this)"  style="overflow:hidden"-->
				    </div>


				    <div class="form-group col-xs-12 col-sd-6 col-md-2">
						<label for="Parent" style="color:black;">Posicion</label>
						<input type="number" name="dbpos" class="form-control" value="{{ $dashboard->dbpos }}">
				    </div>


					<div class="form-group col-xs-12 col-sd-6 col-md-2">
						<label for="dbividadStatus" style="color:black;">Status</label>
				        <select name="dbstatus" class="form-control select2" style="width: 100%;">
                            <option value="A" @if ($dashboard->status == 'A' ) {{"selected"}} @endif>Activo</option>
                            <option value="I" @if ($dashboard->status == 'I' ) {{"selected"}} @endif>Inactivo</option>
				        </select>
				    </div>

				</div><!-- /.box-body -->
				@include('dashboard.tablacontenido')
				<div class="box-footer">
					<a href="/dashboard" class="btn btn-default">Volver</a>
					<button type="submit" class="btn btn-info pull-right">Guardar</button>
				</div>
				<!-- /.box-footer -->

			</form>

		</div><!-- FIN FORMULARIO DE EDIAR TIPO DE ACTIVIDAD -->

@include('dashboard.addtipocontenido')
@include('dashboard.edittipocontenido')
@endsection

@section('implemantations')
<script src="{{ asset('bower_components/select2/dist/js/select2.full.min.js')}}"></script>

@endsection
