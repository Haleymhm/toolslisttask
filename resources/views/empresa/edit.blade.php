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
		    <div class="box-header with-border"> <h3 class="box-title">Datos de la Empresa </h3> </div>

		<!-- form start -->
			<form class="form-horizontal" action="{{ route('empresa.update',$empresas->id) }}" autocomplete="off" method="POST">
				@csrf
				@method('PUT')
				<div class="box-body">
                    <div class="form-group col-md-8 col-sd-8 col-xs-8">
                        <label for="empresaNombre">N° de Identificaci&oacute;n:</label>
                        <input type="text" class="form-control col-md-5" id="txtEmpreasNombre" name="rutrif"  value="{{$empresas->rutrif}}"/>
                    </div>

					<div class="form-group col-md-4 col-sd-4 col-xs-4"">
						<img src="{{ $empresas->getLogoUrl() }}" id="logoImage" class="thumbnail pull-right" alt="User Image" height="100px" width="100px" style="cursor:pointer;">
				  	</div>

					<div class="form-group col-md-12 col-sd-12 col-xs-12"">
				        <label for="empresaNombre">Nombre:</label>
					    <input type="text" class="form-control" id="txtEmpreasNombre" name="empresanombre"  value="{{$empresas->empresanombre}}" required />
				    </div>

				    <div class="form-group col-md-12 col-sd-12 col-xs-12"">
				        <label for="empresaNombre">Direcci&oacute;n:</label>
				        <textarea class="form-control" name="empresadireccion" onkeyup="textAreaAdjust(this)" data-resizable="true" style="overflow:hidden">{{$empresas->empresadireccion}}</textarea>
				    </div>

					<div class="form-group col-md-12 col-sd-12 col-xs-12"">
				        <label for="empresaNombre">Tel&eacute;fono:</label>
				        <input type="text" name="empresatelefono" class="form-control" value="{{$empresas->empresatelefono}}" />
					</div>

					<div class="form-group col-md-12 col-sd-12 col-xs-12"">
				        <label for="empresastatus">Email:</label>
				        <input type="email" name="empresaemail" class="form-control" value="{{$empresas->empresaemail}}" required />
					</div>

                </div>

                <div class="box-footer">
                    <a href="/" class="btn btn-default">Volver</a>
                    <button type="submit" class="btn btn-primary pull-right">Guardar</button>
                </div>
			</form>
        </div>

		<form action="{{ url('empresa/updatelogo') }}" method="post" style="display: none" id="logoForm" form-data>
			{{ csrf_field() }}
			<input type="file" id="logoInput" name="photo">
	</form>
@endsection

