@extends('layouts.app')
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
@section('notificaciones')
    @include('partials.notify-vencida')
    @include('partials.notify-day')
@endsection
@section('cbouniope')
  @include('partials.cbouniope')
  @include('partials.menuta')
@endsection

@section('content')

<div class="box">
    <div class="box-header">
        <h3 class="box-title">Listados</h3>
        <div class="box-tools">
            @can('tipoact.create')
                <a data-target="#add-listado" data-toggle="modal" class="btn btn-info pull-right">Nuevo &nbsp;&nbsp;&nbsp;<i class="fa fa-plus-square"></i></a>
            @endcan
        </div><!-- /.box-tools -->
    </div><!-- /.box-header -->

    <div class="box-body"><br />
        <table class="table table-hover table-condensed table-responsive" style="width:100%" id="example">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th class="nomobile">Descripci&oacute;n</th>
                    <th >Ver como</th>
                    <th width="25px">Status</th>
                    <th width="25px">Acci&oacute;n</th>
                </tr>
            </thead>
            <tbody>
    @foreach ($listados as $key=>$lista)
                <tr>
                    <td>{{ $lista->nombrelista }}</td>
                    <td class="nomobile">{{ $lista->descplista  }}</td>
                    <td>@if ($lista->ver == "lis") Listado
                        @elseif ($lista->ver == "opv") Opciones Vertical
                        @elseif ($lista->ver == "oph") Opciones Horizontal
                        @endif
                    </td>
                    <td>
                        @if ($lista->status == "A")
                            <span class="label label-primary">&nbsp;&nbsp;Activo&nbsp;&nbsp;</span>
                        @elseif ($lista->status == "I")
                            <span class="label label-warning">Inactivo</span>
                        @endif
                    </td>
                    <td style="text-aling:center">
                        @can('listado.edit')
                            <a href="{{ route('listado.edit',$lista->id) }}" class="badge bg-aqua pull-right" title="Editar"><i class="fa fa-edit"></i></a>
                        @endcan
                    </td>
                </tr>
    @endforeach
            </tbody>
        </table>
    </div><!-- /.box-body -->
</div><!-- /.box -->



@include('listado.modalcreate')
@endsection
