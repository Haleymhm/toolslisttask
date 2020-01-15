@extends('layouts.app')
@section('cbouniope')
  @include('partials.cbouniope')
  @include('partials.menuta')
@endsection
@section('notificaciones')
    @include('partials.notify-vencida')
    @include('partials.notify-day')
@endsection
@section('content')

<div class="box">
	<div class="box-header with-border"> <h3 class="box-title">Crear Usuario</h3> </div>
<!-- /.box-header -->
<!-- form start -->
	<form class="form-horizontal" action="{{ route('cusuario.store')}}" autocomplete="off" method="POST" id="createuser">
		@csrf

		 <input type="hidden" class="form-control" name="uidempresa" id="empresauid" value="{{Auth::user()->uidempresa }}">
		<div class="box-body">
		    <div class="form-group">
		      <label for="unidadoNombre" class="col-sm-2" style="color:black;">Nombre</label>

		      <div class="col-sm-10">
		        <input type="text" class="form-control" id="name" name="name" >
		      </div>
		    </div>
		    <div class="form-group">
		      <label for="unidadoNombre" class="col-sm-2" style="color:black;">Email</label>

		      <div class="col-sm-10">
		        <input type="email" class="form-control" id="unidadopnombre" name="email" >
		      </div>
		    </div>
		    <div class="form-group">
		      <label for="unidadoNombre" class="col-sm-2" style="color:black;">Cargo</label>

		      <div class="col-sm-10">
		        <input type="text" class="form-control" id="unidadopnombre" name="cargo">
		      </div>
		    </div>
		    <div class="form-group">
		      <label for="empresaNombre" class="col-sm-2" style="color:black;">Selecione El Rol:</label>

		      <div class="col-sm-10">
                @foreach($roles as $rol)
                @if (($rol->slug=="root") or ($rol->slug=="visitante"))

                @else
                    <ul class="list-unstyled">
                        <li> <label style="color:black;"><input name="roles" type="radio" class="flat-red" value="{{$rol->id}}">
                            &nbsp;{{$rol->name}}&nbsp;<em>&nbsp;&nbsp;{{$rol->description}} </em>
                        </label></li>
                    </ul>
                @endif
            @endforeach
		    	</div>
				</div>
				<div class="form-group">
						<label for="tipoActividadNombre" class="col-sm-2" style="color:black;"></label>	
							<div class="col-sm-6">
								<label style="color: #424949;" id="misactividades"> Ver Solo Mis actividades &nbsp;&nbsp;
									<input type="checkbox" name="solomisact" class="flat-red">
								</label>
						  </div>
					</div>
					<hr />
				<div class="form-group">
		      <label for="empresaNombre" class="col-sm-2" style="color:black;">Selecione la Unidad Operat&iacute;va:</label>

		      <div class="col-sm-10">
		      	<ul class="list-unstyled">
						@foreach($uniops as $uniop)
							<label>
								<li> <input name="uniops[]" type="checkbox" class="flat-red" value="{{$uniop->unidadopuid}}">
									&nbsp;{{$uniop->unidadopnombre}}&nbsp;
								</li>
							</label><br />
						@endforeach
		      	</ul>
		    	</div>
				</div>

				<div class="form-group">
						<label for="empresaNombre" class="col-sm-2" style="color:black;">Acceso a tipos de actividad</label>

						<div class="col-sm-10">

                            <table class="table table-condensed table-hover table-responsive no-padding">
                                <tr>
                                    <th >Selecione Tipo de Activiad</th>
                                    <th style="text-align:center;" >Acceso Web
                                        <input type="checkbox" id="selectallw"/></th>
                                    <th style="text-align:center;" >Acceso M&oacute;vil
                                        <input type="checkbox" id="selectallm"/></th> 
                                </tr>
                                @foreach($tipoacts as $tipact)
                                <tr>
                                    <td>{{$tipact->titulo}}&nbsp;</td>
                                    <td style="text-align:center;"><input name="tipacts[]"    type="checkbox" class="casew"  value="{{$tipact->id}}"></td>
                                    <td style="text-align:center;"><input name="tipactsmob[]" type="checkbox" class="casem" value="{{$tipact->id}}"></td>
                                </tr>
                                @endforeach
                            </table>
						</div>
					</div>

		    <!-- /.box-body -->
			  <div class="box-footer">
			    <a href="{{ route('cusuario.index')}}" class="btn btn-default">Volver</a>
			    <button type="submit" class="btn btn-primary pull-right">Guardar</button>
			  </div>
			  <!-- /.box-footer -->

		</div>
	</form>
</div>

@endsection
