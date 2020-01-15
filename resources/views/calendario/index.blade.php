@extends('layouts.app')

@section('libraries')

<link rel="stylesheet" href="{{ asset('fullcalendar/fullcalendar.css')}}"  />
<link rel="stylesheet" href="{{ asset('fullcalendar/fullcalendar.print.min.css')}}"  media="print" />
<style>

    html, body {
      /*overflow: hidden;  don't do scrollbars
      font-family: "Lucida Grande",Helvetica,Arial,Verdana,sans-serif;*/
      font-size: 14px;
    }

    #calendar-container {
      position: relative;
      /*top: 50px;
      left: 230px;*/
      right: 0;
      bottom: 0;
      height: 100%;
    }

    .demo-topbar { /* will be stripped out */
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      height: 40px;
    }

    .demo-topbar + #calendar-container { /* will be stripped out */
      top: 40px;
    }

    .fc-header-toolbar {
      /*
      the calendar will be butting up against the edges,
      but let's scoot in the header's buttons
      */
      padding-top: 1em;
      padding-left: 1em;
      padding-right: 1em;
    }

  </style>
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


<div class="row justify-content-center">
    <div class="col-xs-12">
        <div id='calendar-container'>
                <div class="box">
                    {!! $calendar->calendar() !!}
                </div>
        </div>
    </div>
</div>
@endsection



@section('implemantations')
<script src="{{ asset('fullcalendar/lib/moment.min.js') }}"></script>

<script src="{{ asset('fullcalendar/fullcalendar.js') }}"></script>
<script src="{{ asset('fullcalendar/locale-all.js') }}"></script>
<script>
function onTablaCalendario() {

    location.href='/tabla/{{ $id }}/edit';
  };
</script>

{!! $calendar->script() !!}


@endsection
