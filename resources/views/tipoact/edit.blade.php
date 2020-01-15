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
			<div class="box-header with-border"> <h4 class="box-title">Editar Tipos de Actividad</h4> </div>
		<!-- /.box-header -->
		<!-- form start -->
			<form action="{{ route('tipoact.update',$tipoactividad->id) }}" autocomplete="off" method="POST">
				@csrf
				@method('PUT')
				<input type="hidden" name="empresauid" value="{{Auth::user()->uidempresa }}" readonly />
				<input type="hidden" name="uid" value="{{$tipoactividad->uid }}" readonly />
				<div class="box-body">
				    <div class="form-group col-xs-12 col-sd-12 col-md-12">
					    <label for="tipoActividadNombre" >Tipo de Actividad</label>
				        <input type="text" class="form-control " id="tipoActividadNombre" name="tipoactnombre" value="{{$tipoactividad->titulo}}">
				    </div>

				    <div class="form-group col-xs-12 col-sd-12 col-md-12">
                      <label for="tipoActividadDescrip">Descripci&oacute;n</label>
                      <textarea type="text" class="form-control " id="tipoActividadNombre" name="tipoactdescrip" onkeyup="textAreaAdjust(this)" style="overflow:hidden">{{$tipoactividad->tipoactdescrip}}</textarea>

				    </div>

					<div class="form-group col-xs-12 col-sm-6 col-md-3">
				    	<label for="Parent" >Pertenece a</label>
				        <select name="parent" class="form-control select2" style="width: 100%;">
				        	<option value="0"></option>
				        	@foreach($grupotipoactividads as $gta)
				        		<option value="{{$gta->id}}" @if ($gta->id == $tipoactividad->parent ) {{ "selected" }} @endif >{{$gta->titulo}}</option>
					      	@endforeach
				        </select>
				    </div>

						<div class="form-group col-xs-12 col-sm-6 col-md-3">
							<label for="vistaInicial">Vista Inicial</label>
							<div class="radio">
								<label>Calendario<input type="radio" name="inictialView" id="optionsRadios1" value="cal" @if($tipoactividad->tvista=="cal"){{"checked"}} @endif></label>
								<label>Tabla<input type="radio" name="inictialView" id="optionsRadios2" value="lis"  @if($tipoactividad->tvista=="lis"){{"checked"}} @endif></label>
							</div>
						</div>

						<div class="form-group col-xs-12 col-sm-6 col-md-3">
							<label for="vistaInicial">Mostrar en Calendario</label>
							<div class="radio">
								<label>Si<input type="radio" name="mcal" id="optionsRadios1" value="SI" @if($tipoactividad->mcal=="SI"){{"checked"}} @endif></label>
								<label>No<input type="radio" name="mcal" id="optionsRadios2" value="NO"  @if($tipoactividad->mcal=="NO"){{"checked"}} @endif></label>
							</div>
						</div>

						<div class="form-group col-xs-12 col-sm-6 col-md-3">
							<label for="vistaInicial">Mostrar en Indicadores</label>
							<div class="radio">
								<label>Si<input type="radio" name="mind" id="optionsRadios1" value="SI" @if($tipoactividad->mind=="SI"){{"checked"}} @endif></label>
								<label>No<input type="radio" name="mind" id="optionsRadios2" value="NO"  @if($tipoactividad->mind=="NO"){{"checked"}} @endif></label>
							</div>
						</div>


				    <div class="form-group col-xs-12 col-sm-6 col-md-2">
						<label for="Parent">Posici&oacute;n</label>
						<input type="number" name="orden" class="form-control " value="{{ $tipoactividad->orden }}">
				    </div>

					<div class="form-group col-xs-12 col-sm-6 col-md-2">
                        <label for="tipoActividadStatus">Color</label>

                            <select name="tipoactcolor" id="color" class="form-control" required>
                              <option></option>
                              @foreach ($colores as $color)
                                  <option style="background-color:{{$color->color}}; color:#ffffff;" value="{{$color->color}}" @if ($tipoactividad->tipoactcolor  == $color->color ) {{"selected"}} @endif>{{$color->nombre}}</option>
                              @endforeach
                            </select>

					    <!-- <input type="color" name="tipoactcolor" class="form-control " value="{{$tipoactividad->tipoactcolor}}" required /> -->
                    </div>
                    <div class="form-group col-xs-12 col-sm-6 col-md-4">
                            <label for="icons" ><strong>Icono</strong></label>
                            <div class="col-sm-3 icon-form">
                                <input type="hidden" name="icono" id="icono" value="{{ $tipoactividad->icono }}">
                                <div class="icon-form"><i id="icon" class="{{ $tipoactividad->icono }}"></i></div>
                                <a href="#" class="btn btn-info btn-xs pull-right" data-toggle="modal" data-target="#iconsModal"><i class="fa fa-plus"></i></a>
                            </div>
                    </div>

                    <div class="form-group col-xs-12 col-sm-6 col-md-3">
                        <label for="vistaInicial">Mostrar en Indicadores</label>
                        <div class="radio">
                            <label>Actividad<input type="radio" name="comporta" id="optionsRadios1" value="1" @if($tipoactividad->comporta=="1"){{"checked"}} @endif></label>
                            <label>Lista Docs.<input type="radio" name="comporta" id="optionsRadios2" value="2"  @if($tipoactividad->comporta=="2"){{"checked"}} @endif></label>
                            <label>Doc. Unico<input type="radio" name="comporta" id="optionsRadios2" value="3"  @if($tipoactividad->comporta=="3"){{"checked"}} @endif></label>
                        </div>
					</div>
					
					<div class="form-group col-xs-12 col-sm-6 col-md-2">
						<label for="Parent">Prefijo</label>
						<input type="text" name="prefijo" class="form-control " value="{{ $tipoactividad->prefijo }}">
				    </div>

					<div class="form-group col-xs-12 col-sm-6 col-md-2">
						<label for="tipoActividadStatus" >Status</label>
				        <select name="tipoactstatus" class="form-control select2" style="width: 100%;">
									@if ($tipoactividad->status == 'A' )
									<option value="A" selected>Activo</option>
									@else
										<option value="A"  >Activo</option>
									@endif

									@if ($tipoactividad->status == 'I' )
									<option value="I" selected>Inactivo</option>
									@else
										<option value="I"  >Inactivo</option>
									@endif

				        </select>
				    </div>

				</div><!-- /.box-body -->
				@include('tipoact.tablacontenido')
				<div class="box-footer">
					<a href="/tipoact" class="btn btn-default">Volver</a>
					<button type="submit" class="btn btn-info pull-right">Guardar</button>
				</div>
				<!-- /.box-footer -->

			</form>

		</div><!-- FIN FORMULARIO DE EDIAR TIPO DE ACTIVIDAD -->
@include('partials.icons')
@include('tipoact.addtipocontenido')
@include('tipoact.edittipocontenido')
@endsection

@section('implemantations')
<script src="{{ asset('bower_components/select2/dist/js/select2.full.min.js')}}"></script>

@endsection
