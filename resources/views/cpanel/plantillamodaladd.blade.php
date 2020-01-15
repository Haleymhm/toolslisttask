<div class="modal modal-default fade" id="aplicarPlantilla" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="box-title">Selecciones una plantilla</h4>
                    </div>
                <form action="/cpanel/empresa/aplicaplantilla" autocomplete="off" method="POST" id="formAplicarPlantilla">
                    <div class="modal-body">
                        @csrf
                        <input type="hidden" name="empresauid" value="{{$id}}" />
                        <div class="col-xs-12 col-sd-12 col-md-12">
                            <select name="plantillauid" class="form-control select2" style="width:95%">
                                <option></option>
                            @foreach($empresaAll as $plant)
                            <option value="{{$plant->id}}">{{$plant->empresanombre }}</option>
                            @endforeach
                            </select>
                        </div>

                    </div>

                <div class="modal-footer">
                    <div class="col-xs-12 col-sd-12 col-md-12">
                        <button type="button" class="btn btn-lg btn-default pull-left" data-dismiss="modal"><i class="fa fa-reply" title="Volver"></i> Volver</button>
                        <button type="submit" class="btn btn-lg btn-info pull-right" ><i class="fa fa-save" ></i> Aplicar</button>
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>
