@extends('layouts.admin')

@section('content')

		<div class="box box-solid with-border">
			<div class="box-header with-border"> <h3 class="box-title">Crear Rol</h3> </div>
		<!-- /.box-header -->
		<!-- form start -->
			<form class="form-horizontal" action="{{ route('roles.store') }}" autocomplete="on" method="POST">
				@csrf
				<div class="box-body">
					<div class="form-group">
				      <label for="empresaNombre" class="col-sm-2" style="color:black;">Nombre del Rol:</label>

				      <div class="col-sm-10">
				        <input type="text" class="form-control" id="txtEmpreasNombre" name="name" value="{{ old('name') }}" required />
				      </div>
				    </div>

				    <div class="form-group">
				      <label for="empresaNombre" class="col-sm-2" style="color:black;">Url Amigable:</label>

				      <div class="col-sm-10">
				        <input type="text" class="form-control" id="txtEmpreasNombre" name="slug"  value="{{ old('slug') }}" required />
				      </div>
				    </div>

				    <div class="form-group">
				      <label for="empresaNombre" class="col-sm-2" style="color:black;">Descripci&oacute;n del Rol:</label>

				      <div class="col-sm-10">
				      	<textarea class="form-control" name="description" onkeyup="textAreaAdjust(this)" style="overflow:hidden" required />{{ old('description') }}</textarea>
				      </div>
				    </div>

					<!-- <div class="form-group">
				      <label for="empresaNombre" class="col-sm-2" style="color:black;">Permiso Espacial:</label>

				      <div class="col-sm-10">
				      	<input name="special" type="radio" value="all-access"> Acceso total
				      	&nbsp; &nbsp; &nbsp; &nbsp;
								<input name="special" type="radio" value="no-access"> Ningún acceso
				      </div>
				    </div>-->

					<div class="form-group">
				      <label for="empresaNombre" class="col-sm-2" style="color:black;">Selecione los Permisos:</label>

				      <div class="col-sm-10">
				      	<ul class="list-unstyled">
                            <h5>
                                <li style="color:black;"><input type="checkbox" id="selectallw" /> Seleccionar todos</li>
                            </h5>
							@foreach($permissions as $permission)
                            <h5 style="color:black;">
                            <h5></h5>
						    <li> <input name="permissions[]" type="checkbox" class="casew" value="{{$permission->id}}">
						    	&nbsp;{{$permission->name}}&nbsp;<em style="color:gray;">&nbsp;&nbsp;({{$permission->description}})</em>
						    </li>
                        </h5>
	    					@endforeach
				      </div>
				    </div>

				    <!-- /.box-body -->
					  <div class="box-footer">
					    <a href="{{ route('roles.index') }}" class="btn btn-default">Volver</a>
					    <button type="submit" class="btn btn-primary pull-right">Guardar</button>
					  </div>
					  <!-- /.box-footer -->

				</div>
			</form>
		</div>

@endsection

