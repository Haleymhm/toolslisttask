<!-- INICIO MODAL CREAR ACTIVIDAD -->
<div class="modal fade" id="fullCalModal"  role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<h4 class="modal-title" id="exampleModalLabel">Nueva Actividad</h4>
      </div>

      <div class="modal-body">

         <form action="{{ route('empresa.store') }}" class="form-horizontal" id="formCreateActividad" autocomplete="off" method="POST">
            @csrf
            <input type="hidden" class="form-control" name="empresauid" value="{{Auth::user()->uidempresa }}" >
            <input type="hidden" class="form-control" name="useruid" value="{{Auth::user()->id }}" >
            <div class="form-group col-xs-12 col-md-12 col-sd-12">
	           <strong >Tipo</strong>&nbsp;
		        <select name="tipoactividaduid" class="form-control select2" style="width:95%" required id="topAct" />
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
                        <input type="text" name="dateinicio" class="form-control" id="dateinicioAC">
                        <div class="input-group-addon"> <span class="glyphicon glyphicon-calendar"></span> </div>
                        <select name="timeinicio" class="form-control col-md-4 select2" id="timeinicioAC" style="width: 100%;" required>
                            @include('calendario.horas')
                        </select>
                    </div>
            </div>
            <div class="col-md-1">&nbsp;</div>
         	<div class="form-group col-xs-12 col-sd-12 col-md-5">
                        <strong>Fin</strong>
						<div class="input-group date">
							<input type="text" name="datefin" class="form-control " id="datefinAC">
                            <div class="input-group-addon"> <span class="glyphicon glyphicon-calendar"></span> </div>
                            <select name="timefin" class="form-control col-md-4 select2" id="timefinAC" style="width: 100%;" required>
                                @include('calendario.horas')
                            </select>
						</div>

					</div>

			<div class="form-group col-xs-12 col-sm-12 col-md-5">
				<strong>Descripci&oacute;n</strong>
				<textarea class="form-control" id="actividaddescip" name="actividaddescip" onkeyup="textAreaAdjust(this)" style="overflow:hidden, height:100px"> </textarea>
			</div>
            <div class="col-md-1">&nbsp;</div>
			<div class="form-group col-xs-12 col-sm-12 col-md-5">
				<strong>Lugar</strong>
			<textarea class="form-control" id="actividalugar" name="actividadlugar" onkeyup="textAreaAdjust(this)"  style="overflow:hidden, height:100px">{{$direccion}}</textarea>
			</div>

			<div class="col-xs-12 col-sm-12 col-md-12 with-border">
                <a class="btn btn-primary" role="button" data-toggle="collapse" href="#collapseExample1" aria-expanded="false" aria-controls="collapseExample">
                    Actividad Peri&oacute;dica
                </a>
                <div class="collapse" id="collapseExample1">
                    <div class="well">
                        <div class="box-body">
                           <!-- <div class="col-xs-12 col-sd-12 col-md-12 with-border">-->
                                <div class="form-group col-xs-12 col-sd-12 col-md-5">
                                    <h5> Repite cada </h5>
                                    <div class="col-xs-4 col-sd-4 col-md-3">
                                        <input name="periocidad" type="text" class="form-control" pattern="[0-9.]+" value="1"  min="0" max="360" maxlength="3">
                                    </div>
                                    <select name="tipoperiodo" class="form-control select2" style="width: 65%;">
                                        <option></option>
                                        <option value="d">d&iacute;a(s)</option>
                                        <option value="v" >Diario (Lunes-Viernes)</option>
                                        <option value="z" >Diario (Lunes-Sabado)</option>
                                        <option value="s">semana(s)</option>
                                        <option value="m">mes(es)</option>
                                        <option value="a">a&ntilde;o(s)</option>
                                    </select>
                                </div>
                                <div class="form-group col-xs-12 col-sd-12 col-md-3">
                                    Finaliza
                                        <div class="input-group date">
                                            <input type="text" name="finperiodo" class="form-control" id="datefinperiodo">
                                            <div class="input-group-addon"> <span class="glyphicon glyphicon-calendar"></span> </div>
                                        </div>
                                </div>
                                <div class="form-group col-xs-12 col-sd-12 col-md-5">
                                    <h5>&nbsp;&nbsp;&nbsp;&nbsp;Programa</h5>
                                    <div class="col-xs-4 col-sd-4 col-md-12">
                                        <select name="programa" class="form-control select2" style="width: 100%;">
                                            <option></option>
                                            @foreach ($programas as $programa)
                                                <option value="{{$programa->id}}">{{$programa->prognombre}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                           <!-- </div> -->
                        </div>
                    </div>
                </div>
            </div>
      <div class="modal-footer">
    <div class="col-xs-12 col-sd-12 col-md-12">
        <button type="button" class="btn btn-info btn-lg  pull-left" id="btnSaveBack" ><i class="fa fa-save" ></i> <i class="fa fa-reply" title="Guardar y volver"></i> <span class="nomobile"> Guardar y volver</span></button>
        <!-- <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button> -->
        <button type="button" class="btn btn-primary btn-lg pull-right" id="btnSaveEdit" ><i class="fa fa-save" ></i> <i class="fa fa-edit" title="Guardar y editar"></i><span class="nomobile" title="Guardar y editar"> Guardar y editar</span></button>
    </div>
      </div>
      </form>
    </div>

  </div>
</div>
<!-- FIN MODAL CREAR ACTIVIDAD -->

