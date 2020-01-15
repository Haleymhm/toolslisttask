<!-- INICIO MODAL PARTICIPANTES -->
<div class="modal fade" id="add-invitado" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="exampleModalLabel">Agregar Participantes</h4>
      </div>

      <div class="modal-body">

        <form class="form-horizontal" action="{{ route('actividad.adduser')}}" autocomplete="on" method="POST">
		      @csrf
          <div class="form-group col-xs-12 col-sd-9 col-md-9">
            <input type="hidden" name="collapseded" value="in">
          	<input type="hidden" name="idactividad" value="{{$actividad->id}}">
          	<input type="hidden" name="empresauid" value="">
            <label>Email </label>
            <input type="email" class="form-control" id="recipient-name" name="emailinvitado" required>
          </div>
          <div class="form-group col-xs-12 col-sd-3 col-md-3">
            <label class="checkbox-inline">Responsable</label>
              <input type="checkbox" name="resp" data-toggle="toggle" data-widget="50" data-height="35" data-style="ios" data-onstyle="success" data-offstyle="default" data-on="Si" data-off="No" >
          </div>
          <div class="form-group col-xs-12 col-sd-12 col-md-12">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            <button type="submit" class="btn btn-primary pull-right" value="add" >Agregar</button>
          </div>
      </div>
      <div class="modal-footer">

      </div>
      </form>
    </div>

  </div>
</div>
<!-- FIN MODAL PARTICIPANTES -->
