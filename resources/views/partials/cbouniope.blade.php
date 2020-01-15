
<!-- ABRE COMBO UNIOP -->
<div class="cboUniopMenu">
<li >
<select id="list-uniope" name="uniope_id" class="form-control">

	 @if(($valor=='root') or ($valor=='admin'))
		@foreach($uniops as $uniop)
			<option value="{{$uniop->unidadopuid }}"  @if ($uniop->unidadopuid == $useruniopsId) {{ "selected" }} @endif>
			&nbsp;&nbsp;&nbsp;	{{$uniop->unidadopnombre}}</option>
		@endforeach

	 @else
	 	@foreach($uniops as $uniop)
			@foreach($useruniops as $useruniop)
				@if ($uniop->unidadopuid == $useruniop->unidadopuid)
					<option value="{{$uniop->unidadopuid }}" @if ($uniop->unidadopuid == $useruniopsId) {{ "selected" }} @endif>&nbsp;&nbsp;&nbsp;{{$uniop->unidadopnombre}}</option>
				@endif
			@endforeach
		@endforeach
	 @endif

</select>

</li>
</div>
<!-- CIERRA COMBO UNIOP -->

<label>&nbsp;&nbsp;&nbsp;</label>
<a href="{{route('calendario.index')}}" title="" class="label bg-light-blue" data-toggle="tooltip" data-placement="bottom" title="Calendario">&nbsp;&nbsp;<i class="glyphicon glyphicon-calendar"></i>&nbsp;&nbsp;</a>
<a href="{{route('graficos.index')}}" title="" class="label bg-yellow" data-toggle="tooltip" data-placement="bottom" title="Estadisticas">&nbsp;&nbsp;<i class="glyphicon glyphicon-stats"></i>&nbsp;&nbsp;</a>
<a href="{{route('tabla.index')}}" title="" class="label bg-green" data-toggle="tooltip" data-placement="bottom" title="Tablas">&nbsp;&nbsp;<i class="glyphicon glyphicon-list"></i>&nbsp;&nbsp;</a>
<a href="{{route('vprogramas.index')}}" title="" class="label bg-purple" data-toggle="tooltip" data-placement="bottom" title="Programas">&nbsp;&nbsp;<i class="glyphicon glyphicon-time"></i>&nbsp;&nbsp;</a>
<!-- <a href="{{route('documentos.index')}}" title="" class="label bg-aqua" data-toggle="tooltip" data-placement="bottom" title="Docuemntos">&nbsp;&nbsp;<i class="glyphicon glyphicon-folder-open"></i>&nbsp;&nbsp;</a>  -->
@can('actividad.create')

    <!-- boton Nueva Actividad  -->
        <li>
            <a id="newActivity" data-target="#fullCalModal" data-toggle="modal">
                <i class="fa fa-plus-square"></i> <span>AGREGAR </span>
            </a>
        </li>
    <!--FIN boton Nueva Actividad -->
@endcan
<li class="treeview">
    <a href="#">
        <i class="fa fa-pie-chart"></i>
        <span>Dashboard</span>
        <span class="pull-right-container">
        <i class="fa fa-angle-left pull-right"></i>
        </span>
    </a>
    <ul class="treeview-menu">
        @foreach($menuDashboard as $mdb)
            <li est="{{ (request()->is('dashboard/'.$mdb->id.'/view')) ? 'active' : '' }}" class="treeview-h {{ (request()->is('dashboard/'.$mdb->id.'/view')) ? 'active' : '' }}">
               <!-- <a href="/dashboard/{{$mdb->id}}/view"> <i class="fa fa-check"></i>{{ $mdb->dbnom }}</a> -->
                <form action="/dashboard/view" method="post">
                    <input type="hidden" name="iudDB" value="{{$mdb->id}}">
                    @csrf
                    <button type="submit" class="btn btn-default" style="padding: 0px 0px 0px 15px;"><i class="fa fa-check"></i><small > {{ $mdb->dbnom }}</small></button>
                </form>
            </li>
        @endforeach
    </ul>
</li>




    @section ('menu_implementations')

    <script>
        $('[est="active"]').parents('ul').css( 'display', 'block' ).parent('li').addClass('menu-open');
    </script>

    @endsection
