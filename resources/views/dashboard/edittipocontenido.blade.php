<!-- INICIO MODAL EDIT DETALLE DASHBOARD -->
@foreach ($dbItem as $key=>$contenido)
<div class="modal modal-default fade" id="edit-tipocontenido-{{$contenido->id}}" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title " id="exampleModalLabel">Editar Item </h4>
            </div>

            <div class="modal-body">

                <form action="{{ route('dashboard.editcontenido') }}" autocomplete="off" method="POST">
                  @csrf

                  <input type="hidden" name="dashboarduid" value="{{$dashboard->id}}">
                  <input type="hidden" name="id" value="{{$contenido->id}}">
                  <div class="form-group col-xs-12 col-sd-12 col-md-12">
                    <label for="tipoActividadNombre" style="color:black;">Item Tipo </label>
                    <select name="itemtipo" class="form-control select2" style="width: 20%;" required>
                        <option value="G" @if ($contenido->itemtipo == 'G' ) {{"selected"}} @endif>Gráfico</option>
                        <option value="T" @if ($contenido->itemtipo == 'T' ) {{"selected"}} @endif>Total</option>
                    </select>
                  </div>

                  <div class="form-group col-xs-12 col-sd-12 col-md-6">
                    <label for="tipoActividadDescrip" style="color:black;">Tipo de Actividad</label>
                    <select name="tipoactuid" class="form-control select2" style="width: 100%;" id="id_tipo" required />
                          <option value=""></option>
                      @foreach ($tipoacts as $item)
                          <option value="{{ $item->uid }}"
                                @if ($contenido->tipoactuid == $item->uid  ) {{"selected"}} @endif>
                                {{ $item->titulo }}</option>
                      @endforeach

                    </select>
                  </div>

                  <div class="form-group col-xs-12 col-sd-12 col-md-6">
                    <label for="tipoActividadDescrip" style="color:black;">Agrupar Tipo Contenido </label>
                    <select name="agrupartipocontuid" class="form-control select2" style="width: 100%;" id="agrupartipocontuid" />
                    <option value=""></option>

                    </select>
                  </div>

                  <div class="form-group col-xs-12 col-sd-12 col-md-3">
                      <label for="tipoActividadNombre" style="color:black;">Operación </label>
                      <select name="itemoperacion" class="form-control select2" style="width: 100%;" required>
                          <option value="AC"  @if ($contenido->itemoperacion == 'AC' ) {{"selected"}} @endif>Agrupar y contar</option>
                          <option value="AS"  @if ($contenido->itemoperacion == 'AS' ) {{"selected"}} @endif>Agrupar y sumar</option>
                          <option value="AP"  @if ($contenido->itemoperacion == 'AP' ) {{"selected"}} @endif>Agrupar y promediar</option>
                      </select>
                  </div>

                  <div class="form-group col-xs-12 col-sd-12 col-md-4">
                      <label for="tipoActividadDescrip" style="color:black;">desde</label>
                      <select name="agrupartipocontuid2" class="form-control select2" style="width: 100%;" id="agrupartipocontuid2" />
                      <option value=""></option>

                      </select>
                    </div>

                  <div class="form-group col-xs-12 col-sd-12 col-md-3">
                      <label for="tipoActividadNombre" style="color:black;">Grafico </label>
                      <select name="itemgrafico" class="form-control select2" style="width: 100%;" required>
                          <option></option>
                          <option value="B" @if ($contenido->itemgrafico == 'B' ) {{"selected"}} @endif>Barras</option>
                          <option value="T" @if ($contenido->itemgrafico == 'T' ) {{"selected"}} @endif>Torta</option>
                          <option value="D" @if ($contenido->itemgrafico == 'D' ) {{"selected"}} @endif>Dona</option>
                          <option value="L" @if ($contenido->itemgrafico == 'L' ) {{"selected"}} @endif>Linea</option>
                      </select>
                  </div>

                  <div class="form-group col-xs-12 col-sd-12 col-md-2">
                    <label for="Parent" style="color:black;">Posición</label>
                  <input type="number" name="itempos" class="form-control" value="{{$contenido->itempos}}" required />
                  </div>

                  <div class="form-group col-xs-12 col-sd-12 col-md-3">
                    <label for="Status" style="color:black;">Status</label>
                      <select name="status" class="form-control select2" style="width: 100%;" required />
                        <option value="A" @if ($contenido->status == 'A' ) {{"selected"}} @endif>Activo</option>
                        <option value="I" @if ($contenido->status == 'I' ) {{"selected"}} @endif>Inactivo</option>
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
@endforeach
<!-- FIN MODAL EDIT DETALLE DASHBOARD  -->
