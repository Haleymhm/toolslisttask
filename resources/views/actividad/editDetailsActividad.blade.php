<!-- <div class="box col-xs-12 col-sd-12 col-md-12"> -->

    <form action="" class="form-horizontal" id="formEditDetail" method="POST" enctype="multipart/form-data" accept-charset="UTF-8" files="true" >
        @csrf
        <input type="hidden" value="{{$rowCont}}" name="ncontenido">
        <div class="box-body">
            <div class="row">
                <div class="col-md-6 col-xs-12 with-border">

                    <input type="hidden" name="idactividaddt" value="{{$actividad->id}}">

                    <div class="form-group col-xs-12 col-sd-12 col-md-4">
                        <label  style="color:black;">Inicio</label>
                            <div class="input-group date">
                                <input type="text" name="dateinicio" class="form-control col-xs-6" id="dateinicio2" value="{{$datebegin}}">
                                <div class="input-group-addon"> <span class="glyphicon glyphicon-calendar"></span> </div>
                            </div>
                            <select name="timeinicio2" class="form-control col-md-4 col-xs-6 select2" id="timeinicio2" value="{{$timebegin2}}" style="width: 90%;" required>
                                @include('calendario.horas')
                            </select>
                    </div>

                    <div class="form-group col-xs-12 col-sd-12 col-md-4">
                        <label  style="color:black;">Fin</label>
                        <div class="input-group date">
                            <input type="text" name="datefin" class="form-control col-xs-6" id="datefin2" value="{{$datebegin}}">
                            <div class="input-group-addon"> <span class="glyphicon glyphicon-calendar"></span> </div>
                        </div>
                            <select name="timefin2" class="form-control col-md-4 col-xs-6 select2" id="timefin2" style="width: 90%;" value="{{$timeend}}" required>
                                @include('calendario.horas')
                            </select>
                    </div>

                    <div class="form-group col-xs-12 col-sd-12 col-md-4">
                        <label class="margin-bottom"  style="color:black;">Status</label><br />
                        <select name="status" class="form-control select2" id="cboStatus"  style="width: 100%;" required>
                            <option></option>
                            @if ($actividad->comporta  == "1")
                                <option value="A" @if ($actividad->actividadstatus=='A') {{ "selected" }} @endif>Abierta</option>
                                <option value="C" @if ($actividad->actividadstatus=='C') {{ "selected" }} @endif>Cerrada</option>
                                <option value="X" @if ($actividad->actividadstatus=='X') {{ "selected" }} @endif>Cancelada</option>
                            @else
                                <option value="A" @if ($actividad->actividadstatus=='A') {{ "selected" }} @endif>En edición</option>
                                <option value="C" @if ($actividad->actividadstatus=='C') {{ "selected" }} @endif>Vigente</option>
                                <option value="X" @if ($actividad->actividadstatus=='X') {{ "selected" }} @endif>No vigente</option>
                            @endif
                        </select>
                    </div>

                    <div class="form-group col-xs-12 col-sd-12 col-md-12">
                        <label  style="color:black;"> Descripci&oacute;n de la Actividad </label>
                        <textarea class="form-control" id="actividaddescip" name="actividaddescipdetale" onkeyup="textAreaAdjust(this)" style="overflow:hidden, height:100px">{{$actividad->actividaddescip}} </textarea>
                    </div>

                    <div class="form-group col-xs-12 col-sm-12 col-lg-12">
                        <label  style="color:black;"> Lugar de la Actividad </label>
                        <textarea type="text" class="form-control"  id="actividalugar" name="place" onkeyup="textAreaAdjust(this)" style="overflow:hidden, height:100px">{{$actividad->actividadlugar}}</textarea>
                    </div>
                    <input type="hidden" name="textoptionsEdit" id="textoptionsEdit">
                    <input type="hidden" name="actperiocidauid" id="actperiocidauid" value="{{$actividad->actperiocidauid}}">

                </div><!-- /.form-horizontal -->

                <!--  participantes -->
                <div class="col-md-6 col-xs-12 with-border">
                    <div class="row">
                            <h2 class="page-header">&nbsp; Participantes
                                <a class="bt btn-default btn-xs pull-right" data-target="#add-invitado" data-toggle="modal"><i class="fa fa-plus"></i></a>
                            </h2>
                        <table class="table table-condensed table-bordered table-striped table-hover" id="tblParticipantes">
                            @foreach ($actividadUsers as $auser)
                            <tr id="part_{{$auser->id}}">
                            <td>
                                {{$auser->nombre}}  {{$auser->email}}
                                @if($auser->responsable==1)
                                    <span class="label label-success pull-right"> Responsable </span>
                                @elseif($auser->responsable==2)
                                    <span class="label label-primary pull-right"> &nbsp;&nbsp; Editor &nbsp;&nbsp;</span>
                                @elseif($auser->responsable==3)
                                    <span class="label label-default pull-right"> Participante </span>
                                @elseif($auser->responsable==4)
                                    <span class="label label-warning pull-right"> Participante Externo </span>
                                @endif
                                    </td>
                                    <td style="width: 10px">
                                    <form autocomplete="off" method="POST" id="formRemoveParticipante{{$auser->id}}">
                                    @csrf
                                    <input type="hidden" name="idactividad" value="{{$actividad->id}}">
                                    <input type="hidden" name="iduser" value="{{$auser->id}}">
                                        <button type="button" id="{{$auser->id}}" id_cont="{{$auser->id}}" class="btn-xs btn-danger btn-delete"><i class="fa fa-close"></i></button>
                                        </form>
                                    </td>

                            </tr>
                                @endforeach
                        </table>
                    </div><!-- /.row  -->
                </div><!--  /. participantes -->


            </div><!-- /.row -->

            <div class="row">
                <a class="btn btn-primary" role="button" data-toggle="collapse" href="#collapseExample1" aria-expanded="false" aria-controls="collapseExample">Actividad Periodica</a>
                <div class="collapse well box col-xs-12 col-md-12 with-border" id="collapseExample1">
                        @include('actividad.actividadperiodica')
                </div>
            </div>
        </div><!-- /.box-body -->


    </form>

<!-- </div > -->


