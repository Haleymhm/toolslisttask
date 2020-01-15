@extends('layouts.app')
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
            <h3 class="box-title">{{$descTipAct}}</h3>
              <div class="box-tools">

                <div class="input-group input-group-sm" style="width: 300px;">
                  <input type="text" name="table_search" class="form-control pull-right" placeholder="Search">

                  <div class="input-group-btn">
                    <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                  </div>
                </div>

              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body table-responsive no-padding">
              <table class="table table-condensed table-hover" style="width:100%">

                <tr>
                <th width="15px"></th>
                  @for($j=0; $j<=$i; $j++)
                  <th>{{$headTables[$j]}}</th>
                  @endfor

                  <th class="pull-right" >Accion</th>
                </tr>

                  @foreach($contTables as $contTable)
                  @if($y>0)
                  <tr>
                    <td></td>
                    <td>{{$contTable['fecha']}}</td>

                    <?php echo $contTable['resumen'] ?>

                    <td>@if ($contTable['status'] == "A")
                      <span class="label label-primary">&nbsp;&nbsp;Abierta&nbsp;&nbsp;</span>
                    @elseif ($contTable['status']== "C")
                        <span class="label label-success">&nbsp;Cerrada&nbsp;&nbsp;</span>
                    @elseif ($contTable['status']== "X")
                        <span class="label label-default">Cancelada</span>
                    @endif
                  </td>
                    <td>
                        @can('actividad.edit')
                      <a id="newActivity" href="{{ route('actividad.edit',$contTable['uid']) }}" class="badge bg-aqua pull-right"><i class="fa fa-edit"></i></a>
                      @endcan
                    </td>
                  </tr>
                  @endif
                  @endforeach


              </table>
            </div>

            <!-- /.box-body -->
          </div>
          <!-- /.box -->

@endsection
