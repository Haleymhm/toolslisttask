@if ($item['tipo'] == 'A')
<li class="{{ (request()->is('calendario/'.$item['uid'].'/utp')) ? 'active' : '' }}">
   <a href="{{ url('calendario',$item['uid']) }}/utp"><i class="fa fa-th-large"></i>{{ $item['titulo'] }}</a><!-- menu sin hijos  -->
</li>
@else
<li class="treeview">
        <a href="">
            <i class="fa fa-plus"></i> <span>{{ $item['titulo'] }}</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
            </span>
        </a>
        <ul class="treeview-menu {{ (request()->is('calendario/'.$id.'/utp')) ? 'active' : '' }}">
            @if ($item['tipo'] == 'G')
                @foreach ($item['submenu'] as $submenu)
                    @if ($submenu['tipo'] == 'A')
                        <li est="{{ (request()->is('calendario/'.$submenu['uid'].'/utp')) ? 'active' : '' }}" class="treeview-h {{ (request()->is('calendario/'.$submenu['uid'].'/utp')) ? 'active' : '' }}">
                        <a href="/calendario/{{$submenu['uid']}}/utp"> <i class="fa fa-check"></i>
                            {{ $submenu['titulo'] }}</a>
                        </li>
                    @else
                        @include('partials.menu-item', [ 'item' => $submenu ])
                    @endif
                @endforeach
            @endif       
        </ul>
</li>
@endif

