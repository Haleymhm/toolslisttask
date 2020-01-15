@extends('layouts.admin')
@section('cssDataTables')
    <link rel="stylesheet" href="{{ asset('bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css')}}">
@endsection
@section('jsDataTables')
    <script src="{{ asset('bower_components/datatables.net/js/jquery.dataTables.min.js')}}"></script>
    <script src="{{ asset('bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js')}}"></script>
    <script>
    $(document).ready(function() {
        $('#tblUser').DataTable({
            "language": {
                "url": "{{ asset('bower_components/datatables.net-bs/lang/Spanish.json') }}",
            }
        });

        $('#tblUniOp').DataTable({
            "language": {
                "url": "{{ asset('bower_components/datatables.net-bs/lang/Spanish.json') }}",
            }
        });

    } );

    </script>
@endsection

@section('content')
@include('partials.messages')
<div class="box box-solid with-border">
    <div class="box-header with-border">
        <h3 class="box-title">Editar Empresa</h3>
        <a class="bt btn-info btn-xs pull-right" data-target="#aplicarPlantilla" data-toggle="modal" style="color:#ffffff;"><i class="fa fa-book"></i> Aplicar Plantilla</a>
    </div>
    <form class="form-horizontal" action="/cpanel/empresa/storeedit" autocomplete="off" method="POST">
        @csrf
        <div class="box-body">
            <input type="hidden" name="id" value="{{$id}}">
            <div class="form-group col-xs-12 col-sd-4 col-md-4">
                <label for="empresaNombre" style="color:black; font-weight: bold;">C&oacute;digo</label>
                <input type="text" class="form-control col-md-5" id="txtEmpreasNombre" name="rutrif"  value="{{$empresas->rutrif}}"/>
            </div>


            <div class="form-group col-md-8 col-sd-8 col-xs-12">
                <label for="empresaNombre" style="color:black; font-weight: bold;">Nombre:</label>
                <input type="text" class="form-control" id="txtEmpreasNombre" name="empresanombre"  value="{{$empresas->empresanombre}}" required />
            </div>

            <div class="form-group col-md-12 col-sd-12 col-xs-12">
                <label for="empresaNombre" style="color:black; font-weight: bold;">Direcci&oacute;n:</label>
                <textarea class="form-control" name="empresadireccion" onkeyup="textAreaAdjust(this)" data-resizable="true" style="overflow:hidden">{{$empresas->empresadireccion}}</textarea>
            </div>

            <div class="form-group col-md-8 col-sd-8 col-xs-12">
                    <label for="empresastatus" style="color:black; font-weight: bold;">Email:</label>
                    <input type="email" name="empresaemail" class="form-control" value="{{$empresas->empresaemail}}" required />
                </div>

            <div class="form-group col-md-4 col-sd-4 col-xs-12">
                <label for="empresaNombre" style="color:black; font-weight: bold;">Tel&eacute;fono:</label>
                <input type="text" name="empresatelefono" class="form-control" value="{{$empresas->empresatelefono}}"/>
            </div>

            <div class="form-group col-xs-12 col-md-6">
                <label for="tipoActividadDescrip" style="color:black; font-weight: bold;" >Vigencia</label>
                <div class="input-group">
                    <input type="text" name="empresaviegencia" class="form-control" value="<?php $d=date_create($empresas->vigencia); echo date_format($d,'d-m-Y'); ?>" id="datevigencia">
                    <div class="input-group-addon "> <span class="glyphicon glyphicon-calendar"></span> </div>
                </div>
            </div>

            <div class="form-group col-xs-12 col-sm-4 col-lg-4">
                <label for="tipoActividadNombre" style="color:black; font-weight: bold;">Status</label>
                <select name="empresastatus" class="form-control select2">
                    <option ></option>
                    <option value="A"  @if ($empresas->empresastatus == 'A' ) {{ "selected"}}  @endif>Activo</option>
                    <option value="I"  @if ($empresas->empresastatus == 'I' ) {{ "selected"}}  @endif>Inactivo</option>
                </select>
            </div>


                <div class="form-group col-xs-12 col-sm-12 col-lg-12">
                    <a href="{{ asset('cpanel/admin')}}" class="btn btn-default">Volver</a>
                    <button type="submit" class="btn btn-primary pull-right">Guardar</button>
                </div>

        </form>

                <div class="form-group col-md-12 col-sd-12 col-xs-12">

                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs" role="tablist">
                        <li role="presentation" class="active"><a href="#users" aria-controls="users" role="tab" data-toggle="tab" style="text-color:white">Usuarios</a></li>
                        <li role="presentation"><a href="#profile" aria-controls="profile" role="tab" data-toggle="tab" style="color:white">Unidades Operativas</a></li>
                    </ul>

                    <!-- Tab panes -->
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane active" id="users">@include('cpanel.usuariostabla')</div>
                        <div role="tabpanel" class="tab-pane" id="profile">@include('cpanel.uniopestabla')</div>
                    </div>

                </div>

            </div>




    </form>
</div>

@include('cpanel.usuariomodalcreate')
@include('cpanel.uniopesmodalcreate')

@include('cpanel.usuariomodaledit')
@include('cpanel.uniopesmodaledit')

@include('cpanel.plantillamodaladd')
@endsection

@section('implemantations')
<script>

$('#datevigencia').datepicker({
    autoclose: true,
    format: 'dd-mm-yyyy',
    language: 'es'
});

</script>

@endsection
