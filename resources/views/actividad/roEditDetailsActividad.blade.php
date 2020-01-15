<form action="" class="form-horizontal" id="formEditDetail" method="POST" enctype="multipart/form-data" accept-charset="UTF-8" files="true" >
@csrf
<input type="hidden" value="{{$rowCont}}" name="ncontenido">
<div class="box-body">
	<div class="col-md-6">

		<div class="form-group col-xs-12 col-sd-12 col-md-5">
            <label  style="color:black;">Inicio</label>
            <div class="input-group">
                    {{$datebegin}} {{$timebegin2}}
				<div class="input-group-addon"> <span class="glyphicon glyphicon-calendar"></span> </div>
			</div>
		</div>

		<div class="form-group col-xs-12 col-sd-12 col-md-5">
			<label  style="color:black;">Fin</label>
			<div class="input-group">
                    {{$datebegin}} {{$timeend}}
				<div class="input-group-addon"> <span class="glyphicon glyphicon-calendar"></span> </div>
			</div>
        </div>

        <div class="form-group col-xs-12 col-sd-12 col-md-2">
            <label class="margin-bottom"  style="color:black;">Status</label>
            @if ($actividad->comporta  == "1")
                @if ($actividad->actividadstatus=='A') Abierta   @endif
                @if ($actividad->actividadstatus=='C') Cerrada   @endif
                @if ($actividad->actividadstatus=='X') Cancelada @endif
            @else
                @if ($actividad->actividadstatus=='A') En edición   @endif
                @if ($actividad->actividadstatus=='C') Vigente   @endif
                @if ($actividad->actividadstatus=='X') No vigente @endif
            @endif
		</div>

		<div class="form-group col-xs-12 col-sd-12 col-md-12">
            <label  style="color:black;"> Descripci&oacute;n de la Actividad</label>
            {{$actividad->actividaddescip}}

		</div>

		<div class="form-group col-xs-12 col-sm-12 col-lg-12">
            <label  style="color:black;"> Lugar de la Actividad </label>
            {{$actividad->actividadlugar}}

        </div>


		</div><!-- /.form-horizontal -->

		<div class="col-md-6  with-border">
			<div class="box">

				<div class="box-body">
					<h2 class="page-header">Participantes</h2>
					<table class="table table-condensed table-bordered table-striped table-hover" id="tblParticipantes">
						@foreach ($actividadUsers as $auser)
		                <tr>
		                <td>
                                {{$auser->nombre}} - {{$auser->email}}
		                  		@if($auser->responsable==1)
                                  <span class="badge bg-green pull-right"> Responsable </span>
                                @elseif($auser->responsable==2)
                                    <span class="badge bg-light-blue pull-right"> Editor </span>
                                @elseif($auser->responsable==3)
                                    <span class="badge bg-default pull-right"> Invitado </span>
		                  		@endif
		                  	</td>
		                </tr>
		          		@endforeach
		            </table>
				</div>


			</div>

        </div>

        <div class="col-xs-12 col-sm-12 col-md-12 with-border">
            <a class="btn btn-primary" role="button" data-toggle="collapse" href="#collapseExample1" aria-expanded="false" aria-controls="collapseExample">Actividad Periodica</a>
            <div class="collapse" id="collapseExample1">
                <div class="well">
                        <div class="box-body">

                                    <div class="box box-solid with-border col-xs-12 col-sd-12 col-md-12">
                                        <div class="form-group col-xs-12 col-sd-12 col-md-5">
                                            <label style="color:black;" >Repite cada  </label> {{ $periocidad }}
                                                    @if($tipop=='d') d&iacute;a(s) @endif
                                                    @if($tipop=='s') semana(s) @endif
                                                    @if($tipop=='m') mes(es) @endif
                                                    @if($tipop=='a') a&ntilde;o(s) @endif


                                        </div>
                                        <div class="form-group col-xs-12 col-sd-12 col-md-4">
                                            <label style="color:black;" >Finaliza   </label>

                                                    {{$dapff}}


                                        </div>
                                        <div class="form-group col-xs-12 col-sd-12 col-md-4">
                                                <label style="color:black;" >Programa  </label>
                                                    @foreach ($programasAll as $programAll)
                                                        @if($actividad->programauid == $programAll->id)
                                                            {{$programAll->prognombre}}
                                                        @endif
                                                    @endforeach

                                        </div>

                                    </div>

                            </div>
                </div>
            </div>
        </div>

	</div><!-- /.box-body -->

	<div class="box-footer">
        <!-- <a href="/calendario" class="btn btn-default">Volver</a> -->
    @if($dapff=="")
    <button type="button" id="btnEditDetails" class="btn btn-primary pull-right">Guardar</button>
    @else
        <button type="button" id="btnEditDetails2" class="btn btn-primary pull-right" data-toggle="modal" data-target="#myModal">Guardar</button>
    @endif
	</div><!-- /.box-footer-->
</form>



<!-- MODAL EDITAR ACTIVIDAD PERIODODICA -->
<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="myModalLabel">Desea editar</h4>
        </div>
        <div class="modal-body">

            <h5>
                <input type="radio" name="optionsEdit" id="optionsRadios1" value="actual" checked>
                Esta actividad
            </h5>
            <h5>
                <input type="radio" name="optionsEdit" id="optionsRadios1" value="actualP">
                Esta actividad y todas las posteriores
            </h5>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
          <button type="button" id="bt_guardar_modal" class="btn btn-primary">Guardar</button>
        </div>
      </div>
    </div>
  </div>
<!-- MODAL EDITAR ACTIVIDAD PERIODODICA -->
@section('modaleditacividadperiodica')
<script>

$('#bt_guardar_modal').on('click', function() {

   $('#textoptionsEdit').val($('input[name=optionsEdit]:checked').val());
   editDetailsActividad();


});

$('#bt_guardar_edit').on('click', function() {

    $('#textoptionsEdit').val($('input[name=optionsEdit]:checked').val());
    editDetailsActividad();
});
</script>
@endsection
