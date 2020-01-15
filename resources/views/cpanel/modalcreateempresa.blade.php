<div class="modal modal-default fade" id="new-empresa" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form action="empresa/store" autocomplete="on" method="POST" id="formCreateEmpresa">
                <div class="modal-body">


                    @csrf
                    <div class="form-group col-xs-12 col-sm-12 col-lg-12">
                        <h4 class="box-title">Datos de la Empresa</h4>
                    </div>
                    <div class="form-group col-xs-12 col-sm-3 col-lg-3">
                            <label for="tipoActividadNombre">C&oacute;digo</label>
                            <input type="text" class="form-control" id="tipoActividadNombre" name="codemp"/>
                    </div>
                    <div class="form-group col-xs-12 col-sm-9 col-lg-9">
                        <label for="tipoActividadNombre">Nombre</label>
                        <input type="text" class="form-control" id="tipoActividadNombre" name="nomemp" required />
                    </div>

                    <div class="form-group col-xs-12 col-sm-12 col-lg-12">
                        <label for="tipoActividadDescrip">Descripci&oacute;n</label>
                        <textarea type="text" class="form-control " id="tipoActividadNombre" name="diremp" onkeyup="textAreaAdjust(this)" style="overflow:hidden"></textarea>
                    </div>

                    <div class="form-group col-xs-12 col-sm-12 col-lg-12">
                        <label for="tipoActividadNombre">Unidad Operativa Inicial</label>
                        <input type="text" class="form-control" id="tipoActividadNombre" name="uniop" required />
                    </div>



                    <div class="form-group col-xs-12 col-sm-12 col-lg-12">
                        <h4 class="box-title">Datos del Contacto</h4>
                    </div>
                    <div class="form-group col-xs-12 col-sm-12 col-lg-12">
                            <label for="tipoActividadNombre">Nombre</label>
                            <input type="text" class="form-control" id="tipoActividadNombre" name="nomcont" required />
                    </div>
                    <div class="form-group col-xs-12 col-sm-6 col-lg-6">
                            <label for="tipoActividadNombre">Email</label>
                            <input type="text" class="form-control" id="tipoActividadNombre" name="emailcont" required />
                    </div>
                    <div class="form-group col-xs-12 col-sm-6 col-lg-6">
                            <label for="tipoActividadNombre">Telefono</label>
                            <input type="text" name="tlfcont" class="form-control"  required />
                    </div>

                </div>

            <div class="modal-footer">
                <div class="col-xs-12 col-sd-12 col-md-12">
                    <button type="button" class="btn btn-lg btn-default pull-left" data-dismiss="modal"><i class="fa fa-reply" title="Volver"></i> Volver</button>
                    <button type="submit" class="btn btn-lg btn-info pull-right" id="btnCreateEmpresa-X" ><i class="fa fa-save" ></i> Guardar</button>
                </div>
            </div>
            </form>
        </div>
    </div>
</div>
