<!-- INICIO MODAL EDIT TIPO ACTIVIDAD -->
@foreach ($elementos as $lista2)
<div class="modal modal-default fade" id="edit-tipocontenido-{{$lista2->id}}" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title " id="exampleModalLabel">Editar Tipo de Contenido</h4>
      </div>

      <div class="modal-body">

          <form action="{{ route('listado.editcontenido',$lista2->id) }}" autocomplete="on" method="POST">
            @csrf
            @method('PUT')

            <input type="hidden" class="form-control" name="listadouid" value="{{$lista2->listadouid }}" >

            <div class="form-group col-xs-12 col-sd-12 col-md-12">
              <label for="tipoActividadNombre">Nombre</label>
              <input type="text" class="form-control" id="titulo" name="elemtnombre" value="{{$lista2->elemnombre }}" required />
            </div>

            <div class="form-group col-xs-12 col-sd-12 col-md-12">
              <label for="tipoActividadDescrip">Descripci&oacute;n</label>
              <input type="text" class="form-control" id="titulo" name="elemtdescrip" value="{{$lista2->elemdescip }}"  />
            </div>

            <div class="form-group col-xs-12 col-sd-12 col-md-6">
                <label for="tipoActividadDescrip">Posici&oacute;n</label>
            <input type="number"  class="form-control" id="elempos" name="elempos" value="{{$lista2->elempos }}" />
            </div>

            <div class="form-group col-xs-12 col-sd-12 col-md-6">
              <label for="Status" >Status</label>
                <select name="status" class="form-control select2" style="width: 100%;" required />
                  <option value="A" @if($lista2->status=='A') {{"selected"}} @endif >Activo</option>
                  <option value="I" >Inactivo</option>
                </select>
            </div>

            <div class="form-group col-xs-12 col-sd-12 col-md-12">
              <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
              <button type="submit" class="btn btn-info pull-right">Editar</button>
            </div>

      </div>
      <div class="modal-footer">

      </div>
    </div>
  </form>
  </div>
</div>
@endforeach
<!-- FIN MODAL ADD TIPO ACTIVIDAD  -->
