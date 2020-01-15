
<!-- CIERRA COMBO UNIOP -->

<li class="treeview">
    @foreach ($menus as $key => $item)
   
        @include('partials.menu-item', ['item' => $item])

    @endforeach

@can('actividad.index')

<label>&nbsp;&nbsp;&nbsp;</label>
<form method="post" id="formMisActividades">
@csrf
&nbsp;&nbsp;&nbsp;&nbsp;<label style="color: #424949;" id="misactividades" @if( Auth::user()->solomisact=="S")
        {{"hidden"}}
    @endif> 
    <input id="checkmisactividades" type="checkbox" name="misact" class="flat-red"
        @if( Auth::user()->misact=="S")
            {{"checked"}}
        @endif
        @if( Auth::user()->solomisact=="S")
            {{"disabled"}}
        @endif
     >&nbsp;&nbsp;<strong> Mis Actividades </strong></label>
</form>
@endcan
