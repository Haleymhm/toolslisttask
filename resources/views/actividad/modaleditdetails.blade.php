<!-- INICIO MODAL EDITAR DETALLES DELA ACTIVIDAD -->
<div class="modal fade" id="edit-details" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog " role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="exampleModalLabel"><strong>
					      	@foreach($tipoactividads as $tipoact)
					      		@if ($tipoact->uid==$actividad->tipoactividaduid)
					      			{{$tipoact->titulo}}
					      		@endif
					      	@endforeach
					        </strong></h4>
      </div>

      <div class="modal-body">
      	<form action="{{ route('actividad.editdetails') }}" autocomplete="on" method="POST">
            @csrf
            <input type="hidden" class="form-control" name="empresauid" value="{{Auth::user()->uidempresa }}" >
            <input type="hidden" class="form-control" name="useruid" value="{{Auth::user()->id }}" >
            <input type="hidden" name="idactividad" value="{{$actividad->id}}">


	          	<div class="form-group col-xs-6 col-sm-6 col-lg-6">
	          		<label for="actividadtitulo"  style="color:black;">Inicio:</label>
		          	<div class="input-group date">
		          		<input type="text" name="dateinicio" class="form-control" id="dateinicio" value="{{ $datebegin }}" required>
					    <div class="input-group-addon"> <span class="glyphicon glyphicon-calendar"></span> </div>
					</div>
	          		<select name="timeinicio" class="form-control select2" style="width: 100%;" required>
	          			@include('calendario.horas')
	          		</select>

	          	</div>



	          	<div class="form-group col-xs-6 col-sm-6 col-lg-6">
	          		<label for="actividadtitulo"  style="color:black;">Fin:</label>
		          	<div class="input-group date">
		          		<input type="text" name="datefin" class="form-control" id="datefin" value="{{ $dateend }}" required>
					    <div class="input-group-addon"> <span class="glyphicon glyphicon-calendar"></span> </div>
					</div>
	          		<select name="timefin" class="form-control select2" style="width: 100%;" required>
	          			@include('calendario.horas')
	          		</select>

	          	</div>



			<div class="form-group col-xs-12 col-sm-12 col-lg-12">
				<label for="actividadtitulo"  style="color:black;">Descripcion de la Actividad</label>
				<textarea class="form-control" id="actividaddescip" name="actividaddescip" onkeyup="textAreaAdjust(this)" style="overflow:hidden">{{$actividad->actividaddescip }} </textarea>
			</div>

			<div class="form-group col-xs-12 col-sm-8 col-lg-8">
				<label for="actividadtitulo">Lugar de la Actividad</label>
				<input type="text" class="form-control" id="place" name="place" value="{{$actividad->actividadlugar}}" />
			</div>

			<div class="form-group col-xs-12 col-sm-4 col-lg-4">
				<label for="actividadtitulo"  style="color:black;">Status</label>
				<select name="status" class="form-control select2" style="width: 100%;" required>
	          			<option value="A">Activo</option>
	          			<option value="B">Cancelada</option>
	          			<option value="C">Cerrada</option>

	          	</select>
			</div>

			<div class="form-group col-xs-12 col-sm-12 col-lg-12">
				<button type="submit" class="btn btn-primary pull-right" value="add" >Agregar</button>
        		<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
			</div>
      <div class="modal-footer">

      </div>
      </form>
    </div>

  </div>
</div>
<!-- FIN MODAL EDITAR DETALLES DE LA ACTIVIDAD -->
