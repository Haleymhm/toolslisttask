<!-- INICIO MODAL EDIT TIPO ACTIVIDAD -->
@foreach ($contenidos as $conten)
<div class="modal modal-default fade" id="edit-tipocontenido-{{$conten->id}}" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title " id="exampleModalLabel">Editar Contenido</h4>
      </div>

      <div class="modal-body">

          <form action="{{ route('tipoact.editcontenido',$conten->id) }}" autocomplete="on" method="POST">
            @csrf
            @method('PUT')
            <input type="hidden" class="form-control" name="empresauid" value="{{Auth::user()->uidempresa }}" >
            <input type="hidden" class="form-control" name="tipoactid" value="{{$tipoactividad->id}}" >
            <input type="hidden" class="form-control" name="tipoactuid" value="{{$tipoactividad->uid}}" >

            <div class="form-group col-xs-12 col-sm-12 col-md-12">
              <label for="tipoActividadNombre">T&iacute;tulo</label>
              <input type="text" class="form-control" id="titulo" name="titulo" value="{{$conten->etiqueta}}" required />
            </div>

            <div class="form-group col-xs-12 col-sm-12 col-md-12">
              <label for="tipoActividadDescrip">Tipo de Contenido</label>
              <select name="tipo" class="form-control select2" style="width: 100%;" id="id_tipo" required disabled readonly/>

                @foreach ($contipos as $contipo2)
                  <option value="{{$contipo2->id}}" @if($contipo2->id== $conten->contenidotipoid ) {{ "selected" }} @endif>{{$contipo2->conttipodesc}}</option>
                @endforeach
              </select>
            </div>
            @foreach ($contipos as $contipo3)

            @if($contipo3->id== $conten->contenidotipoid)
            <div class="form-group col-xs-12 col-sm-12 col-md-12">
            <label for="tipoActividadDescrip">{{$contipo3->tipodato}}</label>
              <select name="idlista" class="form-control select2" style="width: 100%;" id="idlista" />
                <option></option>
                @foreach ($listas as $lista)
                  <option value="{{$lista->id}}"
                                @if($lista->id == $conten->idlista)
                                {{ "selected" }}
                              @endif
                    >{{$lista->nombrelista}}</option>
                @endforeach
              </select>
            </div>
            @endif
            @endforeach
            <div class="form-group col-xs-12 col-sd-12 col-md-4">
            <label for="Status">Mostar en tabla</label><br />
              <input type="checkbox" name="mostar" data-toggle="toggle" data-widget="50" data-height="35"
              data-style="ios" data-onstyle="success" data-offstyle="default" data-on="SI" data-off="NO"
              @if($conten->mostrar=="SI")
                {{ "checked" }}
              @endif
              >
            </div>

            <div class="form-group col-xs-6 col-sm-6 col-md-2">
              <label for="Parent">Posici&oacute;n</label>
              <input type="number" name="posicion" class="form-control" min="1" value="{{$conten->posicion}}" required />

            </div>

            <div class="form-group col-xs-12 col-sd-12 col-md-3" style="text-align:center;" >
                <label for="Status">Obligatorio</label><br />
                <input type="checkbox" name="obligatorio" data-toggle="toggle" data-widget="50" data-height="35"
                data-style="ios" data-onstyle="success" data-offstyle="default" data-on="SI" data-off="NO"
                    @if($conten->obligatorio=="SI")
                        {{ "checked" }}
                    @endif
                >
            </div>
            <div class="form-group col-xs-6 col-sm-6 col-md-3">
              <label for="Status" >Status</label>
                <select name="status" class="form-control select2" required />
                  <option value="A" @if ($conten->status == 'A' ) {{ "selected" }} @endif>Activo</option>
                  <option value="I" @if ($conten->status == 'I' ) {{ "selected" }} @endif>Inactivo</option>
                </select>
            </div>

            <div class="form-group col-xs-12 col-sd-12 col-md-12">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cerrar</button>
                <button type="submit" class="btn btn-primary pull-right">Agregar</button>
            </div>
      </div>
      <div class="modal-footer">
       <!-- <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        <button type="submit" class="btn btn-info">Guardar</button> -->
      </div>
    </div>
  </form>
  </div>
</div>
@endforeach
<!-- FIN MODAL ADD TIPO ACTIVIDAD  -->
