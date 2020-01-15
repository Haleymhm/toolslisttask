
<div class="form-group col-xs-12 col-sd-12 col-md-5">
    <div class="row">
        <div class="col-xs-4"> <label style="color:black; text-align:right" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Repite cada</label></div>
        <div class="col-xs-2"><input name="periocidad" type="text" class="form-control" pattern="[0-9.]+" maxlength="3" value="{{ $periocidad }}" ></div>
        <div class="col-xs-6"><select name="tipoperiodo" class="select2" style="width: 100%;">
                <option value="x"></option>
                <option value="d" @if($tipop=='d') {{ "selected" }} @endif>d&iacute;a(s)</option>
                <option value="v" @if($tipop=='v') {{ "selected" }} @endif>Diario (Lunes-Viernes)</option>
                <option value="z" @if($tipop=='z') {{ "selected" }} @endif>Diario (Lunes-Sabado)</option>
                <option value="s" @if($tipop=='s') {{ "selected" }} @endif>semana(s)</option>
                <option value="m" @if($tipop=='m') {{ "selected" }} @endif>mes(es)</option>
                <option value="a" @if($tipop=='a') {{ "selected" }} @endif>a&ntilde;o(s)</option>
            </select>
        </div>
    </div>
</div>

<div class="form-group col-xs-12 col-md-3">
    <div class="row">
        <div class="col-xs-4"><label style="color:black; text-align:right">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Finaliza</label></div>
        <div class="col-xs-8">
            <div class="input-group date">
                <input type="text" name="finperiodo" class="form-control" id="datefinperiodo" value="{{$dapff}}" style="color:black; text-align:center" >
                <div class="input-group-addon"> <span class="glyphicon glyphicon-calendar"></span> </div>
            </div>
        </div>
    </div>
</div>


<div class="form-group col-xs-12 col-md-5">
    <div class="row">
        <div class="col-xs-4"><label style="color:black; text-align:right">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Programa</label></div>
        <div class="col-xs-8">
            <select name="programa" class="form-control select2" style="width: 100%;">
                <option></option>
                @foreach ($programas as $programa)
                    <option value="{{$programa->id}}" @if($actividad->programauid == $programa->id) {{ "selected" }} @endif>{{$programa->prognombre}}</option>
                @endforeach
                @foreach ($programasAll as $programAll)
                    @if($actividad->programauid == $programAll->id)
                        <option value="{{$programAll->id}}"  selected >{{$programAll->prognombre}}</option>
                    @endif
                @endforeach
            </select>
        </div>
    </div>
</div>

<div class="form-group col-xs-12 col-md-12">
    <div class="row">
        <div class="col-xs-4 pull-right">
                <button type="button" id="btnActividadPeriodica" class="btn btn-primary btn-lg pull-right">
                    <i class="fa fa-save"></i> <span class="nomobile">  Aplicar </span>
                </button>
            <!-- <button class="btn btn-dark"><i class="fa fa-save"></i> Aplicar</button> -->
        </div>
    </div>
</div>







