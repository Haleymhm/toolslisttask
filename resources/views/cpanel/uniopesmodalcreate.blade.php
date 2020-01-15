<div class="modal modal-default fade" id="new-uniop" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="box-title">Unidad Operativa</h4>
                    </div>
                <form action="/cpanel/empresa/createuniop" autocomplete="on" method="POST" id="formCreateUniop">
                    <div class="modal-body">
                        @csrf
                        <input type="hidden" name="empresauid" value="{{ $empresas->id}}">
                        <div class="form-group col-xs-12 col-sm-12 col-lg-12">

                        </div>
                        <div class="form-group col-xs-12 col-sm-12 col-lg-12">
                                <label for="tipoActividadNombre">Nombre</label>
                                <input type="text" class="form-control" id="tipoActividadNombre" name="uniop" required />
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
