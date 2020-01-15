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




<div class="row">
    <div class="col-md-12 col-xs-12">
    <div class="box">
        <div class="box-header with-border"> <h4 class="box-title">
            @foreach ($dashboards as $vg)
                {{$vg->dbnom}}
            @endforeach
        </h4> </div>

            <div class="box-body">

                    <div class="col-md-12 col-xs-12">
                            <div class="box box-solid with-border">
                                <form action="{{ route('dashboard.view') }}" autocomplete="off" method="POST">
                                    @csrf
                                <input type="hidden" name="iudDB" value="{{$iudDB}}">
                                <div class="col-md-3 col-xs-6">Desde
                                    <div class="input-group date">
                                    <input type="text" name="dateinicio" class="form-control" id="dateidash" value="{{$inicio}}">
                                        <div class="input-group-addon"> <span class="glyphicon glyphicon-calendar"></span> </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-xs-6">Hasta
                                    <div class="input-group date">
                                    <input type="text" name="datefin" class="form-control " id="datefdash" value="{{$fin}}">
                                        <div class="input-group-addon"> <span class="glyphicon glyphicon-calendar"></span> </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-xs-6">Status
                                    <select name="tipoactividaduid" class="select2" style="width: 90%;" >
                                        <option></option>
                                        <option @if($status=='A') {{"selected"}} @endif>Abierta</option>
                                        <option @if($status=='I') {{"selected"}} @endif>Cerrada </option>
                                    </select>
                                </div>
                                <div class="col-xs-2 col-md-2">
                                    <button type="submit" class="btn btn-sm btn-primary pull-right" value="search" ><i class="fa fa-search"></i>&nbsp;Buscar</button>
                                </div>
                            </form>
                            </div>
                    </div>

                @foreach ($valoresGraficos as $key => $vg)
                @if ($vg['idItem']!='')


                <div class="col-md-6 col-xs-12">
                    <div class="box box-solid with-border">
                        @if ($vg['itemTipo'] =='T')
                            <div class="box-header" >
                            </div>
                            <div class="chart-responsive" style="height: 300px">
                                    <div class="col-md-12 col-xs-12">
                                <div class="small-box bg-none" style="background-color:{{$vg['itemColor']}}; color:#ffffff;">
                                    <div class="inner">
                                        <h3>{{$vg['itemTotal']}}</h3>
                                        total de actividades
                                        <p><strong>{{$vg['TipAct']}}</strong></p>
                                        <div class="icon">
                                            <i class="{{$vg['itemIcono']}}"></i>
                                        </div>
                                    </div>
                                </div>
                                    </div>
                            </div>
                        @endif
                        @if ($vg['itemTipo'] =='G')
                            <div class="box-header">
                              <h4 >{{$vg['dbItem']}}</h4>
                              <small>{{ $vg['TipAct'] }}</small>
                            </div>
                            <div class="chart-responsive">
                                <div id="piechart-{{$vg['idItem']}}" ></div>
                            </div>
                            <!-- /.box-body -->
                        @endif
                    </div>
                          <!-- /.box -->
                </div>
                @endif
                @endforeach
            </div>
    </div>
</div>
</div><!-- end row -->
@endsection



@section('implemantations')
<script>

    $( function() {
        $('#dateidash').datepicker({
        autoclose: true,
        format: 'dd-mm-yyyy',
        language: 'es',
        })

        $('#datefdash').datepicker({
        autoclose: true,
        format: 'dd-mm-yyyy',
        language: 'es',
        })
    })
</script>

@endsection

@section('datagrafics')

    @foreach ($valoresGraficos as $key => $vg)
        @if ($vg['tipog']=="T")
        <script type="text/javascript">
            google.charts.load('current', {'packages':['corechart']});
            google.charts.setOnLoadCallback(drawChart);

            function drawChart() {
            var data = google.visualization.arrayToDataTable([
                ['Task', '{{$vg['idItem']}}'],
                @foreach ($vg['datos'] as $datos)
                ['{{$datos['item']}}',  {{$datos['valor']}} ],
                @endforeach

            ]);
            var options = {
                is3D: false,
                height:250,
                chartArea:{left:50, top:10, width:'87%',height:'75%'},
                titleTextStyle:{ color:'#404040', fontName: 'arial', fontSize: '12' ,bold:true},
                tooltip:{textStyle: {color: '#000000'}, showColorCode: true},
            };
            var chart = new google.visualization.PieChart(document.getElementById('piechart-{{$vg['idItem']}}'));
            chart.draw(data, options);
            }
        </script>
        @elseif($vg['tipog']=="B")
        <script type="text/javascript">
            google.charts.load('current', {'packages':['corechart','bar']});
            google.charts.setOnLoadCallback(drawChart);

            function drawChart() {
            var data = google.visualization.arrayToDataTable([
               ['Genero', 'sum: '],
                @foreach ($vg['datos'] as $datos)
                ['{{$datos['item']}}',  {{$datos['valor']}}],
                @endforeach
            ]);
            var view = new google.visualization.DataView(data);

            var options = {

                height:250,
                chartArea:{left:50, top:10, width:'87%',height:'75%'},
                bar: {groupWidth: '45%'},
                legend: { position: 'none' },
                titleTextStyle:{ color:'#404040', fontName: 'arial', fontSize: '12' ,bold:true},
                hAxis: { title: '{{ $vg['hAxis'] }}'},
                vAxis: { title: '{{ $vg['vAxis'] }}',baseline:0 },

            };
            var chart = new google.visualization.ColumnChart(document.getElementById('piechart-{{$vg['idItem']}}'));
            chart.draw(data, options);
            }
        </script>
        @elseif($vg['tipog']=="D")
        <script type="text/javascript">
            google.charts.load('current', {'packages':['corechart']});
            google.charts.setOnLoadCallback(drawChart);

            function drawChart() {
            var data = google.visualization.arrayToDataTable([
                ['Task', '{{$vg['idItem']}}'],
                @foreach ($vg['datos'] as $datos)
                ['{{$datos['item']}}',  {{$datos['valor']}} ],
                @endforeach

            ]);
            var options = {
                is3D: false,
                pieHole: 0.4,
                height:250,
                chartArea:{left:50, top:10, width:'87%',height:'75%'},
                titleTextStyle:{ color:'#404040', fontName: 'arial', fontSize: '12' ,bold:true},
                tooltip:{textStyle: {color: '#000000'}, showColorCode: true},
            };
            var chart = new google.visualization.PieChart(document.getElementById('piechart-{{$vg['idItem']}}'));
            chart.draw(data, options);
            }
        </script>
        @elseif($vg['tipog']=="L")
        <script type="text/javascript">
            google.charts.load('current', {'packages':['corechart']});
            google.charts.setOnLoadCallback(drawChart);

            function drawChart() {
            var data = google.visualization.arrayToDataTable([
                ['{{$vg['vAxis']}}', '{{$vg['xXx']}}'],
                @foreach ($vg['datos'] as $datos)
                ['{{$datos['item']}}',  {{$datos['valor']}} ],
                @endforeach

            ]);
            var options = {
                //curveType: 'function',
                axisTitlesPosition:'xXx',
                height:250,
                chartArea:{left:50, top:10, width:'87%',height:'75%'},
                titleTextStyle:{ color:'#404040', fontName: 'arial', fontSize: '12' ,bold:true},
                tooltip:{textStyle: {color: '#000000'}, showColorCode: true},
            };
            var chart = new google.visualization.LineChart(document.getElementById('piechart-{{$vg['idItem']}}'));
            chart.draw(data, options);
            }
        </script>
        @endif
    @endforeach
@endsection
