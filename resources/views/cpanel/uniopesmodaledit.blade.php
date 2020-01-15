@foreach ($uniopes as $uniope)
<div class="modal modal-default fade" id="edit-uniop-{{$uniope->id}}" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="box-title">Unidad Operativa</h4>
                    </div>
                <form action="/cpanel/empresa/edituniop" autocomplete="on" method="POST" id="formEditUniop">
                    <div class="modal-body">
                        @csrf
                        <input type="hidden" name="empresauid" value="{{ $empresas->id}}">
                        <input type="hidden" name="uniopuid" value="{{ $uniope->id}}">
                        <div class="form-group col-xs-12 col-sm-12 col-lg-12">

                        </div>
                        <div class="form-group col-xs-12 col-sm-12 col-lg-12">
                                <label for="tipoActividadNombre">Nombre</label>
                                <input type="text" class="form-control" id="tipoActividadNombre" name="uniop" value="{{$uniope->unidadopnombre}}" required />
                        </div>

                        <div class="form-group col-xs-12 col-sm-6 col-lg-6">
                            <label for="tipoActividadNombre">Status</label>

                            <select name="status" class="form-control select2" style="width:100%">
                                <option ></option>
                                <option value="A"  @if ($uniope->unidadopstatus == 'A' ) {{ "selected"}}  @endif>Activo</option>
                                <option value="I"  @if ($uniope->unidadopstatus == 'I' ) {{ "selected"}}  @endif>Inactivo</option>
                            </select>
                        </div>

                    </div>

                <div class="modal-footer">
                    <div class="col-xs-12 col-sd-12 col-md-12">
                        <button type="button" class="btn btn-lg btn-default pull-left" data-dismiss="modal"><i class="fa fa-reply" title="Volver"></i> Volver</button>
                        <button type="submit" class="btn btn-lg btn-info pull-right" id="btnCreateUniOp++" ><i class="fa fa-save" ></i> Guardar</button>
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>
@endforeach
