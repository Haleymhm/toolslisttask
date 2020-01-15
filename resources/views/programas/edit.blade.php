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
	<div class="box-header with-border"> <h3 class="box-title">Editar Programas</h3> </div>
<!-- /.box-header -->
<!-- form start -->
	<form class="form-horizontal" action="{{ route('programas.update',$programastbl->id) }}" autocomplete="off" method="POST">
		@csrf
		@method('PUT')
		<div class="box-body">
            <div class="form-group">
                <label for="unidadoNombre" class="col-sm-2 control-label">Nombre</label>

                <div class="col-sm-10">
                <input type="text" class="form-control" id="nomb" name="nomb" value="{{ $programastbl->prognombre}}">
                </div>
            </div>

            <div class="form-group">
                <label for="unidadoNombre" class="col-sm-2 control-label">Descripci&oacute;n</label>

                <div class="col-sm-10">
                    <textarea class="form-control" id="descrip" name="descrip" placeholder="Descripci&oacute;n del Programa" onkeyup="textAreaAdjust(this)" style="overflow:hidden">{{ $programastbl->progdescrip}}</textarea>
                </div>
            </div>

		    <div class="form-group">
		      <label for="unidadstatus" class="col-sm-2 ">Status</label>

		      <div class="col-sm-10">
		        <select name="status" class="form-control select2">
                    <option value="A" @if ($programastbl->status == 'A' ) {{"selected"}} @endif>Activo</option>
                    <option value="I" @if ($programastbl->status == 'I' ) {{"selected"}} @endif>Inactivo</option>
		        </select>
		      </div>
            </div>

            <div class="form-group">
                <label for="unidadstatus" class="col-sm-2 "><strong>Color</strong></label>

                <div class="col-sm-3">
                  <select name="color" id="color" class="form-control" >
                    <option></option>
                    @foreach ($colores as $color)
                        <option style="background-color:{{$color->color}}; color:#ffffff;" value="{{$color->color}}" @if ($programastbl->progcolor  == $color->color ) {{"selected"}} @endif>{{$color->nombre}}</option>
                    @endforeach
                  </select>
                </div>
                <label for="icons" class="col-sm-2"><strong>Icono</strong></label>
                <div class="col-sm-3 icon-form">
                    <input type="hidden" name="icono" id="icono" value="{{$programastbl->progicon}}">
                    <div class="icon-form"><i id="icon" class="{{$programastbl->progicon}}"></i></div>
                    <a href="#" class="btn btn-info btn-xs pull-right" data-toggle="modal" data-target="#iconsModal"><i class="fa fa-plus"></i></a>
                </div>
              </div>

		    <!-- /.box-body -->
			  <div class="box-footer">
			    <a href="/programas" class="btn btn-default">Volver</a>
			    <button type="submit" class="btn btn-primary pull-right">Guardar</button>
			  </div>
			  <!-- /.box-footer -->

		</div>
	</form>
</div>

@include('partials.icons')
@endsection
