@extends('layouts.app')
@section('notificaciones')
    @include('partials.notify-vencida')
    @include('partials.notify-day')
@endsection
@section('cbouniope')
  @include('partials.cbouniope')
  @include('partials.menuta')
@endsection
@section('content')
<div class="col-xs-12" style=" padding-top:10px">
<div class="box box-solid box-default">
        <div class="box-header with-border ">
          <h3 class="box-title">Detalles del Usuario</h3>
        </div>
        <div class="box-body">
          <div class="form-group">
              <label for="actividadtitulo" class="col-sm-2 control-label">Nombre:</label>
              <div class="col-sm-10">
              <input type="text" class="form-control" value="{{$usuarios->name }}" readonly />
            </div>
          </div>
          <br /><br />
          <div class="form-group">
              <label for="actividadtitulo" class="col-sm-2 control-label">Email:</label>
              <div class="col-sm-10">
            <textarea class="form-control" name="actividaddescip" readonly />{{$usuarios->email }} </textarea>
          </div>
          </div>
          <br /><br /><br />
          <div class="form-group">
              <label for="actividadtitulo" class="col-sm-2 control-label">Cargo:</label>

              <div class="col-sm-10">
                <input type="text" class="form-control" value="{{$usuarios->cargo}}" readonly />
              </div>
          <br /><br />


        </div>
        <!-- /.box-body -->
        <div class="box-footer">
          <div class="col-sm-3">
            <form action="{{ route('cusuario.destroy',$usuarios->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                  <button type="submit"  class="btn btn-danger" onclick="return confirm('Desea ELIMINAR este Actividad??')">
                    <i class="fa fa-trash"></i> Eliminar </button>
                </form>
            </div>
          <div class="col-sm-3" align="center"><a href="/cusuario" class="btn btn-primary"><i class="fa fa-mail-reply"></i>  Volver</a></div>
          <div class="col-sm-3"><a href="/cusuario" class="btn btn-info pull-right"><i class="fa fa-edit"></i> Asignar Rol</a></div>
          <div class="col-sm-3"><a href="{{ route('cusuario.edit',$usuarios->id) }}" class="btn btn-info pull-right"><i class="fa fa-edit"></i> Asignar Permisos</a></div>


        </div>
        <!-- /.box-footer-->
      </div>
      <!-- /.box -->
</div>
@endsection
