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
            "order": [[ 2, "desc" ]],
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
        <h3 class="box-title">Dashboard</h3>
        <div class="box-tools">
          @can('tipoact.create')
            <a data-target="#add-dashboard" data-toggle="modal" class="btn btn-info pull-right">Nuevo &nbsp;&nbsp;&nbsp;<i class="fa fa-plus-square"></i></a>
          @endcan

        </div>
      </div>
      <!-- /.box-header -->
      <div class="box-body"> <br />
        <table class="table table-hover table-condensed table-responsive" style="width:100%" id="example">
            <thead>
          <tr>
            <th>Nombre</th>
            <th class="nomobile">Descripci&oacute;n</th>
            <th class="nomobile">Posici&oacute;n</th>
            <th width="25px">Status</th>
            <th width="25px">&nbsp;</th>
          </tr></thead>
          <tbody>
          @foreach ($dashboards as $key=>$db)

          <tr>

            <td>{{ $db->dbnom  }}</td>
            <td class="nomobile"><?php echo substr($db->dbdesc,0,300); ?> ...</td>
            <td class="nomobile">{{ $db->dbpos }}</td>
            <td>
                @if ($db->status == "A")
                    <span class="label label-primary">&nbsp;&nbsp;Activo&nbsp;&nbsp;</span>
                @elseif ($db->status == "I")
                    <span class="label label-warning">Inactivo</span>
                @endif
            </td>

            <td >
              <a href="{{ route('dashboard.edit',$db->id) }}" class="badge bg-aqua"><i class="fa fa-edit"></i></a>
            </td>
          </tr>
          @endforeach</tbody>
        </table>

      </div>


    </div>



@include('dashboard.create')
@endsection
