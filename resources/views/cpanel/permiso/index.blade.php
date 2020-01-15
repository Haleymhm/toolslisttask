@extends('layouts.app')

@section('cbouniope')
  @include('partials.cbouniope')
  @include('partials.menuta') 
@endsection

@section('content')
      
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Permisos del Sistema</h3>
              <div class="box-tools">
                @can('permiso.create')
              	<a href="{{ route('permiso.create') }}"  class="btn btn-info pull-right">Nuevo &nbsp;&nbsp;&nbsp;<i class="fa fa-plus-square"></i></a>  
                @endcan
                <div class="input-group input-group-sm" style="width: 300px;">
                  <input type="text" name="table_search" class="form-control pull-right" placeholder="Search">

                  <div class="input-group-btn">
                    <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                  </div>
                </div>
                
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body table-responsive no-padding">
              <table class="table table-hover">
                <tr>
                  <th width="15px"></th>
                  <th>Nombre del Permiso</th>
                  <th>Descripci&oacute;n</th>
                  <th>Ruta</th>
                  <th class="pull-right">Accion</th>
                </tr>
                @foreach ($permisos as $key=>$permiso)
                
                <tr>
                  <td>{{$key+1}}</td>
                  <td>{{ $permiso->name }}</td>
                  <td>{{ $permiso->description }}</td>
                  <td>{{ $permiso->slug }}<td>
                    @can('tipoact.destroy')
                    <form action="{{ route('permiso.destroy',$permiso->id) }}" method="POST">
                      @csrf
                      @method('DELETE')
                    <button type="submit"  class="btn btn-danger btn-xs pull-right" onclick="return confirm('Desea ELIMINAR este Permiso??')">
                        <i class="fa fa-trash"></i> </button>
                    </form>
                    @endcan
                    @can('permiso.edit')
                    <a href="{{ route('permiso.edit',$permiso->id) }}" class="badge bg-aqua pull-right"><i class="fa fa-edit"></i></a>
                    @endcan

                    
                  </td>
                </tr>
				@endforeach
              </table>
            </div>
           
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>

@endsection