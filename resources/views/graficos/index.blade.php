@extends('layouts.app')

@section('libraries')
  <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
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
<div class="box box-primary">
  <div class="row">

  <div class="box-body">
    <form role="form" action="{{ route('graficos.index') }}" autocomplete="on" method="GET">
      <div class="col-xs-12 col-sm-6 col-lg-3">
        <div class="input-group date">
          <label for="actividadtitulo">Desde:</label>
        <input type="text" name="dateinicioI" class="form-control" id="dateinicioI" value="{{$datebegin}}">
          <div class="input-group-addon"> <span class="glyphicon glyphicon-calendar"></span> </div>
        </div>
      </div>

      <div class="col-xs-12 col-sm-6 col-lg-3">
        <div class="input-group date">
        <label for="actividadtitulo">Hasta:</label>
          <input type="text" name="datefinI" class="form-control" id="datefinI" value="{{$dateend }}">
          <div class="input-group-addon"> <span class="glyphicon glyphicon-calendar"></span> </div>
        </div>
      </div>

      <div class="form-group col-xs-12 col-sm-6 col-lg-5">


        <label for="actividadtitulo">Tipo</label><br />

            <select name="tipoactividaduid" class="form-control select2" style="width: 90%;" >
              <option></option>
              @if (($valor=='root') or ($valor=='admin'))
                @foreach($tipoacts as $tipoact)
                  <option value="{{$tipoact->uid}}">{{$tipoact->titulo}}</option>
                @endforeach
              @else
                @foreach($tipoacts as $tipoact)
                  @foreach ($usertipoacts as $usertipoact)
                    @if ($usertipoact->tipoacts_id == $tipoact->id)
                      <option value="{{$tipoact->uid}}">{{$tipoact->titulo}}</option>
                    @endif
                  @endforeach
                @endforeach
              @endif
            </select>

      </div>
      <div class="form-group col-xs-12 col-sm-6 col-md-1">
        <button type="submit" class="btn btn-sm btn-primary pull-right" value="search" ><i class="fa fa-search"></i>&nbsp;Buscar</button>
        <!--<a href="{{ route('graficos.index') }}" class="btn btn-sm btn-info pull-right" value="clear" ><i class="fa fa-refresh"></i>&nbsp;Limpiar</a>-->
      </div>
    </form>
  </div>
  </div>

  <div class="row">
    <div class="box-body">
    @for ($is = 0; $is < $i; $is++)
      @if(($valorActivo[$is]==0) and ($valorCerrado[$is]==0))
          {{" "}}
      @else

        <div class="col-xs-12 col-sm-6 col-md-4">
          <div class="box">
            <div id="piechart{{$is}}" ></div>
          </div>
        </div>

      @endif
    @endfor
  </div>
  </div>


</div>
@endsection



@section('implemantations')
<script>
  $( function() {
    $('#dateinicioI').datepicker({
      autoclose: true,
      format: 'dd-mm-yyyy',
      language: 'es'
    })

    $('#datefinI').datepicker({
      autoclose: true,
      format: 'dd-mm-yyyy',
      language: 'es'
      /*getStartDate:*/

     })

  });

</script>

@endsection

@section('datagrafics')

@for ($is = 0; $is < $i; $is++)
  @if(($valorActivo[$is]==0) and ($valorCerrado[$is]==0))

  @else
    <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Task', 'Status de la Actividad'],
          ['Abierta', {{$valorActivo[$is]}}],
          ['Cerrada', {{$valorCerrado[$is]}}],
        ]);
        var options = {
          title: '{{$nombreTA[$is]}}',
          is3D: false,
          colors: ['#3c8dbc','#00a65a'],
          chartArea:{left:20,top:25,width:'50%',height:'75%'},
          titleTextStyle:{ color:'#404040', fontName: 'arial', fontSize: '12' ,bold:true},
          tooltip:{textStyle: {color: '#000000'}, showColorCode: true},
          width:480,
        };
        var chart = new google.visualization.PieChart(document.getElementById('piechart{{$is}}'));
        chart.draw(data, options);
      }
    </script>
  @endif
@endfor
@endsection
