<form action="{{ route('actividad.editdetails') }}" class="form-horizontal" id="formEditDetail" method="POST" enctype="multipart/form-data" accept-charset="UTF-8" files="true" >
@csrf
<!--action="{{ route('actividad.editdetails') }}" -->
<div class="box-body">
	<div class="col-md-6 with-border">

		<div class="form-group col-xs-12 col-sd-12 col-md-6">
			<label for="actividadtitulo">Inicio</label>
				<div class="input-group date">
					<input type="text" name="dateinicio" class="form-control" id="dateinicio2" value="{{$datebegin}}">
					<div class="input-group-addon"> <span class="glyphicon glyphicon-calendar"></span> </div>
				</div>
				<select name="timeinicio2" class="form-control col-md-4 select2" id="timeinicio2" value="{{$timebegin2}}" style="width: 90%;" required>
					@include('calendario.horas')
				</select>
		</div>

		<div class="form-group col-xs-12 col-sd-12 col-md-6">
			<label for="actividadtitulo">Fin</label>
			<div class="input-group date">
				<input type="text" name="datefin" class="form-control " id="datefin2" value="{{$datebegin}}">
				<div class="input-group-addon"> <span class="glyphicon glyphicon-calendar"></span> </div>
			</div>
				<select name="timefin2" class="form-control select2" id="timefin2" style="width: 90%;" value="{{$timeend}}" required>
					@include('calendario.horas')
				</select>
		</div>

		<div class="form-group col-xs-12 col-sd-12 col-md-12">
			<label for="actividadtitulo">Descripcion de la Actividad</label>
			<textarea class="form-control" id="actividaddescip" name="actividaddescip">{{$actividad->actividaddescip}} </textarea>
		</div>

		<div class="form-group col-xs-12 col-sm-12 col-lg-12">
			<label for="actividadtitulo">Lugar de la Actividad</label>
			<input type="text" class="form-control" name="place" id="actividalugar" name="actividadlugar" value="{{$actividad->actividadlugar}}" />
		</div>

		<div class="form-group col-xs-12 col-sd-12 col-md-4">
			<label for="actividadtitulo">Status</label>
			<select name="status" class="form-control select2" style="width: 100%;" required>
          			<option value="A">Abierta</option>
          			<option value="C">Cerrada</option>
          			<option value="X">Cancelada</option>

          	</select>
		</div>

		</div><!-- /.form-horizontal -->

		<div class="col-md-6 form-horizontal with-border">
			<div class="box">

				<div class="box-body">
					<h2 class="page-header">Participantes
  					   <a class="bt btn-info btn-xs pull-right" data-target="#add-invitado" data-toggle="modal"><i class="fa fa-plus"></i></a>
  					</h2>
					<table class="table table-condensed table-bordered table-striped table-hover">
						@foreach ($actividadUsers as $auser)
		                <tr>
		                <td>
								@foreach ($usuarios as $us) @if ($us->email == $auser->email) {{$us->name}} -  @endif @endforeach
		                  		{{$auser->email}}
		                  		@if($auser->responsable==1)
		                  		<span class="label bg-light-blue pull-right" data-toggle="tooltip" data-placement="bottom" title="Responsabel de la actividad"> Responsable </span>
		                  		@endif
		                  	</td>
		                 	<td style="width: 10px">
								<form action="{{ route('actividad.remuser')}}" autocomplete="on" method="POST">
								@csrf
								<input type="hidden" name="idactividad" value="{{$actividad->id}}">
								<input type="hidden" name="id" value="{{$auser->id}}">
		                  		<button type="submit" class="btn-xs btn-danger"><i class="fa fa-close"></i></button>
		                  		</form>
		                  	</td>

		                </tr>
		          		@endforeach
		            </table>
				</div>


			</div>

		</div>

	</div><!-- /.box-body -->

	<div class="box-footer">
		<a href="/calendario" class="btn btn-default">Volver</a>
		<button type="submit" id="btnEditDetails2" class="btn btn-primary pull-right">Guardar</button>
	</div><!-- /.box-footer-->
</form>



