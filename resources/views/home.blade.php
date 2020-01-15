@extends('layouts.app')

@section('cbouniope')
  @include('partials.cbouniope')
  @include('partials.menuta') 
@endsection

@section('content')
  @include('partials/taks')
    <div class="row justify-content-center">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Proximas Actividades</h3>

              <div class="box-tools">
                <div class="input-group input-group-sm" style="width: 150px;">
                  <input type="text" name="table_search" class="form-control pull-right" placeholder="Search">

                  <div class="input-group-btn">
                    <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                  </div>
                </div>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body table-responsive no-padding">
              <table class="table table-hover">
                <tr>
                  <th></th>
                  <th>Titulo de Actividad</th>
                  <th>Fecha</th>
                  <th>Tipo</th>
                  <th></th>
                </tr>
                @foreach($actividades as $actividad)

                <tr>
                  <td></td>
                  <td>{{$actividad->actividadtitulo}}</td>
                  <td>{{$actividad->actividadinicio}}</td>
                  <td><span class="label" style="background-color:{{ $actividad->actividadcolor  }};">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></td>
                  <td>
                    <a href="{{ route('actividad.show',$actividad->id) }}" class="btn btn-default btn-xs"><i class="fa fa-search"></i></a>
                  </td>
                </tr>

        @endforeach
                
              </table>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
    </div>

@endsection
