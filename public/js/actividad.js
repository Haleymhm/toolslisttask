$(function(){

    $('#btnActividadPeriodica').on('click', editActividadPeriodica);
    $('#btnEditActividad').on('click', editActividades);
});


/** EDIT FORM ACTIVIDAD**/
function editActividades(){
    $editActividad=$('#formEditActividad');
    var formData = new FormData($editActividad[0]);
    var token = $('input[name=_token]').val();

    $.ajax({
        url: '/actividad/updateActividad',
        headers: {'X-CSRF-TOKEN':token},
        method: 'post',
        contentType: false,
        processData: false,
        data: formData })

        .done(function (data) {
            console.info('La Actividad es Actualizo con EXITO');
            console.info(data.msg);
            if (data.success){
                editDetailsActividad();
                $.alert('<strong>Mensaje del Sistema</strong> <br />Los Datos se han ACTUALIZADO exitosamente',{
                    closeTime: 3500,
                    type: 'info',// danger, success, warning or info
                    position: ['top-right', [60, 0]], // top-left,top-right,bottom-left,bottom-right,center
                    speed: 'fast',
                });


            }

        }).fail(function () {

            console.info('Error en la Ejecucion de Editar la Actividad: ');

        });
};
/** EDIT FORM DETALLES DE ACTIVIDAD**/

/** EDIT FORM DETALLES CABECERA DE LA ACTIVIDAD**/
function editDetailsActividad(){
    $editDetails=$('#formEditDetail');
    var formData = new FormData($editDetails[0]);
    var token = $('input[name=_token]').val();

    $.ajax({
        url: '/actividad/editdetails',
        headers: {'X-CSRF-TOKEN':token},
        method: 'post',
        contentType: false,
        processData: false,
        data: formData })

        .done(function (data) {
            if(data.headtask=='FALLO'){
                $.alert('<strong>Mensaje del Sistema</strong> <br />La actividad no puede ser cerrada<br /> hasta haber completado todos los datos obligatorios',{
                    closeTime: 5000,
                    autoClose: false,
                    type: 'warning',// danger, success, warning or info
                    position: ['top-right', [0, 0]], // top-left,top-right,bottom-left,bottom-right,center
                    speed: 'fast',
                });
            }else{
                $('#status_span').attr("class", data.statusclass);
                $('#status_span').text(data.statustext);
                $('#descripcion').empty();
                $('#descripcion').append("" + data.descrip + "");
                //location.href='/calendario/'+ data.uidta +'/utp';
                console.info(document.referrer);
                location.replace(document.referrer);
            }

            console.info('mensaje: '+ data.headtask + data.statustext + data.descrip + data.indexbusq);

        }).fail(function () {
            /*$.alert('<strong>Mensaje del Sistema</strong> <br />UPS, ha ocurrido un error Editar el Detalle de la Actividad...',{
                closeTime: 3500,
                type: 'danger',// danger, success, warning or info
                position: ['top-right', [60, 0]], // top-left,top-right,bottom-left,bottom-right,center
                speed: 'fast',
            });*/
            console.info('Error al Actualizar la informacion de la Actividad DETALLE DE ACTIVIDAD');
        });
}
/** FIN EDIT FORM DETALLES CABECERA DE LA ACTIVIDAD**/

/** ACTIVIDAD PERIODICA **/
function editActividadPeriodica() {
    $editDetails = $('#formEditDetail');
    var formData = new FormData($editDetails[0]);
    var token = $('input[name=_token]').val();
    $.ajax({
        url: '/actividad/editactividadper',
        headers: { 'X-CSRF-TOKEN': token },
        method: 'post',
        contentType: false,
        processData: false,
        data: formData
    })
        .done(function (data) {

            if (data.success){
                $.alert('<strong>Mensaje del Sistema</strong> <br />Los Datos se han ACTUALIZADO exitosamente',{
                    closeTime: 3500,
                    type: 'info',// danger, success, warning or info
                    position: ['top-right', [60, 0]], // top-left,top-right,bottom-left,bottom-right,center
                    speed: 'fast',
                });
                location.reload();
            }
            console.info('La Actividad es Actualizo con EXITO');
        }).fail(function () {
            $.alert('<strong>Mensaje del Sistema</strong> <br />UPS, ha ocurrido un error...',{
                closeTime: 3500,
                type: 'danger',// danger, success, warning or info
                position: ['top-right', [60, 0]], // top-left,top-right,bottom-left,bottom-right,center
                speed: 'fast',
            });
            console.info('Error en la Ejecucion de Editar la Actividad Periodica');

        });
}
/** FIN ACTIVIDAD PERIODICA **/

