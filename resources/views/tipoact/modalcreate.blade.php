<!-- INICIO MODAL ADD TIPO ACTIVIDAD -->
<div class="modal modal-default fade" id="add-tipoact" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title " id="exampleModalLabel">Tipos de Actividad</h4>
      </div>

      <div class="modal-body">

          <form action="{{ route('tipoact.store') }}" autocomplete="on" method="POST">
          	@csrf
            <input type="hidden" class="form-control" name="empresauid" value="{{Auth::user()->uidempresa }}" >
              <div class="form-group col-xs-12 col-sm-12 col-lg-12">
                <label for="tipoActividadNombre">Tipo de Actividad</label>
                <input type="text" class="form-control" id="tipoActividadNombre" name="tipoactnombre" required />
              </div>

            <div class="form-group col-xs-12 col-sm-12 col-lg-12">
              <label for="tipoActividadDescrip">Descripci&oacute;n</label>
              <textarea type="text" class="form-control " id="tipoActividadNombre" name="tipoactdescrip" onkeyup="textAreaAdjust(this)" style="overflow:hidden"></textarea>
            </div>

            <div class="form-group col-xs-6 col-sm-6 col-lg-6">
              <label for="Parent">Pertenece a</label>
              <select name="parent" class="form-control select2" style="width: 100%;" required />
                  <option value="0">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option>
                  @foreach($grupotipoactividads as $grupotipoactividad)
                    <option value="{{$grupotipoactividad->id}}">{{$grupotipoactividad->titulo}}</option>
                  @endforeach
              </select>
            </div>

            <div class="form-group col-xs-6 col-sm-6 col-lg-6">
              <label for="tipoActividadStatus" >Color</label>

                <select name="tipoactcolor" class="form-control" id="color" required>
                <option></option>
                @foreach ($colores as $color)
                    <option style="background-color:{{$color->color}}; color:#ffffff;" value="{{$color->color}}">{{$color->nombre}}</option>
                @endforeach
                </select>

            </div>

            <div class="form-group col-xs-12 col-sm-6 col-lg-6">
                    <label for="icons" class="col-sm-2"><strong>Icono</strong></label>
                    <div class="col-sm-3 icon-form">
                        <input type="hidden" name="icono" id="icono">
                        <div class="icon-form"><i id="icon" class="fa fa-plus"></i></div>
                        <a href="#" class="btn btn-info btn-xs pull-right" data-toggle="modal" data-target="#iconsModal"><i class="fa fa-plus"></i></a>
                    </div>
            </div>


      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        <button type="submit" class="btn btn-info" name="continuar" value="add" >Continuar</button>
      </div>
      </form>
    </div>

  </div>
</div>
<!-- FIN MODAL ADD TIPO ACTIVIDAD  -->
@include('partials.icons')
