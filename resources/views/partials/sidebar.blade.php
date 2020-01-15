<aside class="control-sidebar control-sidebar-dark">
    <!-- Create the tabs -->
    <ul class="nav nav-tabs nav-justified control-sidebar-tabs">
      <li class="active"><a href="#control-sidebar-home-tab" data-toggle="tab"><i class="fa fa-home"></i></a></li>
    </ul>
    <!-- Tab panes -->
    <div class="tab-content">
      <!-- Home tab content -->
      <div class="tab-pane active" id="control-sidebar-home-tab">
        <h3 class="control-sidebar-heading">Administraci&oacute;n</h3>

        <ul class="control-sidebar-menu">

            <li>
                <a href="{{route('empresa.edit',Auth::user()->id)}}">
                <h4 class="control-sidebar-subheading">
                    <span class="label label-danger"><i class="fa fa-building"></i></span> Empresa
                </h4>
                </a>
            </li>

        <li>
            <a href="{{ route('uniop.index') }}">
            <h4 class="control-sidebar-subheading">
                <span class="label label-danger"><i class="fa fa-shield"></i></span> Unidades Operativas
            </h4>
            </a>
        </li>


          <li>
            <a href="{{ route('cusuario.index') }}">
              <h4 class="control-sidebar-subheading">
                <span class="label label-danger"><i class="fa fa-user"></i></span> Usuarios
              </h4>
            </a>
          </li>


        <!-- <li>
            <a href="{{ route('roles.index') }}">
              <h4 class="control-sidebar-subheading">
                <span class="label label-danger"><i class="fa fa-key"></i></span> Roles
              </h4>
            </a>
          </li> -->


          <h3 class="control-sidebar-heading">&nbsp;&nbsp;&nbsp; Registro B&aacute;sico</h3>
          <li>
            <a href="{{ route('grupotipoact.index') }}">
              <h4 class="control-sidebar-subheading">
                <span class="label label-danger"><i class="fa fa-user"></i></span> Grupos de Tipos de Actividad
              </h4>
            </a>
          </li>
          <li>
            <a href="{{ route('tipoact.index') }}">
              <h4 class="control-sidebar-subheading">
                <span class="label label-danger"><i class="fa fa-shield"></i></span> Tipos de Actividad
              </h4>
            </a>
          </li>

          <li>
            <a href="{{ route('listado.index') }}">
              <h4 class="control-sidebar-subheading">
                <span class="label label-danger"><i class="fa fa-key"></i></span> Listados
              </h4>
            </a>
          </li>
            <li>
                <a href="{{ route('programas.index') }}">
                <h4 class="control-sidebar-subheading">
                    <span class="label label-danger"><i class="fa fa-calendar-check-o"></i></span> Programas
                </h4>
                </a>
            </li>
            <li>
                <a href="{{ route('dashboard.index') }}">
                <h4 class="control-sidebar-subheading">
                    <span class="label label-danger"><i class="fa fa-calendar-check-o"></i></span> Dashboard
                </h4>
                </a>
            </li>
        </ul>
      </div>
    </div>
  </aside>
