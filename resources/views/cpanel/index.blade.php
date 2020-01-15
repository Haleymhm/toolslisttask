@extends('layouts.admin')
@section('cssDataTables')
    <link rel="stylesheet" href="{{ asset('bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css')}}">
@endsection
@section('jsDataTables')
    <script src="{{ asset('bower_components/datatables.net/js/jquery.dataTables.min.js')}}"></script>
    <script src="{{ asset('bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js')}}"></script>
    <script>
    $(document).ready(function() {
        $('#example').DataTable({
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
        <div class="box-header">
            <h3 class="box-title">Empresas</h3>
            <div class="box-tools">
                    <a href="empresa/export" id="exportExcel" data-target="#fullCalModal" class="btn" style="text-transform: initial;"><i class="fa fa-file-excel-o"></i> <span>Exportar</span></a>
                <a data-target="#new-empresa" data-toggle="modal"  class="btn btn-info pull-right">Nuevo &nbsp;&nbsp;&nbsp;<i class="fa fa-plus-square"></i></a>
            </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <table class="table table-hover table-condensed table-responsive" style="width:100%" id="example">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th width="25px">Unidades Operativas</th>
                        <th width="15px">Usuarios Activos</th>
                        <th width="15px">Espacio Disco</th>
                        <th width="35px">Vigencia&nbsp;&nbsp;</th>
                        <th width="25px">Status</th>
                        <th width="35px">&nbsp;</th>
                    </tr>
                </thead>

                <tbody>
                @foreach($datEmpresas as $emp)
                    <tr>
                        <td>{{$emp['nombre']}}</td>
                        <td>{{$emp['nunipos']}}</td>
                        <td>{{$emp['nusers']}}</td>
                        <td style="text-align:right;" >{{$emp['tsize']}}</td>
                        <td><?php
                                $d=date_create($emp['vigencia']);
                                echo trim(date_format($d,'d-m-Y'));
                            ?>
                        </td>
                        <td>
                            @if ($emp['status']== "A")
                                <span class="label label-primary">&nbsp;&nbsp;Activo&nbsp;&nbsp;</span>
                            @elseif ($emp['status'] == "I")
                                <span class="label label-warning">Inactivo</span>
                            @endif
                        </td>
                        <td style="text-align:center" >
                            <a class="bt btn-info   btn-xs pull-left" style="color:#ffffff;" href="empresa/{{ $emp['uid'] }}/edit" title="Editar"><i class="fa fa-edit"></i></a>
                            <a class="bt btn-danger btn-xs pull-right" data-target="#deleteEmpresa-{{ $emp['uid'] }}" data-toggle="modal" style="color:#ffffff;" title="Borrar">&nbsp;<i class="fa fa-trash-o"></i>&nbsp;</a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
          </table>
        </div>

        <!-- /.box-body -->
      </div>
      <!-- /.box -->
    @include('cpanel.modalcreateempresa')
    @include('cpanel.modaldeleteempresa')
@endsection

