@extends('layouts.app')

@section('cssDataTables')
    <link rel="stylesheet" href="{{ asset('bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css')}}">
@endsection

@section('jsDataTables')
    <script src="{{ asset('bower_components/datatables.net-bs/js/jquery.dataTables.min.js')}}"></script>
    <script src="{{ asset('bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js')}}"></script>
    <script>
    $(document).ready(function() {
        $('#example').DataTable({
            "lengthMenu": [[25, 50, -1], [ 25, 50, "todos"]],
            "order": [[ 0, "asc" ]],
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
        <h3 class="box-title" >Tipos de Actividad</h3><br />
        <div class="box-tools">
            @can('actividad.create')
            <a data-target="#add-tipoact" data-toggle="modal" class="btn btn-info pull-right">Nuevo &nbsp;&nbsp;&nbsp;<i class="fa fa-plus-square"></i></a>
            @endcan
        </div>
    </div>


    <!-- /.box-header -->
    <div class="box-body"><br />
        <table class="table table-hover table-condensed table-responsive" style="width:100%" id="example" >
            <thead>
        <tr>
            <th>Tipo de Actividad</th>
            <th class="nomobile">Pertenece a:</th>
            <th width="75px" class="nomobile">Etiqueta</th>
            <th width="25px">Status</th>
            <th width="25px" >&nbsp;</th>
        </tr></thead>
        <tbody>
        @foreach ($tipoactividads as $tipoact)

        <tr>
            <td>{{ $tipoact->titulo }}</td>
            <td class="nomobile">
                @foreach ($grupotipoactividads as $ta)
                    @if ($ta->id == $tipoact->parent)
                        {{ $ta->titulo }}
                    @endif
                @endforeach
            </td>
            <td class="nomobile">
                <span class="badge" style="background-color:{{ $tipoact->tipoactcolor  }};">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
            </td>
            <td>
                @if ($tipoact->status == "A")
                    <span class="label label-primary">&nbsp;&nbsp;Activo&nbsp;&nbsp;</span>
                @elseif ($tipoact->status == "I")
                    <span class="label label-warning">Inactivo</span>
                @endif
            </td>


            <td align="center">
                @can('tipoact.edit')
                    <a href="{{ route('tipoact.edit',$tipoact->id) }}" class="badge bg-aqua"><i class="fa fa-edit"></i></a>
                @endcan
            </td>
        </tr>
        @endforeach
        </tbody>

        </table>
    </div>

    <!-- /.box-body -->
</div>



@include('tipoact.modalcreate')
@endsection
