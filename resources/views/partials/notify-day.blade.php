<li class="dropdown notifications-menu">

        @foreach ($notificDay as $nv)
            @if ($nv['uidTipAct']=='0')
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                    <i class="fa fa-bell-o"></i>
                    <span class="label label-warning">{{$nv['Cant']}}</span>
                </a>
            @else
            <ul class="dropdown-menu">
                    @foreach ($notificDay as $nv2)
                        @if ($nv2['uidTipAct']=='0')
                            <li class="header">Tienes <strong>{{$nv2['Cant']}}<strong> actividades vencidas</li>
                        @endif
                    @endforeach

                  <li>

                    <ul class="menu">
                        @foreach ($notificDay as $nv3)
                            @if ($nv3['uidTipAct']!='0')
                                <li>
                                    <a href="#">
                                    <span class="label" style="background:{{$nv3['Color']}}">&nbsp;&nbsp;&nbsp;</span>
                                    {{$nv3['Cant']}} {{$nv3['TipAct']}}
                                    </a>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                  </li>

                  <li class="footer"><a href="#">ver todas</a></li>
                </ul>
            @endif
        @endforeach


    </li>
