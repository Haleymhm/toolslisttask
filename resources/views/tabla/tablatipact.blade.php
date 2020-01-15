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


          <div class="box box-solid with-border">
            <div class="box-header">
            <h3 class="box-title">{{$descTipAct}}</h3><br />
            <div class="box-tools">
                @can('actividad.create')
                <a href="#" id="newActivityTabla" data-target="#fullCalModal" data-toggle="modal" class="btn btn-info pull-right"><i class="fa fa-plus-square"></i> <span>AGREGAR </span></a>
              	<!-- <a href="#" id="newActivity" data-target="#fullCalModal" data-toggle="modal" class="btn btn-info pull-right">Nuevo &nbsp;&nbsp;&nbsp;<i class="fa fa-plus-square"></i></a>  -->
                @endcan
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body"><br />
              <table class="table table-hover table-condensed table-responsive" id="example">
                <thead>
                <tr>

                  @for($j=0; $j<$i; $j++)
                  <th @if ($headTables[$j] == 'Status') width="25px" align="right"  @endif
                      @if ($headTables[$j] == 'Fecha') width="35px"  @endif>{{$headTables[$j]}}</th>
                  @endfor
                  <th>Status --</th>
                  <th class="pull-right" width="10px">&nbsp;</th>
                </tr></thead>
                    <tbody>
                  @foreach($contTables as $contTable)
                  <tr>

                    <td>{{$contTable['fecha']}}</td>

                    <?php echo $contTable['resumen'] ?>

                    <td>@if ($contTable['status'] == "A")
                      <span class="label label-primary">&nbsp;&nbsp;Abierta&nbsp;&nbsp;</span>
                    @elseif ($contTable['status']== "C")
                        <span class="label label-success">Cerrada</span>
                    @elseif ($contTable['status']== "X")
                        <span class="label label-default">Cancelada</span>
                    @endif
                  </td>
                    <td align="center">@can('actividad.edit')
                      <a href="{{ route('actividad.edit',$contTable['uid']) }}" class="badge bg-aqua pull-right"><i class="fa fa-edit"></i></a>
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
