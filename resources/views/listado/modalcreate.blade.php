<!-- INICIO MODAL ADD TIPO ACTIVIDAD -->
<div class="modal modal-default fade" id="add-listado" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title " id="exampleModalLabel">Nuevo Listado</h4>
      </div>

      <div class="modal-body">

          <form action="{{ route('listado.store') }}" autocomplete="on" method="POST">
          	@csrf
            <input type="hidden" class="form-control" name="empresauid" value="{{Auth::user()->uidempresa }}" >
              <div class="form-group col-xs-12 col-sm-12 col-lg-12">
                <label for="tipoActividadNombre">Nombre del Listado</label>
                <input type="text" class="form-control" id="tipoActividadNombre" name="listnombre" required />
              </div>

            <div class="form-group col-xs-12 col-sm-12 col-lg-12">
              <label for="tipoActividadDescrip">Descripci&oacute;n</label>
              <textarea class="form-control" id="tipoActividadDescrip" name="listdescrip" onkeyup="textAreaAdjust(this)" style="overflow:hidden" required> </textarea>
            </div>


      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        <button type="submit" class="btn btn-info" name="continuar" value="add" >Continuar</button>
      </div>
      </form>
    </div>

  </div>
</div>
<!-- FIN MODAL ADD TIPO ACTIVIDAD  -->
