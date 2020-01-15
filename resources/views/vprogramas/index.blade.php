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
            <i class="glyphicon glyphicon-time"></i>
            <h3 class="box-title">Programas de Trabajo</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-default btn-sm" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                </div>
        </div>
            <!-- /.box-header -->
        <div class="box-body">
                <!-- Small boxes (Stat box) -->
            <div class="row">
                    @foreach ($contTables as $key=>$programa)
                        @if($programa['id']!="")
                        <!-- ./col -->
                            <div class="col-lg-4 col-xs-6">
                            <!-- small box -->
                            <div class="small-box bg-none" style="background-color:{{$programa['color']}}; color:#ffffff;">
                                <div class="inner">
                                <h3>{{$programa['avance']}}%</h3>
                                {{ $programa['complet'] }} de {{ $programa['tact'] }}
                                <p><strong>{{ $programa['nombre'] }}</strong></p>
                                {{ $programa['fi'] }} a {{ $programa['ff'] }}
                                </div>
                                <div class="icon">
                                <i class="{{$programa['icono']}}"></i>
                                </div>
                                <a href="/vprogramas/{{ $programa['id'] }}" class="small-box-footer">
                                ver mas <i class="fa fa-arrow-circle-right"></i>
                                </a>
                            </div>
                            </div>
                            <!-- ./col -->
                        @endif
                    @endforeach
                    <!-- ./col -->
            </div><!-- /.row -->
        </div>

    </div>





    <div class="box box-solid box-default collapsed-box">
        <div class="box-header">
            <i class="glyphicon glyphicon-time"></i>
            <h3 class="box-title">Programas de Trabajo No Vigentes</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-default btn-sm" data-widget="collapse"><i class="fa fa-plus"></i>
                    </button>
                </div>
        </div>
            <!-- /.box-header -->
        <div class="box-body">

                <div class="row">
                        @foreach ($contTablesI as $key=>$programa)
                            @if($programa['id']!="")
                            <!-- ./col -->
                                <div class="col-lg-4 col-xs-6">
                                <!-- small box -->
                                <div class="small-box bg-none" style="background-color:{{$programa['color']}}; color:#ffffff;">
                                    <div class="inner">
                                    <h3>{{$programa['avance']}}%</h3>
                                    {{ $programa['complet'] }} de {{ $programa['tact'] }}
                                    <p><strong>{{ $programa['nombre'] }}</strong></p>
                                    {{ $programa['fi'] }} a {{ $programa['ff'] }}
                                    </div>
                                    <div class="icon">
                                    <i class="{{$programa['icono']}}"></i>
                                    </div>
                                    <a href="/vprogramas/{{ $programa['id'] }}" class="small-box-footer">
                                    ver mas <i class="fa fa-arrow-circle-right"></i>
                                    </a>
                                </div>
                                </div>
                                <!-- ./col -->
                            @endif
                        @endforeach
                        <!-- ./col -->
                </div><!-- /.row -->
        </div>
    </div>

@endsection
