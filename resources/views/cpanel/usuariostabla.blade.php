<!-- INICIO DE TABLA DE CONTENIDO -->
<div class="col-md-12 form-horizontal with-border">
    <div class="box-body">
      <h2 class="page-header">Usuarios
         <a class="bt btn-info btn-xs pull-right" data-target="#new-user" data-toggle="modal" style="color:#ffffff;"><i class="fa fa-plus"></i></a>
      </h2>
      <table class="table table-condensed table-bordered table-striped table-hover table-responsive" style="width:100%" id="tblUser">
        <thead>
            <tr>
                <th >Nombre</th>
                <th>Email</th>
                <th>Unidad Operativa</th>
                <th>Rol</th>
                <th style="width: 70px">Status</th>
                <th style="width: 50px"></th>
            </tr>
        </thead>
        <tbody>
@foreach ($usuarios as $usuario)
            <tr>
                <td>{{$usuario['name']}}</td>
                <td>{{$usuario['email']}} </td>
                <td>
                    @foreach ($uniopes as $uniope)
                        @if ($usuario['selectuniop'] == $uniope->unidadopuid)
                            {{$uniope->unidadopnombre}}
                        @endif
                    @endforeach
                </td>
                <td>
                    @foreach($roles as $rol)
                        @if ($rol->id==$usuario['idrol'])
                            {{$rol->name}}
                        @endif
                    @endforeach
                </td>
                <td>
                    @if ($usuario['status'] == "A")
                    <span class="label label-success">&nbsp;&nbsp;Activo&nbsp;&nbsp;</span>
                    @elseif ($usuario['status'] == "I")
                    <span class="label label-warning">Inactivo</span>
                    @endif
                </td>
                <td>
                    <a class="badge bg-aqua pull-right" data-target="#edit-usuario-{{$usuario['id']}}" data-toggle="modal"><i class="fa fa-edit"></i></a>
                </td>
            </tr>
@endforeach
        </tbody>
      </table>
    </div><!-- fin box-body  -->
</div>
<!-- FIN DE TABLA DE CONTENIDO -->


