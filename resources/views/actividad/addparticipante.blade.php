<!-- INICIO MODAL PARTICIPANTES -->
<div class="modal fade" id="add-invitado" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="exampleModalLabel">Agregar Participantes</h4>
      </div>

        <form id="formAddParticipante" class="form-horizontal" action="{{ route('actividad.adduser')}}" autocomplete="on" method="POST">
            <div class="modal-body">
                @csrf
                <input type="hidden" name="collapseded" value="in">
                <input type="hidden" name="idactividad" value="{{$actividad->id}}">

                <div class="form-group col-xs-12  col-md-4">
                        <label style="color:black;">Rol</label>
                        <select name="resp" id="idresp" class="form-control" style="width: 100%;">
                            <option value="1">Responsable</option>
                            <option value="2">Editor</option>
                            <option value="3" selected>Participante</option>
                            <option value="4">Participante externo</option>
                        </select>
                </div>

                <div class="form-group col-xs-12 col-md-12" id="divUserInter">
                    <label style="color:black;">Participante </label>
                    <select name="useruid" id="cboUserUid" class="form-control select2" style="width: 100%;">
                        <option></option>
                        @foreach($colUsers as $colUser)
                            <option value="{{$colUser->id}}">{{$colUser->name}} - {{$colUser->email}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-xs-12 col-md-12" id="divNomPart">
                    <label style="color:black;">Nombre </label>
                    <input type="text" name="nombreinvitado" id="txtNomPart" class="form-control">
                </div>
                <div class="form-group col-xs-12 col-md-12" id="divEmailPart">
                    <label style="color:black;">Email </label>
                    <input type="email" name="emailinvitado" id="txtEmailPart" class="form-control" onKeyUp="javascript:validateMail('txtEmailPart')">
                </div>


            </div>
            <div class="modal-footer">
                <div class="form-group col-xs-12 col-sd-12 col-md-12">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary pull-right" id="btnAddParticipante">Agregar</button>
                </div>
            </div>
        </form>
    </div>

  </div>
</div>
<!-- FIN MODAL PARTICIPANTES -->


