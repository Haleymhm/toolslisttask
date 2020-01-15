<!-- INICIO DE TABLA DE CONTENIDO -->
<div class="col-md-12 form-horizontal with-border">
    <div class="box-body">
      <h2 class="page-header">Unidades Operativas
         <a class="bt btn-info btn-xs pull-right" data-target="#new-uniop" data-toggle="modal" style="color:#ffffff;"><i class="fa fa-plus"></i></a>
      </h2>
      <table class="table table-condensed table-bordered table-striped table-hover" style="width:100%" id="tblUniOp">
        <thead>
            <tr>
                <th >Nombre</th>
                <th style="width: 70px">Status</th>
                <th style="width: 50px"></th>
            </tr>
        </thead>
        <tbody>
@foreach ($uniopes as $uniope)
        <tr>
            <td>{{$uniope->unidadopnombre}}</td>
            <td>
              @if ($uniope->unidadopstatus == "A")
                <span class="label label-success">&nbsp;&nbsp;Activo&nbsp;&nbsp;</span>
              @elseif ($uniope->unidadopstatus == "I")
                <span class="label label-warning">Inactivo</span>
              @endif
            </td>
            <td>
                <a class="badge bg-aqua pull-right" data-target="#edit-uniop-{{$uniope->id}}" data-toggle="modal"><i class="fa fa-edit"></i></a>
            </td>
        </tr>
@endforeach
        </tbody>
      </table>
    </div><!-- fin box-body  -->
</div>
<!-- FIN DE TABLA DE CONTENIDO -->


