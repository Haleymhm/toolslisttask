@extends('admin.app')
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

          <div class="box box-solid with-border">
            <div class="box-header">
              <h3 class="box-title">Roles del Sistema</h3>
              <div class="box-tools">
                @can('roles.create')
              	<a href="{{ route('roles.create') }}"  class="btn btn-info pull-right">Nuevo &nbsp;&nbsp;&nbsp;<i class="fa fa-plus-square"></i></a>
                @endcan

              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table class="table table-hover table-condensed table-responsive" id="example">
                  <thead>
                <tr>
                  <th width="15px"></th>
                  <th>Rol</th>
                  <th>Descripci&oacute;n</th>
                  <th width="25px">Status</th>
                  <th width="25px">Acci&oacute;n</th>
                </tr></thead>
                <tbody>
                @foreach ($roles as $rol)
                <tr>
                  <td></td>
                  <td>{{ $rol->name }}</td>
                  <td>{{ $rol->description }}</td>
                   <td>
                      @if ($rol->status == "A")
                          <span class="label label-primary">&nbsp;&nbsp;Activo&nbsp;&nbsp;</span>
                      @elseif ($unidadop->unidadopstatus == "I")
                          <span class="label label-warning">Inactivo</span>
                      @endif
                  </td>
                  <td align="center">
                    @can('roles.edit')
                      <a href="{{ route('roles.edit',$rol->id) }}" title="Editar" class="badge bg-aqua"><i class="fa fa-edit"></i></a>
                    @endcan

                  </td>
                </tr>
                        @endforeach
                    </tbody>
              </table>
            </div>

            <!-- /.box-body -->
          </div>
          <!-- /.box -->


@endsection
