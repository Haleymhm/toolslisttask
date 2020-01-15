<!-- INICIO DE TABLA DE CONTENIDO -->
<div class="col-md-12 form-horizontal with-border">
    <div class="box-body">
      <h2 class="page-header">Contenido de Datos
         <a class="bt btn-info btn-xs pull-right" data-target="#add-tipocontenido" data-toggle="modal" style="color:#ffffff;"><i class="fa fa-plus"></i></a>
      </h2>
      <table class="table table-condensed table-bordered table-striped table-hover">
          <tr>
            <th style="width: 10px"></th>
            <th >Titulo</th>
            <th>Tipo de Dato</th>
            <th style="width: 90px">Ver en Tabla</th>
            <th style="width: 70px">Posici&oacute;n</th>
            <th style="width: 70px">Obligatorio</th>
            <th style="width: 70px">Status</th>
            <th style="width: 50px"></th>
          </tr>
@foreach ($contenidos as $key=>$contenido)
          <tr>
            <td>{{$key + 1 }}</td>
            <td>{{$contenido->etiqueta}}</td>
            <td>
                @foreach ($contipos as $contipo)
                  @if ($contenido->contenidotipoid == $contipo->id)
                      {{$contipo->conttipodesc}}
                  @endif
                @endforeach
            </td>

            <td style="text-align:center;">
                @if ($contenido->mostrar == "SI")
                  <span class="label label-info">&nbsp;&nbsp;&nbsp;SI&nbsp;&nbsp;&nbsp;</span>
                @elseif ($contenido->mostrar == "NO")
                  <span class="label label-warning">&nbsp;&nbsp;NO&nbsp;&nbsp;</span>
                @endif
            </td>

            <td style="text-align:center;">{{$contenido->posicion}}</td>

            <td style="text-align:center;">
                @if ($contenido->obligatorio == "SI")
                    <span class="label label-info">&nbsp;&nbsp;&nbsp;SI&nbsp;&nbsp;&nbsp;</span>
                @elseif ($contenido->obligatorio == "NO")
                    <span class="label label-warning">&nbsp;&nbsp;NO&nbsp;&nbsp;</span>
                @endif
            </td>
            <td>
              @if ($contenido->status == "A")
                <span class="label label-success">&nbsp;&nbsp;Activo&nbsp;&nbsp;</span>
              @elseif ($contenido->status == "I")
                <span class="label label-warning">Inactivo</span>
              @endif
            </td>
            <td>
                <a class="badge bg-aqua pull-right" data-target="#edit-tipocontenido-{{$contenido->id}}" data-toggle="modal"><i class="fa fa-edit"></i></a>
            </td>
          </tr>
@endforeach
      </table>
    </div><!-- fin box-body  -->
</div>
<!-- FIN DE TABLA DE CONTENIDO -->


