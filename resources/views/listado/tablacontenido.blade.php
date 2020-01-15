<!-- INICIO DE TABLA DE CONTENIDO -->
<div class="col-md-12 form-horizontal with-border">
    <div class="box-body">
      <h2 class="page-header">Elementos del Listado
         <a class="bt btn-default  btn-xs pull-right" data-target="#add-elemento" data-toggle="modal"><i class="fa fa-plus"></i></a>
      </h2>
      <table class="table table-condensed table-bordered table-striped table-hover">
          <tr>

            <th>&nbsp;&nbsp;&nbsp; Nombre</th>
            <th>Descripci&oacute;n</th>
            <th style="width: 25px">Posici&oacute;n</th>
            <th style="width: 25px">Status</th>
            <th style="width: 10px"></th>
          </tr>
@foreach ($elementos as $key=>$elemento)
          <tr>
            <td>&nbsp;&nbsp;&nbsp;{{$elemento->elemnombre}}</td>
            <td>{{$elemento->elemdescip}}</td>
            <td style="text-align:center;">{{$elemento->elempos}}</td>
            <td>
              @if ($elemento->status == "A")
                <span class="label label-primary">&nbsp;&nbsp;Activo&nbsp;&nbsp;</span>
              @elseif ($elemento->status == "I")
                <span class="label label-warning">Inactivo</span>
              @endif
            </td>
            <td><a class="badge bg-aqua pull-right" data-target="#edit-tipocontenido-{{$elemento->id}}" data-toggle="modal"><i class="fa fa-edit"></i></a></td>
          </tr>
@endforeach
      </table>
    </div><!-- fin box-body  -->
</div>
<!-- FIN DE TABLA DE CONTENIDO -->


