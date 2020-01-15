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
			<div class="box-header with-border"> <h3 class="box-title">Grupos de Tipos de Actividad</h3> </div>
		<!-- /.box-header -->
		<!-- form start -->
			<form class="form-horizontal" action="{{ route('grupotipoact.store') }}" autocomplete="on" method="POST">
				@csrf

				<div class="box-body">
					<input type="hidden" class="form-control" name="empresauid" value="{{Auth::user()->uidempresa }}" >
				    <div class="form-group">
				      <label for="tipoActividadNombre" class="col-sm-2">Nombre del Grupo</label>

				      <div class="col-sm-10">
				        <input type="text" class="form-control" id="Nombre" name="nombre" placeholder="Nombre del Grupo" value="{{ old('nombre') }}">
				      </div>
				    </div>

				    <div class="form-group">
				      <label for="Descripcion" class="col-sm-2">Descripci&oacute;n</label>

				      <div class="col-sm-10">
				        <textarea class="form-control" id="tipoActividadDescrip" name="descripcion" onkeyup="textAreaAdjust(this)" style="overflow:hidden" placeholder="Descripcion">{{ old('descripcion') }}</textarea>
				      </div>
				    </div>

					<div class="form-group">
				      <label for="Parent " class="col-sm-2">Pertenece a</label>

				      <div class="col-sm-5">
				        <select name="parent" class="form-control select2">
				        	<option value="0">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option>
				        	@foreach($grupotipoactividads as $grupotipoactividad)
					      		<option value="{{$grupotipoactividad->id}}">{{$grupotipoactividad->titulo}}</option>

					      	@endforeach
				        </select>
				      </div>
						<label for="Parent " class="col-sm-2">Posici&oacute;n</label>
						<div class="col-sm-3">
							<input type="number" name="orden" class="form-control" value="1">

                        </div>

                    </div>
                    <div class="form-group col-xs-12 col-sm-4 col-lg-4">
                        <label for="icons" class="col-sm-2">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Icono</label>
                        <div class="col-sm-10 icon-form">
                            <input type="hidden" name="icono" id="icono">&nbsp;&nbsp;&nbsp;&nbsp;
                            <div class="icon-form"><i id="icon" class="fa fa-plus"></i></div>
                            <a href="#" class="btn btn-info btn-xs pull-right" data-toggle="modal" data-target="#iconsModal"><i class="fa fa-plus"></i></a>
                        </div>
                    </div>


				    <!-- /.box-body -->
					  <div class="box-footer">
                        <div class="form-group col-xs-12 col-sm-12 col-lg-12">
					        <a href="/grupotipoact" class="btn btn-default">Volver</a>
                            <button type="submit" class="btn btn-primary pull-right">Guardar</button>
                        </div>
					  </div>
					  <!-- /.box-footer -->

				</div>
			</form>
		</div>
@include('partials.icons')
@endsection
