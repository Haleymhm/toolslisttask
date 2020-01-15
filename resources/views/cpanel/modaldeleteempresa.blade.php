@foreach($datEmpresas as $emp)
<div class="modal modal-default fade" id="deleteEmpresa-{{ $emp['uid'] }}" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="box-title">ELIMNAR EMPRESA</h4>
                    </div>
                <form action="/cpanel/empresa/deleteempresa" autocomplete="off" method="POST" id="formDeleteEmpresa">
                    <div class="modal-body">
                        @csrf
                        <input type="hidden" name="empresauid" value="{{ $emp['uid'] }}" />
                        <label style="color:#000000;">
                            Esta seguro de eliminar la empresa:
                            <h4> <strong> {{ $emp['nombre'] }} </strong> ?</h4>
                        </label>
                    </div>

                <div class="modal-footer">
                    <div class="col-xs-12 col-sd-12 col-md-12">
                        <button type="button" class="btn btn-lg btn-default pull-left" data-dismiss="modal"><i class="fa fa-reply" title="Volver"></i> Volver</button>
                        <button type="submit" class="btn btn-lg btn-danger pull-right" ><i class="fa fa-save" ></i> Aceptar</button>
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>
@endforeach
