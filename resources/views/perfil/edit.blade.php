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
			<div class="box-header with-border"> <h3 class="box-title">Editar Perfil</h3></div>

		<!-- form start -->
			<form class="form-horizontal" action="{{ route('perfil.update',$usuario->id) }}" autocomplete="off" method="POST" enctype="multipart/form-data">
				@csrf
				@method('PUT')
				 <input type="hidden" class="form-control" name="empresauid" id="empresauid" value="{{ $usuario->uidempresa  }}">
				<div class="box-body">

				    <div class="form-group col-md-3 col-sd-3 col-xs-11" >
					    <label for="unidadoNombre" >Nombre</label> <br>
					    <label style="color: black;">{{ $usuario->name }}</label>
                    </div>
                    <div class="form-group col-md-4 col-sd-3 col-xs-11">
					    <label for="unidadoNombre" >Email</label><br>
					    <label style="color: black;">{{ $usuario->email }} </label>
                    </div>
                    <div class="form-group col-md-3 col-sd-3 col-xs-12">
					    <label for="unidadoNombre" >Cargo</label> <br>
					    <label style="color: black;">{{ $usuario->cargo }}</label>
                    </div>

                    <div class="form-group col-md-2 col-sd-2 col-xs-2 pull-center">
                            <img src="{{ auth()->user()->getAvatarUrl() }}" id="avatarImage" class="thumbnail pull-right" alt="User Image" height="100px" width="100px" style="cursor:pointer;">
                    </div>

                    <div class="form-group col-md-5 col-sd-2 col-xs-6">
					    <label for="unidadoNombre" >Contraseña</label>
					    <input type="password" name="passw" class="form-control">
				    </div>



				    <div class="form-group col-md-5 col-sd-6 col-xs-12">
						<label for="vista" >Vista Inicial:</label>
			            <select id="vista" name="vista"  class="form-control select2" style="width: 90%;">
			                <option value="0" @if ($usuario->vista == 0) {{ "selected" }} @endif>Calendario</option>
			                <option value="1" @if ($usuario->vista == 1) {{ "selected" }} @endif>Indicadores</option>
			                <option value="2" @if ($usuario->vista == 2) {{ "selected" }} @endif>Carpeta</option>
			                <option value="3" @if ($usuario->vista == 3) {{ "selected" }} @endif>Resumen</option>
			            </select>

					</div><!--
				    <div class="form-group col-md-4 col-sd-6 col-xs-12">
				    	<label for="unidadstatus" >Zona Horaria</label>
				        <select name="timezone" class="form-control select2" style="width: 90%;">
				        	<option value="-4"  >-4</option>
				        	<option value="-3"  >-3</option>
				        	<option value="-2"  >-2</option>
				        	<option value="-1"  >-1</option>
				        	<option value="0" selected>0</option>
				        	<option value="1"  >1</option>
				        	<option value="2"  >2</option>
				        	<option value="3"  >3</option>
				        	<option value="4"  >4</option>
				        </select>
				    </div>

				    <div class="form-group col-md-4 col-sd-6 col-xs-12">
						<label for="langauges" >Idioma:</label>

			            <select id="langauges" name="langauges"  class="form-control select2" style="width: 90%;">
			                <option value="arabic">Arabic</option>
			                <option value="chinese">Chinese</option>
			                <option value="english">English</option>
			                <option value="french">French</option>
			                <option value="german">German</option>
			                <option value="portuguese">Portuguese</option>
			                <option value="russian">Russian</option>
			                <option value="spanish">Spanish</option>
			            </select>

					</div>form-group -->




				    </div><!-- /.box-body -->
					  <div class="box-footer">

							<button type="submit" class="btn btn-primary pull-right">Guardar</button>
							<a href="/home" class="btn btn-default">Volver</a>
					  </div>
					  <!-- /.box-footer -->
                </form>
			</div>



<form action="{{ url('perfil/updatephoto') }}" method="post" style="display: none" id="avatarForm" form-data>
    {{ csrf_field() }}
    <input type="file" id="avatarInput" name="photo">
</form>

@endsection
