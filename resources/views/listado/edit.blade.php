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
			<div class="box-header with-border"> <h4 class="box-title">Editar Listado</h4> </div>
		<!-- /.box-header -->
		<!-- form start -->
			<form action="{{ route('listado.update',$listados->id) }}" autocomplete="off" method="POST">
				@csrf
				@method('PUT')
				<input type="hidden" name="empresauid" value="{{Auth::user()->uidempresa }}" readonly />

				<div class="box-body">
				    <div class="form-group col-md-12 col-sm-12 col-xs-12">
					    <label style="color:black;" >Nombre</label>
				        <input type="text" class="form-control " id="tipoActividadNombre" name="listnombre" value="{{$listados->nombrelista }}">
				    </div>

				    <div class="form-group col-md-12 col-sm-12 col-xs-12">
                      <label style="color:black;">Descripci&oacute;n</label>
                      <textarea class="form-control" id="tipoActividadDescrip" name="listdescrip" onkeyup="textAreaAdjust(this)" style="overflow:hidden"> {{$listados->descplista}}</textarea>

				    </div>


					<div class="form-group col-md-3 col-sm-6 col-xs-6">
						<label style="color:black;">Status</label>
				        <select name="tipoactstatus" class="form-control select2">
									@if ($listados->status == 'A' )
									<option value="A" selected>Activo</option>
									@else
										<option value="A"  >Activo</option>
									@endif

									@if ($listados->status == 'I' )
									<option value="I" selected>Inactivo</option>
									@else
										<option value="I"  >Inactivo</option>
									@endif

				        </select>
                    </div>

                    <div class="form-group col-xs-12 col-sm-6 col-md-6">
							<label style="color:black;">Ver como</label>
							<div class="radio">
								<label>Listado            <input type="radio" name="inictialView" id="optionsRadios1" value="lis" @if($listados->ver =="lis"){{"checked"}} @endif></label>
                                <label>Opciones Vertical  <input type="radio" name="inictialView" id="optionsRadios2" value="opv" @if($listados->ver =="opv"){{"checked"}} @endif  ></label>
                                <label>Opciones Horirontal<input type="radio" name="inictialView" id="optionsRadios3" value="oph" @if($listados->ver =="oph"){{"checked"}} @endif ></label>
							</div>
						</div>

				</div><!-- /.box-body -->
				@include('listado.tablacontenido')
				<div class="box-footer">
					<a href="/listado" class="btn btn-default">Volver</a>
					<button type="submit" class="btn btn-info pull-right">Guardar</button>
				</div>
				<!-- /.box-footer -->

			</form>

        </div><!-- FIN FORMULARIO DE EDIAR TIPO DE ACTIVIDAD -->

@include('listado.addtipocontenido')
@include('listado.edittipocontenido')
@endsection
