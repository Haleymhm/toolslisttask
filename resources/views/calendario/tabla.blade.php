@extends('layouts.app')
@section('cssDataTables')
    <link rel="stylesheet" href="{{ asset('bower_components/datatables.net-bs/css/dataTables.bootstrap.css')}}">
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
                <h3 class="box-title" >{{$descTipAct}}</h3><br />
                <div class="box-tools">
                    <a href="../../export/actividadxls/{{$id}}" id="exportExcel" data-target="#fullCalModal" class="btn" style="text-transform: initial;"><i class="fa fa-file-excel-o"></i> <span>Exportar</span></a>
                    <a href="{{ route('calendario.index') }}/{{$id}}/user" id="newActivityCAl" data-target="#fullCalModal" class="btn" style="text-transform: initial;"><span>Calendario</span></a>
                    @can('actividad.create')
                    <a href="#" id="newActivityTabla" data-target="#fullCalModal" data-toggle="modal" class="btn btn-info pull-right"><i class="fa fa-plus-square"></i> <span>AGREGAR </span></a>
                    @endcan
                </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body"><br />
              <table class="table table-hover table-condensed table-responsive" style="width:100%" id="example">
                <thead>
                <tr>

                  @for($j=0; $j<=$i; $j++)
                  <th
                            @if ($headTables[$j] == 'Status') width="25px" @endif
                            @if ($headTables[$j] == 'Fecha') width="60px"  @endif
                            @if ( ($headTables[$j] != 'Status') and ($j > 1 ) ) class="nomobile"  @endif

                            >{{$headTables[$j]}} @if ($headTables[$j] == 'Fecha') &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;@endif</th>
                  @endfor

                  <th width="10px" >&nbsp;</th>
                </tr></thead>
                <tbody>
                  @foreach($contTables as $contTable)
                  @if($y>0)
                  <tr>

                    <td>{{$contTable['fecha']}}</td>

                    <?php echo $contTable['resumen'] ?>

                    <td width="25px" align="center">
                        @if ($contTable['status'] == "A")

                      <span class="label label-primary">&nbsp;&nbsp;Abierta&nbsp;&nbsp;</span>
                    @elseif ($contTable['status']== "C")

                        <span class="label label-success">&nbsp;Cerrada&nbsp;&nbsp;</span>
                    @elseif ($contTable['status']== "X")

                        <span class="label label-default">Cancelada</span>
                    @endif
                  </td>
                    <td align="center">@can('actividad.edit')
                      <a id="newActivity" href="{{ route('actividad.edit',$contTable['uid']) }}" class="badge bg-aqua"><i class="fa fa-edit"></i></a>
                      @endcan</td>
                  </tr>
                  @endif
                  @endforeach
                </tbody>
              </table>
            </div>

            <!-- /.box-body -->
          </div>
          <!-- /.box -->

@endsection
