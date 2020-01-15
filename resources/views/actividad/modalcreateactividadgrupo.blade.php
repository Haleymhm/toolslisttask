<!-- INICIO MODAL CREAR ACTIVIDAD -->
<div class="modal fade" id="actGrupoModal"  role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<h4 class="modal-title" id="exampleModalLabel">Nueva Actividad+</h4>
      </div>

      <div class="modal-body">

         <form action="{{ route('actividad.storeactgrupo') }}" id="formActividadGrupo" class="form-horizontal" autocomplete="off" method="POST">
            @csrf
            <input type="hidden" class="form-control" name="empresauid" value="{{Auth::user()->uidempresa }}" >
            <input type="hidden" class="form-control" name="useruid" value="{{Auth::user()->id }}" >
            <input type="hidden" class="form-control" name="actgrupuid" value="{{ $actividad->id }}" >
            <div class="form-group col-xs-12 col-md-12 col-sd-12">
	           <strong class="col-sm-2 ">Tipo</strong>&nbsp;
		        <select name="tipoactividaduid" class="form-control select2" style="width: 50%;" required id="topActGrup" />
							<option></option>
							@if (($valor=='root') or ($valor=='admin'))
								@foreach($tipoacts as $tipoact)
									<option value="{{$tipoact->uid}}" @if ($tipoact->uid == $id) {{ "selected" }} @endif>{{$tipoact->titulo}}</option>
								@endforeach
							@else
								@foreach($tipoacts as $tipoact)
									@foreach ($usertipoacts as $usertipoact)
										@if ($usertipoact->tipoacts_id == $tipoact->id)
											<option value="{{$tipoact->uid}}" @if ($tipoact->uid == $id) {{ "selected" }} @endif>{{$tipoact->titulo}}</option>
										@endif
									@endforeach
								@endforeach
							@endif

		        </select>
            </div>

            <div class="form-group col-xs-12 col-sd-12 col-md-5">
                <strong>Inicio</strong>
                    <div class="input-group date">
                        <input type="text" name="dateinicio" class="form-control" id="dateinicioAG">
                        <div class="input-group-addon"> <span class="glyphicon glyphicon-calendar"></span> </div>
                        <select name="timeinicio" class="form-control col-md-4 select2" id="timeinicioAG" style="width: 100%;" required>
                            @include('calendario.horas')
                        </select>
                    </div>
            </div>
            <div class="col-md-1">&nbsp;</div>
         	<div class="form-group col-xs-12 col-sd-12 col-md-5">
                        <strong>Fin</strong>
						<div class="input-group date">
							<input type="text" name="datefin" class="form-control " id="datefinAG">
                            <div class="input-group-addon"> <span class="glyphicon glyphicon-calendar"></span> </div>
                            <select name="timefin" class="form-control col-md-6 select2" id="timefinAG" style="width: 100%;" required>
                                @include('calendario.horas')
                            </select>
						</div>

					</div>

			<div class="form-group col-xs-12 col-sm-12 col-md-5">
				<strong>Descripci&oacute;n</strong>
				<textarea class="form-control" id="actividaddescip" name="actividaddescip" onkeyup="textAreaAdjust(this)" style="overflow:hidden"> </textarea>
			</div>
            <div class="col-md-1">&nbsp;</div>
			<div class="form-group col-xs-12 col-sm-12 col-md-5">
				<strong>Lugar</strong>
			<textarea class="form-control" id="actividalugar" name="actividadlugar" onkeyup="textAreaAdjust(this)" style="overflow:hidden">{{$direccion}}</textarea>
			</div>


      <div class="modal-footer">
        <div class="form-group col-xs-12 col-sm-12 col-md-12">
            <button type="button" class="btn btn-primary pull-right" id="btnActividadGrupo" >Guardar</button>
            <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cerrar</button>
        </div>
      </div>
      </form>
    </div>

  </div>
</div>
</div>
<!-- FIN MODAL CREAR ACTIVIDAD -->

