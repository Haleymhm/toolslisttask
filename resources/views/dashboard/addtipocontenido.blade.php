<!-- INICIO MODAL ADD TIPO ACTIVIDAD -->
<div class="modal modal-default fade" id="add-tipocontenido" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title " id="exampleModalLabel">Agregar Item </h4>
      </div>

      <div class="modal-body">

          <form action="{{ route('dashboard.addcontenido') }}" autocomplete="off" method="POST">
            @csrf

            <input type="hidden" name="dashboarduid" value="{{$dashboard->id}}">
            <div class="form-group col-xs-12 col-sd-12 col-md-12">
              <label for="tipoActividadNombre" style="color:black;">Item Tipo </label>
              <select name="itemtipo" class="form-control select2" style="width: 20%;" required>
                  <option value="G" selected>Gráfico</option>
                  <option value="T" >Total</option>
              </select>
            </div>

            <div class="form-group col-xs-12 col-sd-12 col-md-6">
              <label for="tipoActividadDescrip" style="color:black;">Tipo de Actividad</label>
              <select name="tipoactuid" class="form-control select2" style="width: 100%;" id="id_tipo" required />
                    <option value=""></option>
                @foreach ($tipoacts as $item)
                    <option value="{{ $item->uid }}">{{ $item->titulo }}</option>
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
                <select name="itemoperacion" class="form-control select2" style="width: 100%;" >
                    <option></option>
                    <option value="AC">Agrupar y contar</option>
                    <option value="AS">Agrupar y sumar</option>
                    <option value="AP">Agrupar y promediar</option>
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
                <select name="itemgrafico" class="form-control select2" style="width: 100%;" >
                    <option></option>
                    <option value="B">Barras</option>
                    <option value="T">Torta</option>
                    <option value="D">Dona</option>
                    <option value="L">Linea</option>
                </select>
            </div>

            <div class="form-group col-xs-12 col-sd-12 col-md-2">
              <label for="Parent" style="color:black;">Posición</label>
            <input type="number" name="itempos" class="form-control" value="{{$nColumnas}}" required />
            </div>

            <div class="form-group col-xs-12 col-sd-12 col-md-3">
              <label for="Status" style="color:black;">Status</label>
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
<!-- FIN MODAL ADD TIPO ACTIVIDAD  -->
