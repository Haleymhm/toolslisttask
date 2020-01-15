@extends('layouts.app')

@section('libraries')
<link rel="stylesheet" href="{{ asset('bower_components/colorbox/colorbox.css')}}">
<style>
  /* .toggle.ios, .toggle-on.ios, .toggle-off.ios { border-radius: 20px; }
  .toggle.ios .toggle-handle { border-radius: 20px; } */
</style>
 
@endsection
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

		<div class="box-header with-border">
            <h3 class="box-title">
            {{$actividad->titulo}}
            </h3>&nbsp;&nbsp;
            @if($actividad->tipoactdescrip!='')
                <a tabindex="0" role="button" data-toggle="popover"
                data-placement="bottom"
                data-trigger="focus"
                data-content="{{$actividad->tipoactdescrip}}" class="no-print">
                <i class="fa fa-info-circle"></i></a>
            @endif



            <a role="button" data-toggle="collapse" href="#collapseEditDetailsActividad" aria-expanded="false" aria-controls="collapseExample">
                    @if ($actividad->comporta  == "1")
                        @if ($actividad->actividadstatus  == "A") <span id="status_span" class="badge label-primary">&nbsp;&nbsp;&nbsp;&nbsp;Abierta&nbsp;&nbsp;&nbsp;&nbsp;</span>
                        @elseif ($actividad->actividadstatus  == "X") <span id="status_span" class="badge label-default">Cancelada</span>
                        @elseif ($actividad->actividadstatus  == "C")<span id="status_span" class="badge label-success">&nbsp;&nbsp;Cerrada&nbsp;&nbsp;</span>
                        @endif
                    @else
                        @if ($actividad->actividadstatus  == "A") <span id="status_span" class="badge label-primary">&nbsp;&nbsp;&nbsp;&nbsp;En edición&nbsp;&nbsp;&nbsp;&nbsp;</span>
                        @elseif ($actividad->actividadstatus  == "X") <span id="status_span" class="badge label-default">No vigente</span>
                        @elseif ($actividad->actividadstatus  == "C")<span id="status_span" class="badge label-success">&nbsp;&nbsp;Vigente&nbsp;&nbsp;</span>
                        @endif
                    @endif
            </a>
                            @if($rowContAP>0)
                                &nbsp;&nbsp;<i class="fa fa-calendar-check-o" data-toggle="tooltip" data-placement="bottom" title="Se repite cada {{$periocidad}}&nbsp; @if($tipop=='d') {{ "dia(s)," }} @elseif($tipop=='s') {{ "semana(s)," }} @elseif($tipop=='m') {{ "mes(es)," }} @elseif($tipop=='a') {{ "año(s)," }} @endif finaliza el {{$dapff}}"></i>
                            @endif

                            @if($actividad->programauid!='')
                            @foreach($programasAll as $pr)
                                @if ($pr->id==$actividad->programauid)
                                &nbsp;&nbsp;<i class="{{$pr->progicon}}" data-toggle="tooltip" data-placement="bottom" title="Pertenece a
                                    {{$pr->prognombre}}"></i>
                                    @endif

                                @endforeach

                            @endif

                            @if($actividad->actividadgrupouid!='')
                                &nbsp;&nbsp;<i class='fa fa-list'data-toggle='tooltip' data-placement='bottom'
                                @foreach($actividadesParent as $actp)
                                    title='Pertenece a "{{$actp->titulo}}"
                                    <?php $d=date_create($actp->actividadinicio); echo date_format($d,'d/m/Y - H:i'); ?>
                                @endforeach
                                '></i>
                            @endif


			 <br /><small id="descripcion">{{$actividad->actividadcodigo}} <br /> {{$actividad->actividaddescip}}</small>


			<div class="box-tools">
				<a role="button" data-toggle="collapse" href="#collapseEditDetailsActividad" aria-expanded="false" aria-controls="collapseExample"><i class="fa fa-calendar"></i></a>
					@if($datebegin === $dateend)
						{{ $datebegin }}
						@if($timebegin2 === $timeend)
							@if(!($timebegin2==="00:00:00"))
								{{ $timebegin2 }}
							@endif
						@else
							{{ $timebegin2 }} - {{ $timeend }}
						@endif
					@else
						{{ $datebegin }} - {{ $timebegin2 }}   {{ $dateend }} - {{ $timeend }}
					@endif
				<br />
				@if(!($actividad->actividadlugar==""))
				<a role="button" data-toggle="collapse" href="#collapseEditDetailsActividad" aria-expanded="false" aria-controls="collapseExample"><i class="fa fa-map-marker"></i></a>&nbsp;{{$actividad->actividadlugar}}<br />
				@endif
				<a class="nomobile" role="button" data-toggle="collapse" href="#collapseEditDetailsActividad" aria-expanded="false" aria-controls="collapseExample"><i class="fa fa-users"></i></a>
					<small class="nomobile">
          				<?php
          					$i=0; $j=0;
          					foreach ($actividadUsers as $auser){
          						    if($auser->responsable==1){ echo '<span class="label label-success mail-part" data-toggle="tooltip" data-placement="bottom" title="Responsable de la actividad">'. $auser->email .'</span>';}
          						elseif($auser->responsable==2){ echo '<span class="label label-info mail-part" data-toggle="tooltip" data-placement="bottom" title="Editor de actividad">'. $auser->email .'</span>';}
                                elseif($auser->responsable==3){ echo '<span class="label label-default mail-part">'. $auser->email. '</span>';}
                                elseif($auser->responsable==4){
                                    if($auser->email==""){echo '<span class="label label-warning mail-part">'. $auser->nombre. '</span>';}
                                    else { echo '<span class="label label-warning mail-part">'. $auser->email. '</span>';}
                                 }
          						$i++; $j++;
          						if ($i >= 3){ echo "<br />"; break;}
          						if ($j >= 6){ break; }
          					}
          				?>
				</small>
			</div>

<br />

        @if($rowCont==0)
		    @include('actividad.editDetailsActividad')
		@else
		<div class="collapse well box " id="collapseEditDetailsActividad">
            @include('actividad.editDetailsActividad')

		</div>
    </div>

		<form class="form-horizontal" id="formEditActividad" autocomplete="off" method="POST" enctype="multipart/form-data" accept-charset="UTF-8" files="true" >
			@csrf

            <div class="col-xs-12 col-sd-12 col-md-12"> &nbsp;</div>
			<div class="box-body">

				@foreach ($contenidos as $key => $conte)
                <div class="col-xs-12 col-md-12 col-sd-12 simobile" style="display: none;">&nbsp;</div>
					@if ($conte->tipodato == "text")
						<div class="form-group col-md-6 col-sd-12 col-xs-12">
							<div class="col-md-12 col-sd-12 col-xs-12">
                                <label for="tipoActividadDescrip" style="color:black; font-weight: bold;" class="printertitle">{{$conte->etiqueta}}</label>
                                <input type="text" class="form-control no-print" id="tipoActividadNombre" name="valortext[]" value="{{$conte->valortexto}}" onkeypress="inputCharacters()"/>
                                <div class="printertext">{{$conte->valortexto}}</div>
                              </div>
					    	<input type="hidden" name="registro[]" value="{{$conte->id}}">
					    	<input type="hidden" name="tipodato[]" value="{{$conte->tipodato}}">
					    </div>
				    @endif

					@if ($conte->tipodato == "textarea")
						<div class="form-group col-md-12 col-sd-12 col-xs-12">
							<div class="col-md-12 col-sd-12 col-xs-12">
							    <label for="tipoActividadDescrip" style="color:black; font-weight: bold;" class="printertitle" >{{$conte->etiqueta}}</label>
							    <textarea name="valortext[]" class="form-control no-print" id="tipoActividadNombre" onkeyup="textAreaAdjust(this)" style="overflow:hidden; height:100px">{{$conte->valortexto}}</textarea>
                                <div class="printertext">{{$conte->valortexto}}</div>
					    	</div>
					      <input type="hidden" name="registro[]" value="{{$conte->id}}">
					      <input type="hidden" name="tipodato[]" value="{{$conte->tipodato}}">
					  </div>
				    @endif
				    @if ($conte->tipodato == "numeric")
						<div class="form-group col-md-3 col-sd-12 col-xs-12">
							<div class="col-md-12 col-sd-12 col-xs-12">
							    <label for="tipoActividadDescrip" style="color:black; font-weight: bold;" class="printertitle">{{$conte->etiqueta}} </label>
                                <input type="text" class="form-control no-print"  id="tipoActividadNombre" onkeypress="return formatInteger(event);" name="valortext[]" value="<?php echo number_format($conte->valornumero); ?>" />

                                <div class="printertext"><?php echo number_format($conte->valornumero); ?></div>
							</div>
						    <input type="hidden" name="registro[]" value="{{$conte->id}}">
						    <input type="hidden" name="tipodato[]" value="{{$conte->tipodato}}">
					  </div>
				    @endif
				    @if ($conte->tipodato == "date")
						<div class="form-group col-md-3 col-sm-12 col-xs-12">
							<div class="col-md-12 col-sd-12 col-xs-12" >
                                <label for="tipoActividadDescrip" style="color:black; font-weight: bold;" class="printertitle">{{$conte->etiqueta}}</label>
                                <div class="input-group date no-print">
                                    <input type="text" name="valortext[]" class="form-control" value="<?php $d=date_create($conte->valorfecha); echo date_format($d,'d-m-Y'); ?>" id="date{{$conte->id}}">
                                    <div class="input-group-addon "> <span class="glyphicon glyphicon-calendar"></span> </div>
                                </div>
                                <div class="printertext"><?php $d=date_create($conte->valorfecha); echo date_format($d,'d-m-Y'); ?></div>
                        </div>
					    	<input type="hidden" name="registro[]" value="{{$conte->id}}">
					    	<input type="hidden" name="tipodato[]" value="{{$conte->tipodato}}">
					    </div>
				    @endif
				    @if ($conte->tipodato == "monto")
						<div class="form-group col-md-3 col-sd-12 col-xs-12">
							<div class="col-md-12 col-sd-12 col-xs-12">
							    <label for="tipoActividadDescrip" style="color:black; font-weight: bold;" class="printertitle">{{$conte->etiqueta}}</label>
                                <input type="text" class="form-control no-print "  id="tipoActividadNombre" name="valortext[]" onkeypress="return formatDecimal(event,this)" value="<?php echo number_format($conte->valornumero,2); ?>" />
                                <div class="printertext"><?php echo number_format($conte->valornumero,2); ?></div>
							</div>
							<input type="hidden" name="registro[]" value="{{$conte->id}}">
							<input type="hidden" name="tipodato[]" value="{{$conte->tipodato}}">

					  </div>
				    @endif

				    @if ($conte->tipodato == "lista")
						<div class="form-group col-md-6 col-sd-12 col-xs-12">
							<div class="col-md-12 col-sd-12 col-xs-12">
                                <label for="tipoActividadDescrip" style="color:black; font-weight: bold;" class="printertitle">{{$conte->etiqueta}}</label>

                                @foreach ($listados as $listado)
                                    @if (($listado->ver=='lis') and ($conte->idlista==$listado->id))
                                        <select class="form-control select2 no-print" style="width:100%;" name="valortext[]" >
                                            <option value="0">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option>
                                                @foreach ($elementos as $elemento)
                                                  @if($elemento->listadouid==$conte->idlista)
                                                      <option value="{{$elemento->id}}" @if($elemento->id==$conte->valorlista) {{ "selected" }} @endif > {{$elemento->elemnombre }} </option>
                                                  @endif
                                                @endforeach
                                        </select>
                                    @elseif(($listado->ver=='oph') and ($conte->idlista==$listado->id))
                                        <div class="radio no-print">
                                            @foreach ($elementos as $elemento)
                                                @if($elemento->listadouid==$conte->idlista)
                                                    <label>{{$elemento->elemnombre }}
                                                    <input type="radio" name="{{$conte->id}}" class="iradio" idCont="{{$conte->id}}" value="{{$elemento->id}}" @if($elemento->id==$conte->valorlista) {{"checked"}} @endif ></label>

                                                @endif
                                            @endforeach
                                        </div>
                                        <input type="hidden" name="valortext[]" id="{{$conte->id}}" value="{{$conte->valorlista}}">
                                    @elseif(($listado->ver=='opv') and ($conte->idlista==$listado->id))
                                        <div class="radio no-print">
                                            @foreach ($elementos as $elemento)
                                                @if($elemento->listadouid==$conte->idlista)
                                                    <label>{{$elemento->elemnombre }}
                                                    <input type="radio" name="{{$conte->id}}" class="iradio" idCont="{{$conte->id}}" value="{{$elemento->id}}" @if($elemento->id==$conte->valorlista) {{"checked"}} @endif ></label> <br />

                                                @endif
                                            @endforeach
                                        </div>
                                        <input type="hidden" name="valortext[]" id="{{$conte->id}}" value="{{$conte->valorlista}}">
                                    @endif

                                @endforeach


                                @foreach ($elementos as $elemento)
                                    @if($elemento->listadouid==$conte->idlista)
                                        @if($elemento->id==$conte->valorlista)
                                            <div class="printertext">{{$elemento->elemnombre }}</div>
                                        @endif
                                    @endif
                                @endforeach
                              </div>

					      <input type="hidden" name="registro[]" value="{{$conte->id}}">
					      <input type="hidden" name="tipodato[]" value="{{$conte->tipodato}}">
					    </div><br />
				    @endif

					@if ($conte->tipodato == "documento")
                        <br class="form-group col-md-12 col-sm-12 col-xs-12" />
                        <div class="form-group col-md-12 col-sm-12 col-xs-12">
                                <label id="documentFile{{$conte->id}}" id_cont="{{$conte->id}}" class="file_upload btn btn-primary  pull-right no-print"><i class="fa fa-plus"></i> Examinar</label>
                                <label for="tipoActividadDescrip" style="color:black; font-weight: bold;" class="printertitle">&nbsp;&nbsp;&nbsp;{{$conte->etiqueta}}</label>

                        <br />&nbsp;&nbsp;&nbsp;
                        <ul id="documentList{{$conte->id}}" class="mailbox-attachments" width="100%">
                                @include('actividad.viewfiles')
                        </ul>

                        <ul id="documentList{{$conte->id}}" class="mailbox-attachments2" width="100%" style="display: none;">
                                @include('actividad.viewfiles2')
                        </ul>
                        </div>
                    @endif

                    @if ($conte->tipodato == "titulo")
                        <div class="form-group col-md-12 col-sm-12 col-xs-12">
                            <h3 class="box-title" style="color:black;" class="printertitle"> {{$conte->etiqueta}}</h3>
                        </div>
                    @endif

                    @if ($conte->tipodato == "desing")
                    <br class="clearfix col-md-12 col-sm-12 col-xs-12" />
                    @endif


                    @if ($conte->tipodato == "actividad")
                    <div class="form-group col-md-12 col-sm-12 col-xs-12">
                        <a href="#" id="newActivityGrupo" data-target="#actGrupoModal" data-toggle="modal" class="btn btn-primary no-print pull-right"><i class="fa fa-plus"></i> Agregar</a>
                    <a href="#" id="newActivityGrupoTask" data-target="#actGrupoModalTask" data-toggle="modal" idact="{{$actividad->id}}" class="btn btn-primary no-print pull-right"><i class="fa fa-search"></i> Asociar</a>
                        <label style="color:black; font-weight: bold;" class="printertitle">&nbsp;&nbsp;&nbsp; {{$conte->etiqueta}}</label> &nbsp
                    </div>
                    <div class="row" id="divActividadGrupo">
                        @foreach ($actividadesItmen as $item)
                            <div class="col-md-4 col-sm-6 col-xs-12" >
                                <div class="info-box2">
                                <span class="info-box2-icon" style="background-color:{{$item->tipoactcolor}};">&nbsp;</span>
                                @if ($item->actividadstatus=='A')<div class="info-box2-content"><span class="badge label2-primary pull-right">&nbsp;Abierta&nbsp;</span>
                                @elseif ($item->actividadstatus=='C')<div class="info-box2-content"><span class="badge label2-success pull-right">&nbsp;Cerrada&nbsp;</span>
                                @elseif ($item->actividadstatus=='X')<div class="info-box2-content"><span class="badge label2-default pull-right">&nbsp;Cancelada&nbsp;</span>
                                @endif
                                    <span class="info-box2-title">{{$item->titulo}}</span>
                                    <span class="info-box2-text"><?php
                                                                    $date=new DateTime($item->actividadinicio);
                                                                    echo $date->format('d-m-Y H:i');
                                                                ?></span>
                                    <span class="info-box2-text">{{$item->actividaddescip}} &nbsp;</span>
                                    <a href="/actividad/{{$item->id}}/edit" class="small-box-footer no-print" > ver mas <i class="fa fa-arrow-circle-right"></i></a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    @endif

				@endforeach



			</div>
			<div class="box-footer no-print">
                <input type="hidden" name="id" value="{{$actividad->id}}">
				<input type="hidden" name="uniopuid" value="{{$conte->uniopuid}}">
				<input type="hidden" name="tipactuid" value="{{$actividad->tipoactividaduid}}">
                <a href="{{ URL::previous() }}" class="btn btn-default btn-lg "><i class="fa fa-reply"></i> <span class="nomobile"> Volver</span> </a>
                <button type="button" class="btn btn-default btn-lg " onclick="javascript:window.print();"><i class="fa fa-print"></i> <span class="nomobile"> Imprimir </span> </button>
				<button type="button" id="btnEditActividad" class="btn btn-primary btn-lg pull-right "><i class="fa fa-save"></i> <span class="nomobile"> Guardar </span></button>
			</div><!-- /.box-footer-->
			</form>
		@endif
		</div>
		<!-- /.box-body -->



			@foreach ($contenidos as $key => $conte)
				@if ($conte->tipodato == "documento")
				<form method="post" style="display: none" id="documentForm{{$conte->id}}">
					<input type="file"  id="documentInput{{$conte->id}}" name="valorfile">
				    <input type="hidden" name="idActidadDoc" value="{{$actividad->id}}">
				    <input type="hidden" name="idcarpetaDoc" value="{{$conte->valorcarpeta}}">
		      		<input type="hidden" name="registroDoc" value="{{$conte->id}}">
		      		<input type="hidden" name="tipodatoDoc" value="{{$conte->tipodato}}">
		      	</form>
	      		@endif
			@endforeach

	</div>


@include('actividad.addparticipante')
@include('actividad.modalcreateactividadgrupo')
@include('actividad.modalassciactividad')
@endsection

@section('implemantations')
<script src="{{ asset('bower_components/colorbox/colorbox.js')}}"></script>
<script src="{{ asset('js/actividad.js')}}"></script>

<script>
    /* FUNCTION AUTOCOMPLETE */

    /* FIN FUNCTION AUTOCOMPLETE */

    function inputCharacters() {
        if (event.keyCode == 13) {
            event.preventDefault();
        }
    }

	$( function() {


        $('[data-toggle="popover"]').popover({
            template:'<div class="popover" role="tooltip"><div class="arrow"></div><h3 class="popover-title"  style="color:black;"></h3><div class="popover-content"></div></div>'
        });
        $('[data-toggle="tooltip"]').tooltip()

		$('select[name=timeinicio2]').val('{{$timebegin2}}').trigger('change');
		$('select[name=timefin2]').val('{{$timeend}}').trigger('change');

		$('select[name=timeinicio2]').on('change', function() {
            if ($('select[name=timeinicio2] option:selected').next().next().val())
                $sig = $('select[name=timeinicio2] option:selected').next().next();
            else
                if ($('select[name=timeinicio2] option:selected').next().val())

                    $sig = $('select[name=timeinicio2] option:selected').next();
                else
                    $sig = $('select[name=timeinicio2] option:selected');

            $('select[name=timefin2]').val($sig.val()).trigger('change');
		});

		$('#dateinicio2').datepicker({
			autoclose: true,
			format: 'dd-mm-yyyy',
			language: 'es'
		});

		$('#datefin2').datepicker({
			autoclose: true,
			format: 'dd-mm-yyyy',
			language: 'es',
			setStartDate:'{{$datebegin}}'
		 });

		 $('#dateinicioAG').datepicker({
			autoclose: true,
			format: 'dd-mm-yyyy',
			language: 'es'
		});

		$('#datefinAG').datepicker({
			autoclose: true,
			format: 'dd-mm-yyyy',
			language: 'es',
			setStartDate:'{{$datebegin}}'
		 });

		$(document).ready(function(){
				@foreach ($contenidos as $key => $conte)
						@if ($conte->tipodato == "documento")
							$(".group{{$conte->id}}").colorbox({
                                                                rel:'group{{$conte->id}}',
                                                                width:"80%",
                                                                height:"80%",
                                                                transition:"fade",
                                                                slideshow:false,
                                                                arrowKey:true,
                                                                closeButton:true
                                                            });
						@endif
				@endforeach
		});

@foreach ($contenidos as $key => $conte)
	@if ($conte->tipodato == "date")
		$('#date{{$conte->id}}').datepicker({
			autoclose: true,
			format: 'dd-mm-yyyy',
			language: 'es'

			});
	@endif
@endforeach



});
</script>
@endsection
