$(function(){
    $('#btnCreateEmpresa').on('click', editActividadPeriodica);
    $('#btnEditActividad').on('click', editActividades);
});





$('#btnCreateUser').on('click', function (){
    $addParticipanteForm = $('#formCreateUsuario');
    var formData = new FormData($addParticipanteForm[0]);
    var token = $('input[name=_token]').val();

    $.ajax({
        url: 'cpanel/empresa/createuser',
        headers: {'X-CSRF-TOKEN':token},
        method: 'post',
        contentType: false,
        processData: false,
        data: formData })

        .done(function (data) {
            if (data.success){
            console.info(data.path);

        }
        }).fail(function () {
            console.info('Error al AGREGRA USUARIO');
        });
});
