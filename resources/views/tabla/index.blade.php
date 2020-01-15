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
                <h3 class="box-title">Actividades</h3><br />
                <div class="box-tools">
                    <a href="{{ route('calendario.index') }}" id="newActivityCAl" data-target="#fullCalModal" class="btn" style="text-transform: initial;"><span>Calendario</span></a>
                @can('actividad.create')
                    <a href="#" id="newActivityTabla" data-target="#fullCalModal" data-toggle="modal" class="btn btn-info pull-right" ><i class="fa fa-plus-square"></i> <span>AGREGAR </span></a>
                @endcan
                </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body"><br />
              <table class="table table-hover table-condensed table-responsive" style="width:100%" id="example">
                <thead>
                <tr>
                  <th width="35px" align="left">Fecha -</th>
                  <th>Tipo</th>
                  <th class="nomobile">Resumen</th>
                  <th width="25px" align="right">Status</th>
                  <th width="10px" align="right">&nbsp;</th>
                </tr></thead>
                <tbody>
                  @foreach($contTables as $contTable)
                  <tr>

                    <td >{{ $contTable['fecha'] }}</td>
                    <td><?php echo $contTable['tipo'] ?></td>
                    <td class="nomobile"><?php echo substr($contTable['resumen'],0,350); ?></td>

                    <td>@if ($contTable['status'] == "A")
                      <span class="label label-primary">&nbsp;&nbsp;Abierta&nbsp;&nbsp;</span>
                    @elseif ($contTable['status']== "C")
                        <span class="label label-success">&nbsp;&nbsp;Cerrada&nbsp;</span>
                    @endif
                  </td>
                    <td align="center">@can('actividad.edit')
                        @if($contTable['uid']!="")
                            <a id="newActivity" href="{{ route('actividad.edit',$contTable['uid']) }}" class="badge bg-aqua pull-right"><i class="fa fa-edit"></i></a>
                        @endif
                      @endcan</td>
                  </tr>
                  @endforeach
                </tbody>
              </table>

            </div>

            <!-- /.box-body -->
          </div>
          <!-- /.box -->

@endsection
