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
<div class="col-xs-12" style=" padding-top:10px">
		<div class="box box-solid with-border">
			<div class="box-header with-border"> <h3 class="box-title">Agregar Tipo de Actividades</h3> </div>
		<!-- /.box-header -->
		<!-- form start -->
			<form class="form-horizontal" action="{{ route('tipoact.store') }}" autocomplete="off" method="POST">
				@csrf

				<div class="box-body">
					<input type="hidden" class="form-control" name="empresauid" value="{{Auth::user()->uidempresa }}" >
				    <div class="form-group">
				      <label for="tipoActividadNombre" class="col-sm-2 control-label">Tipo de Actividad</label>

				      <div class="col-sm-10">
				        <input type="text" class="form-control" id="tipoActividadNombre" name="tipoactnombre" placeholder="Nombre del Tipo de Actividad">
				      </div>
				    </div>

				    <div class="form-group">
				      <label for="tipoActividadDescrip" class="col-sm-2 control-label">Descripci&oacute;n</label>

				      <div class="col-sm-10">
				        <textarea class="form-control" id="tipoActividadDescrip" name="tipoactdescrip" placeholder="Descripcion" onkeyup="textAreaAdjust(this)" style="overflow:hidden"> </textarea>
				      </div>
				    </div>

					<div class="form-group">
						<label for="Parent " class="col-sm-2 control-label">Pertenece a</label>

						<div class="col-sm-5">
						<select name="parent" class="form-control select2">
							<option value="0">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option>
							@foreach($grupotipoactividads as $grupotipoactividad)
						  		<option value="{{$grupotipoactividad->id}}">{{$grupotipoactividad->titulo}}</option>
						  	@endforeach
						</select>
						</div>
					<label for="tipoActividadStatus" class="col-sm-2 control-label">Color</label>
                    <div class="col-sm-3">
                        <select name="color" class="form-control">
                        <option></option>
                        @foreach ($colores as $color)
                            <option style="background-color:{{$color->color}}; color:#ffffff;" value="{{$color->color}}">{{$color->nombre}}</option>
                        @endforeach
                        </select>
                    </div>
				    </div>

				    <!-- /.box-body -->
					  <div class="box-footer">
					    <a href="/tipoact" class="btn btn-default">Volver</a>
					    <button type="submit" class="btn btn-primary pull-right">Guardar</button>
					  </div>
					  <!-- /.box-footer -->

				</div>
			</form>
		</div>
</div>
@endsection
