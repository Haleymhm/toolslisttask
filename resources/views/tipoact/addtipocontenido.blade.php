<!-- INICIO MODAL ADD TIPO CONTENIDO -->
<div class="modal modal-default fade" id="add-tipocontenido" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title " id="exampleModalLabel">Agregar Contenido</h4>
      </div>

      <div class="modal-body">

          <form action="{{ route('tipoact.addcontenido') }}" autocomplete="off" method="POST">
            @csrf
            <input type="hidden" class="form-control" name="empresauid" value="{{Auth::user()->uidempresa }}" >
            <input type="hidden" class="form-control" name="tipoactid" value="{{$tipoactividad->id}}" >
            <input type="hidden" class="form-control" name="tipoactuid" value="{{$tipoactividad->uid}}" >

            <div class="form-group col-xs-12 col-sd-12 col-md-12">
              <label for="tipoActividadNombre">T&iacute;tulo</label>
              <input type="text" class="form-control" id="titulo" name="titulo" required />
            </div>

            <div class="form-group col-xs-12 col-sd-12 col-md-12">
              <label for="tipoActividadDescrip">Tipo de Contenido</label>
              <select name="tipo" class="form-control select2" style="width: 100%;" id="id_tipo" required />
              <option></option>
                @foreach ($contipos as $contipo2)
                  <option value="{{$contipo2->id}}">{{$contipo2->conttipodesc}}</option>
                @endforeach
              </select>
            </div>

            <div class="form-group col-xs-12 col-sd-12 col-md-12" id="divListados">
                <label for="tipoActividadDescrip">Listas</label>
                <select name="idlista" class="form-control select2" style="width: 100%;" id="idlista" />
                <option>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option>
                    @foreach ($listas as $lista)
                    <option value="{{$lista->id}}">{{$lista->nombrelista}}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group col-xs-12 col-sd-12 col-md-4" >
              <label for="Status">Mostar  en tabla</label><br />
              <input type="checkbox" name="mostar" data-toggle="toggle" data-widget="50" data-height="35" data-style="ios" data-onstyle="success" data-offstyle="default" data-on="Si" data-off="No" >
            </div>

            <div class="form-group col-xs-12 col-sd-12 col-md-2">
              <label for="Parent">Posici&oacute;n</label>
              <input type="number" name="posicion" class="form-control" value="{{$nColumnas}}" required />
            </div>

            <div class="form-group col-xs-12 col-sd-12 col-md-3" style="text-align:center;" >
                <label for="Status">Obligatorio</label><br />
                <input type="checkbox" name="obligatorio" data-toggle="toggle" data-widget="50" data-height="35" data-style="ios" data-onstyle="success" data-offstyle="default" data-on="Si" data-off="No" >
            </div>
            <div class="form-group col-xs-12 col-sd-12 col-md-3">
              <label for="Status" >Status</label>
                <select name="status" class="form-control select2" style="width: 100%;" required />
                  <option value="A" >Activo</option>
                  <option value="I" >Inactivo</option>
                </select>
            </div>
            <div class="form-group col-xs-12 col-sd-12 col-md-12">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cerrar</button>
                <button type="submit" class="btn btn-primary pull-right">Agregar</button>
            </div>

      </div>
      <div class="modal-footer">

      </div>
      </form>
    </div>

  </div>
</div>
<!-- FIN MODAL ADD TIPO CONTENIDO  -->
