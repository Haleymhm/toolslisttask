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


          <div class="box box-solid with-border">
            <div class="box-header">
              <h3 class="box-title">Grupos de Tipo de Actividad</h3>
              <div class="box-tools">
                @can('tipoact.create')
              	<a href="{{ route('grupotipoact.create') }}"  class="btn btn-info pull-right">Nuevo &nbsp;&nbsp;&nbsp;<i class="fa fa-plus-square"></i></a>
                @endcan

              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <br />
              <table class="table table-hover table-condensed table-responsive" id="example">
                <thead>
                <tr>
                  <th>Grupo Tipo de Actividad</th>
                  <th class="nomobile">Grupo padre</th>
                  <th width="25px" class="nomobile">Posici&oacute;n</th>
                  <th width="20px" class="nomobile">Icono</th>
                  <th width="25px">Status</th>
                  <th width="25px">Acci&oacute;n</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($grupotipoactividads as $key=>$grupotipoact)

                <tr>

                  <td>{{ $grupotipoact->titulo}}</td>
                  <td class="nomobile">
                      @foreach ($grupotipoactividads as $ta)
                        @if ($ta->id == $grupotipoact->parent)
                          {{ $ta->titulo }}
                        @endif
                      @endforeach
                  </td>
                  <td align="center" class="nomobile">{{ $grupotipoact->orden }}</td>
                  <td align="center" class="nomobile"><i class="{{ $grupotipoact->icono }}"></i></td>
                  <td>
                      @if ($grupotipoact->status == "A")
                          <span class="label label-primary">&nbsp;&nbsp;Activo&nbsp;&nbsp;</span>
                      @elseif ($grupotipoact->status == "I")
                          <span class="label label-warning">Inactivo</span>
                      @endif
                  </td>

                  <td align="center">
                    @can('grupotipoact.edit')
                    <a href="{{ route('grupotipoact.edit',$grupotipoact->id) }}" class="badge bg-aqua"><i class="fa fa-edit"></i></a>
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
        </div>

@endsection
