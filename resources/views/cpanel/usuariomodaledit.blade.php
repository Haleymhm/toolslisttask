@foreach ($usuarios as $usuario)
<div class="modal modal-default fade" id="edit-usuario-{{$usuario['id']}}"tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="box-title">Editar Usuario</h4>
                    </div><!-- action="/cpanel/empresa/createuser" -->
                <form action="/cpanel/empresa/edituser" autocomplete="on" method="POST" id="formCreateUsuario">
                    <div class="modal-body">
                        @csrf
                        <input type="hidden" name="empresauid" value="{{ $empresas->id}}">
                        <input type="hidden" name="usuariouid" value="{{$usuario['id']}}">

                        <div class="form-group col-xs-12 col-sm-12 col-lg-12">
                                <label for="tipoActividadNombre">Nombre</label>
                                <input type="text" class="form-control" id="tipoActividadNombre" name="nomcont" value="{{$usuario['name']}}" required />
                        </div>
                        <div class="form-group col-xs-12 col-sm-6 col-lg-6">
                                <label for="tipoActividadNombre">Email</label>
                                <input type="text" class="form-control" id="tipoActividadNombre" name="emailcont" value="{{$usuario['email']}}" required  />
                        </div>
                        <div class="form-group col-xs-12 col-sm-6 col-lg-6">
                                <label for="tipoActividadNombre">Password</label>
                                <input type="text" name="passw" class="form-control" />
                        </div>
                        <div class="form-group col-xs-12 col-sm-6 col-lg-6">
                            <label for="tipoActividadNombre">Status</label>

                            <select name="status" class="form-control select2" style="width:100%">
                                <option ></option>
                                <option value="A"  @if ($usuario['status'] == 'A' ) {{ "selected"}}  @endif>Activo</option>
                                <option value="I"  @if ($usuario['status'] == 'I' ) {{ "selected"}}  @endif>Inactivo</option>
                            </select>
                        </div>






                    </div>
                    <div class="form-group col-md-12 col-sd-12 col-xs-12">

                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs" role="tablist">
                            <li role="presentation" class="active"><a href="#roles-{{$usuario['id']}}" aria-controls="roles" role="tab" data-toggle="tab" style="text-color:white">Roles de Usuario</a></li>
                            <li role="presentation"><a href="#uniop-{{$usuario['id']}}" aria-controls="uniops" role="tab" data-toggle="tab" style="text-color:white">Unidad Operativa</a></li>
                            <li role="presentation"><a href="#tipact-{{$usuario['id']}}" aria-controls="tipacts" role="tab" data-toggle="tab" style="color:white">Tipos de Actividad</a></li>
                        </ul>

                        <!-- Tab panes -->
                        <div class="tab-content">
                            <div role="tabpanel" class="tab-pane active" id="roles-{{$usuario['id']}}">
                                <div class="form-group col-xs-12 col-sm-8 col-lg-8">
                                        @foreach($roles as $rol)
                                        @if (($rol->slug=="root") or ($rol->slug=="visitante"))

                                        @else
                                            <ul class="list-unstyled">
                                                <li> <label style="color:black;">
                                                    <input name="roles" type="radio" class="flat-red" value="{{$rol->id}}"

                                                                @if ($rol->id==$usuario['idrol'])
                                                                    {{ "checked" }}
                                                                @endif
                                                            >
                                                    &nbsp;{{$rol->name}}&nbsp;<em>&nbsp;&nbsp;{{$rol->description}} </em>
                                                    </label>
                                                </li>
                                            </ul>
                                        @endif
                                  @endforeach
                                </div>
                            </div>
                            <div role="tabpanel" class="tab-pane" id="uniop-{{$usuario['id']}}">
                                <div class="form-group col-xs-12 col-sm-8 col-lg-8">
                                    <ul class="list-unstyled">
                                        @foreach($uniopes as $uniop)
                                            <li> <label style="color:black;"><input name="uniops[]" type="checkbox" class="flat-red" value="{{$uniop->unidadopuid}}"
                                                @foreach($useruniops as $useruniop)
                                                    @if (($useruniop->unidadopuid == $uniop->unidadopuid) and ($useruniop->user_id == $usuario['id']))
                                                            {{ "checked" }}
                                                    @endif
                                                @endforeach>
                                                &nbsp;{{$uniop->unidadopnombre}}&nbsp;</label>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                            <div role="tabpanel" class="tab-pane" id="tipact-{{$usuario['id']}}">
                                <div class="form-group col-xs-12 col-sm-8 col-lg-8">
                                    <table class="table table-condensed table-hover table-responsive no-padding">
                                        <tr>
                                            <th >Selecione Tipo de Activiad</th>
                                            <th style="text-align:center;" >Acceso Web
                                                <input type="checkbox" id="selectallw"/></th>
                                            <th style="text-align:center;" >Acceso M&oacute;vil
                                                <input type="checkbox" id="selectallm"/></th>
                                        </tr>
                                        @foreach($tipoacts as $tipact)
                                        <tr>
                                            <td>{{$tipact->titulo}}&nbsp;</td>
                                            <td style="text-align:center;"><input name="tipacts[]" type="checkbox" class="casew" value="{{$tipact->id}}"
                                                @foreach($usertipoacts as $usertipoact)
                                                    @if (($usertipoact->tipoacts_id == $tipact->id) and ($usertipoact->user_id == $usuario['id']))
                                                            {{ "checked" }}
                                                    @endif
                                                @endforeach ></td>
                                            <td style="text-align:center;"><input name="tipactsmob[]" type="checkbox" class="casem" value="{{$tipact->id}}"
                                                @foreach($usertipoactsmobs as $usertipoactsmob)
                                                    @if (($usertipoactsmob->tipoacts_id == $tipact->id) and ($usertipoactsmob->user_id == $usuario['id']))
                                                            {{ "checked" }}
                                                    @endif
                                                @endforeach ></td>
                                        </tr>
                                        @endforeach
                                    </table>
                                </div>

                            </div>
                        </div>

                    </div>

                <div class="modal-footer">
                    <div class="col-xs-12 col-sd-12 col-md-12">
                        <button type="button" class="btn btn-lg btn-default pull-left" data-dismiss="modal"><i class="fa fa-reply" title="Volver"></i> Volver</button>
                        <button type="submit" class="btn btn-lg btn-info pull-right" ><i class="fa fa-save" ></i> Guardar</button>
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>
@endforeach
