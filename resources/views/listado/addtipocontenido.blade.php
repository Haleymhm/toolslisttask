<!-- INICIO MODAL ADD TIPO ACTIVIDAD -->
<div class="modal modal-default fade" id="add-elemento" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title " id="exampleModalLabel">Agregar Elementos al Listado</h4>
      </div>

      <div class="modal-body">

          <form action="{{ route('listado.addcontenido') }}" autocomplete="on" method="POST">
            @csrf
            <input type="hidden" class="form-control" name="empresauid" value="{{Auth::user()->uidempresa }}" >
            <input type="hidden" class="form-control" name="listadouid" value="{{$listados->id }}" >


            <div class="form-group col-xs-12 col-sd-12 col-md-12">
              <label for="tipoActividadNombre">Nombre</label>
              <input type="text" class="form-control" id="titulo" name="elemtnombre" required />
            </div>

            <div class="form-group col-xs-12 col-sd-12 col-md-12">
              <label for="tipoActividadDescrip">Descripci&oacute;n</label>
              <input type="text" class="form-control" id="titulo" name="elemtdescrip" />
            </div>

            <div class="form-group col-xs-12 col-sd-12 col-md-3">
                <label for="tipoActividadDescrip">Posici&oacute;n</label>
            <input type="number"  class="form-control" id="elempos" name="elempos" value="{{$pos}}" />
            </div>

            <div class="form-group col-xs-12 col-sd-12 col-md-6">
              <label for="Status" >Status</label><br />
                <select name="status" class="form-control select2" style="width: 75%;" required />
                  <option value="A" >Activo</option>
                  <option value="I" >Inactivo</option>
                </select>
            </div>
      </div>
      <div class="modal-footer">
        <div class="form-group col-xs-12 col-sd-12 col-md-12">
            <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cerrar</button>
            <button type="submit" class="btn btn-info pull-right">Agregar</button>
        </div>
      </div>
    </div>
  </form>
  </div>
</div>
<!-- FIN MODAL ADD TIPO ACTIVIDAD  -->
