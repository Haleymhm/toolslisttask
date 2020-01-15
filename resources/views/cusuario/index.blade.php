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
              <h3 class="box-title">Control de Usuarios</h3><br />
              <div class="box-tools">
                @can('cusuario.create')
              	    <a href="{{ route('cusuario.create') }}"  class="btn btn-info pull-right">Nuevo &nbsp;&nbsp;&nbsp;<i class="fa fa-plus-square"></i></a>
                @endcan
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body "><br />
              <table class="table table-hover table-condensed table-responsive" id="example">
                  <thead>
                <tr>
                  <th>Nombre</th>
                  <th class="nomobile">Email</th>
                  <th class="nomobile">Cargo</th>
                  <th width="25px">Status</th>
                  <th width="25px">Accion</th>
                </tr></thead>
                <tbody>
                 @foreach ($users as $user)
                <tr>

                  <td>{{ $user->name }}</td>
                  <td class="nomobile">{{ $user->email }}</td>
                  <td class="nomobile">{{ $user->cargo }}</td>
                  <td>@if ($user->status == "A")
                          <span class="label label-primary">&nbsp;&nbsp;Activo&nbsp;&nbsp;</span>
                      @elseif ($user->status == "I")
                          <span class="label label-warning">Inactivo</span>
                      @endif
                  </td>
                  <td align="center">
                    @can('cusuario.edit')
                    <a href="{{ route('cusuario.edit',$user->id) }}"  class="badge bg-aqua" title="Editar Usuario"><i class="fa fa-edit"></i></a>
                    @endcan
                  </td>
                </tr>
                 @endforeach</tbody>
              </table>
            </div>

            <!-- /.box-body -->
          </div>
          <!-- /.box -->


@endsection
