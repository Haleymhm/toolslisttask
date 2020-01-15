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

            <h3 class="box-title">{{$nombProg}}</h3>

                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-default btn-sm" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>

                </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <div class="row">
                    @foreach ($contTables as $key=>$programa)
                        @if($programa['id']!="")
                        <div class="col-md-4 col-sm-6 col-xs-12">
                            <div class="info-box">
                                <span class="info-box-icon" style="background-color:{{$programa['color']}};">
                                <input type="text" class="knob" value="{{$programa['avance']}}" data-width="100" data-height="100" data-fgColor="#ffffff" data-skin="tron" data-readonly="true">
                                </span>

                                <div class="info-box-content">
                                <span class="info-box-text"><strong>{{$programa['nombre']}}</strong></span>
                                <span class="info-box-text">{{$programa['complet']}} de {{$programa['totalact']}}</span>
                                <span class="info-box-text">{{$programa['fi']}} a {{$programa['ff']}}</span>
                                <span class="info-box-text">{{$programa['msgper']}}</span>

                                <!--<form class="none-border" action="" autocomplete="off" method="GET">

                                    <input type="hidden"  name="uidta" value="{{ $programa['id'] }}">
                                    <input type="hidden"  name="uidpr" value="{{$programaUID}}">
                                    <a href="/vprogramas/{{ $programa['id'] }}/utp/{{$programaUID}}/idpr" class="small-box-footer" > ver mas <i class="fa fa-arrow-circle-right"></i></a>
                                    <button class="btn btn-xs btn-primary none-border">ver mas <i class="fa fa-arrow-circle-right"></i></button>
                                </form> -->
                                <a class="btn btn-xs btn-primary none-border small-box-footer" href="{{ route('vprogramas.viewcontenido',[ $programa['id'], $programaUID ] ) }}">ver mas <i class="fa fa-arrow-circle-right"></i></a>

                                </div>

                            </div>
                            <!-- /.info-box -->
                            </div>
                        <!-- ./col -->
                        @endif
                    @endforeach
                </div>
            </div>
        </div>



@endsection


@section('datagrafics')
    <script src="{{ asset('bower_components/jquery-knob/js/jquery.knob.js')}}"></script>
    <script>
    $(function () {
/* jQueryKnob */

    $(".knob").knob({
      /*change : function (value) {
       //console.log("change : " + value);
       },
       release : function (value) {
       console.log("release : " + value);
       },
       cancel : function () {
       console.log("cancel : " + this.value);
       },*/
      draw: function () {

        // "tron" case
        if (this.$.data('skin') == 'tron') {

          var a = this.angle(this.cv)  // Angle
              , sa = this.startAngle          // Previous start angle
              , sat = this.startAngle         // Start angle
              , ea                            // Previous end angle
              , eat = sat + a                 // End angle
              , r = true;

          this.g.lineWidth = this.lineWidth;

          this.o.cursor
          && (sat = eat - 0.3)
          && (eat = eat + 0.3);

          if (this.o.displayPrevious) {
            ea = this.startAngle + this.angle(this.value);
            this.o.cursor
            && (sa = ea - 0.3)
            && (ea = ea + 0.3);
            this.g.beginPath();
            this.g.strokeStyle = this.previousColor;
            this.g.arc(this.xy, this.xy, this.radius - this.lineWidth, sa, ea, false);
            this.g.stroke();
          }

          this.g.beginPath();
          this.g.strokeStyle = r ? this.o.fgColor : this.fgColor;
          this.g.arc(this.xy, this.xy, this.radius - this.lineWidth, sat, eat, false);
          this.g.stroke();

          this.g.lineWidth = 2;
          this.g.beginPath();
          this.g.strokeStyle = this.o.fgColor;
          this.g.arc(this.xy, this.xy, this.radius - this.lineWidth + 1 + this.lineWidth * 2 / 3, 0, 2 * Math.PI, false);
          this.g.stroke();

          return false;
        }
      }
    });
});

    </script>
@endsection
