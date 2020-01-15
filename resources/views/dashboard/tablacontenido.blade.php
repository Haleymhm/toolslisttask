<!-- INICIO DE TABLA DE CONTENIDO -->
<div class="col-md-12 form-horizontal with-border">
    <div class="box-body">
      <h2 class="page-header">Contenido de Datos
         <a class="bt btn-info btn-xs pull-right" data-target="#add-tipocontenido" data-toggle="modal" style="color:#ffffff;"><i class="fa fa-plus"></i></a>
      </h2>
      <table class="table table-condensed table-bordered table-striped table-hover">
          <tr>

            <th style="width: 25px; text-align:center;">Tipo</th>
            <th>Tipo de Actividad</th>
            <th class="nomobile">Agrupado por</th>
            <th class="nomobile">Operaci&oacute;n</th>
            <th class="nomobile">Desde</th>
            <th class="nomobile" style="width: 30px; text-align:center;">Gr&aacute;fico</th>
            <th style="width: 15px">Pos</th>
            <th style="width: 15px">Status</th>
            <th style="width: 10px"></th>
          </tr>
@foreach ($dbItem as $key=>$contenido)
          <tr>
            <td>
                @if ($contenido->itemtipo == "G")
                    <span class="label label-primary">&nbsp;Gr&aacute;fico&nbsp;</span>
                @elseif ($contenido->itemtipo == "T")
                    <span class="label label-info">Total</span>
                @endif
            </td>
            <td>
                @foreach ($tipoacts as $tipoact)
                  @if ($tipoact->uid == $contenido->tipoactuid)
                      {{$tipoact->titulo}}
                  @endif
                @endforeach
            </td>
            <td>
                @foreach ($tipoContenidos as $ct)
                    @if ($ct->id == $contenido->agrupartipocontuid)
                        {{$ct->etiqueta}}
                    @endif
                @endforeach
            </td>

            <td>
                @if ($contenido->itemoperacion == "AC") <span class="label label-default">Agrupar y contar</span>
                @elseif ($contenido->itemoperacion == "AS") <span class="label label-info">Agrupar y sumar valores</span>
                @elseif ($contenido->itemoperacion == "AP") <span class="label label-success">Agrupar y promediar</span>
                @endif
            </td>
            <td>
                @foreach ($tipoContenidos as $ct)
                    @if ($ct->id == $contenido->itemdesde)
                        {{$ct->etiqueta}}
                    @endif
                @endforeach
            </td>
            <td>
                @if ($contenido->itemgrafico == "T") <span class="label label-info"><i class="fa fa-pie-chart"></i>&nbsp;&nbsp;Torta&nbsp;&nbsp;&nbsp;</span>
                @elseif ($contenido->itemgrafico == "D") <span class="label label-info"><i class="fa fa-bullseye"></i>&nbsp;&nbsp;Dona&nbsp;&nbsp;&nbsp;</span>
                @elseif ($contenido->itemgrafico == "L") <span class="label label-info"><i class="fa fa-line-chart"></i>&nbsp;&nbsp;Linea&nbsp;&nbsp;</span>
                @elseif ($contenido->itemgrafico == "B") <span class="label label-info"><i class="fa fa-bar-chart"></i>&nbsp;&nbsp;Barras</span>
                @endif
            </td>
            <td style="text-align:center;">{{$contenido->itempos}}</td>
            <td>
              @if ($contenido->status == "A") <span class="label label-success">&nbsp;&nbsp;Activo&nbsp;&nbsp;</span>
              @elseif ($contenido->status == "I") <span class="label label-warning">Inactivo</span>
              @endif
            </td>
            <td><a class="badge bg-aqua pull-right" data-target="#edit-tipocontenido-{{$contenido->id}}" data-toggle="modal"><i class="fa fa-edit"></i></a></td>
          </tr>
@endforeach
      </table>
    </div><!-- fin box-body  -->
</div>
<!-- FIN DE TABLA DE CONTENIDO -->


