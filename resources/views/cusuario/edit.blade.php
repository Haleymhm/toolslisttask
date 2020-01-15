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

<div class="box">
	<div class="box-header with-border"> <h3 class="box-title">Editar Usiario</h3> </div>
<!-- /.box-header -->
<!-- form start -->
	<form class="form-horizontal" action="{{ route('cusuario.update',$user->id) }}" autocomplete="on" method="POST">
		@csrf
		@method('PUT')
		<div class="box-body">
		    <div class="form-group">
		      <label for="unidadoNombre" class="col-sm-2" style="color:black;">Nombre</label>

		      <div class="col-sm-10">
		        <input type="text" class="form-control" id="name" name="name" value="{{$user->name}}">
		      </div>
		    </div>
		    <div class="form-group">
		      <label for="unidadoNombre" class="col-sm-2" style="color:black;">Email</label>

		      <div class="col-sm-10">
		        <input type="email" class="form-control" id="unidadopnombre" name="email" value="{{$user->email}}">
		      </div>
		    </div>
		    <div class="form-group">
		      <label for="unidadoNombre" class="col-sm-2" style="color:black;">Cargo</label>

		      <div class="col-sm-10">
		        <input type="text" class="form-control" id="unidadopnombre" name="cargo" value="{{$user->cargo}}">
		      </div>
            </div>

            <div class="form-group">
                <label for="tipoActividadNombre" class="col-sm-2" style="color:black;">Password</label>
                <div class="col-sm-10">
                    <input type="text" name="passw" class="form-control" />
                </div>
			</div>
			
			<div class="form-group">
					<label for="tipoActividadNombre" class="col-sm-2" style="color:black;"></label>	
						<div class="col-sm-6">
							<label style="color: #424949;" id="misactividades"> Ver Solo Mis actividades
								<input type="checkbox" name="solomisact" class="flat-red"
									@if( $user->solomisact=="S")
										{{"checked"}}
									@endif
									>
							</label>
				      </div>
				</div>
				<hr />

				<div class="form-group">
					<label for="tipoActividadStatus" class="col-sm-2" style="color:black;">Status</label>
						<div class="col-sm-2">
                            <select name="status" class="form-control select2">
                                <option value="A" @if ($user->status == 'A' ) {{"selected"}} @endif>Activo</option>
                                <option value="I" @if ($user->status == 'I' ) {{"selected"}} @endif>Inactivo</option>
                            </select>
				      </div>
				</div>
				<hr />
				<div class="form-group">
		        <label for="empresaNombre" class="col-sm-2" style="color:black;">Selecione El Rol:</label>

		        <div class="col-sm-10">
                    @foreach($roles as $rol)
                        @if (($rol->slug=="root") or ($rol->slug=="visitante"))

                        @else
                            <ul class="list-unstyled">
                                <li> <label style="color:black;">
                                    <input name="roles" type="radio" class="flat-red" value="{{$rol->id}}"
                                    @foreach($rolesuserhs as $rolesuserh)
                                        @if ($rolesuserh->role_id == $rol->id)
                                                {{ "checked" }}
                                        @endif
                                    @endforeach>
                                    &nbsp;{{$rol->name}}&nbsp;<em>&nbsp;&nbsp;{{$rol->description}} </em>
                                    </label>
                                </li>
                            </ul>
                        @endif
                    @endforeach

			    </div>
				</div>

				<hr />

		<div class="form-group">
		      <label for="empresaNombre" class="col-sm-2" style="color:black;">Selecione la Unidad Operat&iacute;va:</label>

		      <div class="col-sm-10">
		      	<ul class="list-unstyled">
						@foreach($uniops as $uniop)
						  <label style="color:black;">
									<li> <input name="uniops[]" type="checkbox" class="flat-red" value="{{$uniop->unidadopuid}}"
										@foreach($useruniops as $useruniop)
											@if ($useruniop->unidadopuid == $uniop->unidadopuid)
													{{ "checked" }}
											@endif
										@endforeach
										>&nbsp;{{$uniop->unidadopnombre}}&nbsp;</li>
						  </label><br />
						@endforeach
		      	</ul>
		    	</div>
				</div>
				<hr />
				<div class="form-group">
						<label for="empresaNombre" class="col-sm-2" style="color:black;">Acceso a tipos de actividad</label>

						<div class="col-sm-10">

                            <table class="table table-condensed table-hover table-responsive no-padding">
                                    <tr>
                                        <th >Selecione Tipo de Activiad</th>
                                        <th style="text-align:center;" >Acceso Web <input type="checkbox" id="selectallw"/></th>
                                        <th style="text-align:center;" >Acceso M&oacute;vil <input type="checkbox" id="selectallm"/></th>
                                    </tr>
                                @foreach($tipoacts as $tipact)
                                <tr>
                                    <td>{{$tipact->titulo}}&nbsp;</td>
                                    <td style="text-align:center;"><input name="tipacts[]" type="checkbox" class="casew" value="{{$tipact->id}}"
                                        @foreach($usertipoacts as $usertipoact)
                                            @if ($usertipoact->tipoacts_id == $tipact->id)
                                                    {{ "checked" }}
                                            @endif
                                        @endforeach >
                                    </td> 
                                    <td style="text-align:center;"><input name="tipactsmob[]" type="checkbox" class="casem" value="{{$tipact->id}}"
                                        @foreach($usertipoactsmobs as $usertipoactsmob)
                                            @if ($usertipoactsmob->tipoacts_id == $tipact->id)
                                                    {{ "checked" }}
                                            @endif
                                        @endforeach >
                                    </td>
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
