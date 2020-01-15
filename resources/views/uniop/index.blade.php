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
              <h3 class="box-title">Unidades Operativas</h3>
              <div class="box-tools">
                @can('uniop.create')
              	<a href="{{ route('uniop.create') }}"  class="btn btn-info pull-right">Nuevo &nbsp;&nbsp;&nbsp;<i class="fa fa-plus-square"></i></a>
                @endcan

              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body"><br />
              <table class="table table-hover table-condensed table-responsive" style="width:100%" id="example">
                  <thead>
                <tr>
                  <th>Nombre de la Unidad</th>
                  <th width="25px">Status</th>
                  <th width="25px">Acci&oacute;n</th>
                </tr></thead>
                <tbody>
                @foreach ($unidadesops as $key=>$unidadop)
                <tr>

                  <td>{{ $unidadop->unidadopnombre }}</td>
                  <td>
                      @if ($unidadop->unidadopstatus == "A")
                          <span class="label label-primary">&nbsp;&nbsp;Activo&nbsp;&nbsp;</span>
                      @elseif ($unidadop->unidadopstatus == "I")
                          <span class="label label-warning">Inactivo</span>
                      @endif
                  </td>
                  <td align="center">
                    @can('uniop.edit')
                    <a href="{{ route('uniop.edit',$unidadop->id) }}" class="badge bg-aqua" title="Editar"><i class="fa fa-edit"></i></a>
                    @endcan

                  </td>
                </tr>
                @endforeach
            </tbody>
              </table>
            </div>
          </div>
          <!-- /.box -->


@endsection
