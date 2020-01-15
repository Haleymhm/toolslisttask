<!-- INICIO MODAL ASOCIAR ACTIVIDAD -->
<div class="modal fade" id="actGrupoModalTask"  role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                  <h4 class="modal-title" id="exampleModalLabel">Asociar Actividad +</h4>
                  <input type="hidden" name="idactasc" id="idactasc" value="{{$actividad->id}}" maxlength="40">
            </div>
            @csrf
            <div class="modal-body">
              <table id="example" class="table-hover table-condensed table-responsive" style="width:100%">
                <thead>
                    <tr>
                      <th style="width:30px">Fecha</th>
                      <th>Tipo</th>
                      <th>Descripcion</th>
                      <th>Lugar</th>
                      <th style="width:30px">Status</th>
                      <th style="width:20px">&nbsp;</th>
                    </tr>
                </thead>
              </table>
          </div>
        </div>
      </div>
</div>


<!-- FIN MODAL CREAR ACTIVIDAD -->
@section('cssDataTables')
      <link rel="stylesheet" href="{{ asset('bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css')}}">
@endsection

@section('jsDataTables')
<script src="{{ asset('bower_components/datatables.net/js/jquery.dataTables.min.js')}}"></script>
<script src="{{ asset('bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js')}}"></script>

<script>


$(document).ready(function() {

    $.ajaxSetup({
      headers: { "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr('content') }
  });



  $('#example').DataTable({

        "order": [[ 0, "desc" ]],
        "processing": true,
        "serverSide": true,
        "scrollY": "350px",
        "scrollCollapse": true,
        "paging": true,


        "language": {
            "url": "{{ asset('bower_components/datatables.net-bs/lang/Spanish.json') }}"
        },
        "ajax":{
            "url" :"{{ asset('actividad/searchtask') }}",
            "type": "GET"
            },
        //"dataSrc": data,
        "columns": [
            { "data": "actividadinicio", "name": "actividadinicio"},
            { "data": "titulo", "name":"titulo"},
            { "data": "actividaddescip", "name": "actividaddescip"},
            { "data": "actividadlugar", "name": "actividadlugar"},
            { "data": "actividadstatus", "name": "actividadstatus"},
            { "data": "add", "name":"add" }
        ]

    });

});



function AsociaAct(xAsoc_uid) {

    var token = $('input[name=_token]:first').val(); //$('meta[name="csrf-token"]').attr('content');
    var idAct = $('input[name=idactasc]').val();
    console.info('token: ' + token + ' padre_uid: '+ idAct + '  act_uid: ' + xAsoc_uid);

    var formData = new FormData();
        var token = $('input[name=_token]').val();
        formData.append('padre_uid', idAct);
        formData.append('act_uid', xAsoc_uid);
    $.ajax({
        url: '/actividad/asociartask',
        headers: {'X-CSRF-TOKEN':token},
        method: 'post',
        contentType: false,
        processData: false,
        data: formData  })
        .done(function (data) {
            $('#divActividadGrupo').append(""+data.path+"");
            $('#actGrupoModalTask').modal('toggle');
            console.info('La Actividad es Actualizo con EXITO');
            console.info(data.msg);

        })

}

</script>
@endsection
