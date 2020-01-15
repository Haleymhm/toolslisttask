@extends('layouts.app')

@section('libraries')
<link rel="stylesheet" href="{{ asset('bower_components/colorbox/colorbox.css')}}" />


@endsection
@section('notificaciones')
    @include('partials.notify-vencida')
    @include('partials.notify-day')
@endsection
@section('cbouniope')
  @include('partials.cbouniope')
  
@endsection

@section('content')

<div class="box box-solid box-default">

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
                                &nbsp;&nbsp;<i class="fa fa-clock-o" data-toggle="tooltip" data-placement="bottom" title="Pertenece a
                                @foreach($programasAll as $pr)
                                    @if ($pr->id==$actividad->programauid)
                                        {{$pr->prognombre}}
                                    @endif
                                @endforeach
                                "></i>
                            @endif

                            @if($actividad->actividadgrupouid!='')
                                &nbsp;&nbsp;<i class='fa fa-list'data-toggle='tooltip' data-placement='bottom'
                                @foreach($actividadesParent as $actp)
                                    title='Pertenece a "{{$actp->titulo}}"
                                    <?php $d=date_create($actp->actividadinicio); echo date_format($d,'d/m/Y - H:i'); ?>
                                @endforeach
                                '></i>
                            @endif


			 <br /><small id="descripcion">{{$actividad->actividaddescip}}</small>


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
				<a role="button" data-toggle="collapse" href="#collapseEditDetailsActividad" aria-expanded="flase" aria-controls="collapseExample"><i class="fa fa-map-marker"></i>&nbsp;{{$actividad->actividadlugar}}</a><br />
				@endif
				<a role="button" data-toggle="collapse" href="#collapseEditDetailsActividad" aria-expanded="false" aria-controls="collapseExample"><i class="fa fa-users"></i></a>
					<small>
          				<?php
          					$i=0; $j=0;
          					foreach ($actividadUsers as $auser){
          						if($auser->responsable==1){ echo '<span class="label bg-green" data-toggle="tooltip" data-placement="bottom" title="Responsable de la actividad">'. $auser->email .'</span>';
          						}elseif($auser->responsable==2){ echo '<span class="label bg-light-blue" data-toggle="tooltip" data-placement="bottom" title="Editor de actividad">'. $auser->email .'</span>';
          						}elseif($auser->responsable==3){ echo '<span class="label">'. $auser->email. '</span>'; }
          						$i++; $j++;
          						if ($i >= 3){ echo "<br />"; break;}
          						if ($j >= 6){ break; }
          					}
          				?>
				</small>
			</div>

		</div>

		@if($rowCont==0)
		    @include('actividad.roEditDetailsActividad')
		@else
		<div class="collapse " id="collapseEditDetailsActividad">
  			<div class="well">
                @include('actividad.roEditDetailsActividad')
  			</div>
		</div>


		<form class="form-horizontal" id="formEditActividad" autocomplete="off" method="POST" enctype="multipart/form-data" accept-charset="UTF-8" files="true" >
			@csrf


			<div class="box-body">

				@foreach ($contenidos as $key => $conte)

					@if ($conte->tipodato == "text")
						<div class="form-group col-md-6 col-sd-12 col-xs-12">
							<div class="col-md-12 col-sd-12 col-xs-12">
						      <label for="tipoActividadDescrip" style="color:black;" >{{$conte->etiqueta}}</label>&nbsp; {{$conte->valortexto}}

						    </div>
					    	<input type="hidden" name="registro[]" value="{{$conte->id}}">
					    	<input type="hidden" name="tipodato[]" value="{{$conte->tipodato}}">
					    </div>
				    @endif

					@if ($conte->tipodato == "textarea")
						<div class="form-group col-md-12 col-sd-12 col-xs-12">
							<div class="col-md-12 col-sd-12 col-xs-12">
							    <label for="tipoActividadDescrip" style="color:black;">{{$conte->etiqueta}}</label>&nbsp;&nbsp;{{$conte->valortexto}}
					    	</div>
					      <input type="hidden" name="registro[]" value="{{$conte->id}}">
					      <input type="hidden" name="tipodato[]" value="{{$conte->tipodato}}">
					  </div>
				    @endif
				    @if ($conte->tipodato == "numeric")
						<div class="form-group col-md-6 col-sd-12 col-xs-12">
							<div class="col-md-12 col-sd-12 col-xs-12">
							    <label for="tipoActividadDescrip" style="color:black;">{{$conte->etiqueta}} </label>&nbsp;<?php echo number_format($conte->valornumero); ?>

							</div>
						    <input type="hidden" name="registro[]" value="{{$conte->id}}">
						    <input type="hidden" name="tipodato[]" value="{{$conte->tipodato}}">
					  </div>
				    @endif
				    @if ($conte->tipodato == "date")
						<div class="form-group col-md-4 col-sm-6 col-xs-6">
							<div class="col-md-12 col-sd-12 col-xs-12" >
									<label for="tipoActividadDescrip" style="color:black;">{{$conte->etiqueta}}</label>&nbsp; <?php $d=date_create($conte->valorfecha); echo date_format($d,'d-m-Y'); ?>

					    	</div>
					    	<input type="hidden" name="registro[]" value="{{$conte->id}}">
					    	<input type="hidden" name="tipodato[]" value="{{$conte->tipodato}}">
					    </div>
				    @endif
				    @if ($conte->tipodato == "monto")
						<div class="form-group col-md-6 col-sd-12 col-xs-12">
							<div class="col-md-12 col-sd-12 col-xs-12">
							    <label for="tipoActividadDescrip" style="color:black;">{{$conte->etiqueta}}</label>&nbsp; <?php echo number_format($conte->valornumero,2); ?>

							</div>
							<input type="hidden" name="registro[]" value="{{$conte->id}}">
							<input type="hidden" name="tipodato[]" value="{{$conte->tipodato}}">

					  </div>
				    @endif

				    @if ($conte->tipodato == "lista")
						<div class="form-group col-md-6 col-sd-12 col-xs-12">
							<div class="col-md-12 col-sd-12 col-xs-12">
                              <label for="tipoActividadDescrip" style="color:black;">{{$conte->etiqueta}}</label>
                              @foreach ($elementos as $elemento)
										@if($elemento->listadouid==$conte->idlista)
											@if($elemento->id==$conte->valorlista) {{$elemento->elemnombre }} @endif
										@endif
						      		@endforeach

					    	</div>
					      <input type="hidden" name="registro[]" value="{{$conte->id}}">
					      <input type="hidden" name="tipodato[]" value="{{$conte->tipodato}}">
					    </div>
				    @endif

						@if ($conte->tipodato == "documento")
						<br class="clearfix col-md-12 col-sm-12 col-xs-12" />
						<div class="col-md-12 col-sm-12 col-xs-12">
								<!-- <label id="documentFile{{$conte->id}}" id_cont="{{$conte->id}}" class="file_upload btn btn-primary  pull-right"><i class="fa fa-plus"></i> Examinar</label> -->
								<label for="tipoActividadDescrip" style="color:black;">{{$conte->etiqueta}}</label>


						  <ul id="documentList{{$conte->id}}" class="mailbox-attachments clearfix" width="100%">
								@include('actividad.viewfiles')
						  </ul>
					    </div>
                    @endif

                    @if ($conte->tipodato == "titulo")
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <h3 class="box-title" style="color:black;" > {{$conte->etiqueta}}</h3>
                        </div>
                    @endif

                    @if ($conte->tipodato == "desing")
                    <br class="clearfix col-md-12 col-sm-12 col-xs-12" />
                    @endif


                    @if ($conte->tipodato == "actividad")
                    <div class="col-md-12 col-sm-12 col-xs-12">
                       <!-- <a href="#" id="newActivityGrupo" data-target="#actGrupoModal" data-toggle="modal" class="file_upload btn btn-primary  pull-right"><i class="fa fa-plus"></i> Agregar</a>-->
                        <label style="color:black;"> {{$conte->etiqueta}}</label> &nbsp;

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
                                    <span class="info-box2-text">{{$item->actividadlugar}}</span>
                                    <a href="/actividad/{{$item->id}}/edit" class="small-box-footer" > ver mas <i class="fa fa-arrow-circle-right"></i></a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    @endif

				@endforeach



			</div>
			<div class="box-footer">
                <input type="hidden" name="id" value="{{$actividad->id}}">
				<input type="hidden" name="uniopuid" value="{{$conte->uniopuid}}">
				<input type="hidden" name="tipactuid" value="{{$actividad->tipoactividaduid}}">
				<a href="{{ URL::previous() }}" class="btn btn-default">Volver</a>
				<!-- <button type="button" id="btnEditActividad" class="btn btn-primary pull-right">Guardar</button> -->
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

@endsection

@section('implemantations')
<script src="{{ asset('bower_components/colorbox/colorbox.js')}}"></script>

<script>


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
