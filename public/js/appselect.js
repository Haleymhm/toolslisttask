

$(function(){
    //alert('coñoooo');
    $('#list-uniope').on('change',onNewUnidadSelected);
    $('#misactividades').on('click',onMisActividad);
    $('#checkmisactividades').on('ifClicked',onMisActividad)
    $('#newActivity').on('click',newActivity);
    $('#newActivityTabla').on('click',newActivityTabla);
    $('#newActivityGrupo').on('click',newActivityGrupo);
    $('.select2').select2()
    $('[data-toggle="tooltip"]').tooltip()
    $('#idresp').on('change',cboParticipante);
    $('#id_tipo').on('change',cboListado);
    $('#cboStatus').on('change',optPeriodica);

});

function cboParticipante() {
    var parti=$(this).val();
    var limpiar="";
    if (parti==4){

        $('#divNomPart').show();
        $('#divEmailPart').show();
        $('#divUserInter').hide();
        $('#cboUserUid').val(limpiar).trigger('change');

    }else{
        $('#divNomPart').hide();
        $('#divEmailPart').hide();
        $('#divUserInter').show();
        $('#btnAddParticipante').show();
    }
}

function optPeriodica() {
    var optStatus=$(this).val();
    var limpiarS="";
    if(optStatus=="C"){
        $('#optionsRadios2').attr('disabled', true);
        console.info('Status Seleccionado: ' + optStatus);
    }else{
        $('#optionsRadios2').attr('disabled', false);
        console.info('Status Seleccionado: ' + optStatus);
    }

}
function cboListado() {
    var parti=$(this).val();
    if (parti==7){
        $('#divListados').show();
    }else{
        $('#divListados').hide();
    }
}


function validateMail(idMail){
	//Creamos un objeto
	object=document.getElementById(idMail);
	valueForm=object.value;
	// Patron para el correo
	var patron=/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,4})+$/;
	if(valueForm.search(patron)==0){
		//Mail correcto
        object.style.color="#000";
        $('#btnAddParticipante').show();
		return;
	}
	//Mail incorrecto
    object.style.color="#f00";
    $('#btnAddParticipante').hide();
}

function formatInteger(evt){
    var code = evt.which ? evt.which : evt.keyCode;
    if (code == 8) {
        //backspace
        return true;
    } else if (code >= 48 && code <= 57) {
        //is a number
        return true;
    } else {
        return false;
    }
}

function formatDecimal(evt, obj) {

    var charCode = (evt.which) ? evt.which : event.keyCode
    var value = obj.value;
    var dotcontains = value.indexOf(".") != -1;
    if (dotcontains)
        if (charCode == 46) return false;
    if (charCode == 46) return true;
    if (charCode > 31 && (charCode < 48 || charCode > 57))
        return false;
    return true;
}

function textAreaAdjust(o) {
    o.style.height = "1px";
    o.style.height = (25+o.scrollHeight)+"px";
  }

function resizeTextarea (id) {
    var a = document.getElementById(id);
    a.style.height = 'auto';
    a.style.height = a.scrollHeight+'px';
  }

function init() {
    var a = document.getElementsByTagName('textarea');
    for(var i=0,inb=a.length;i<inb;i++) {
       if(a[i].getAttribute('data-resizable')=='true')
        resizeTextarea(a[i].id);
    }
    $('#divNomPart').hide();
    $('#divEmailPart').hide();
    $('#divUserInter').show();
    $('#divListados').hide();
  }

  addEventListener('DOMContentLoaded', init);

function onNewUnidadSelected() {

    location.href='/calendario/'+ $(this).val()+'/view';

  };

function onMisActividad() {
    $editDetails=$('#formMisActividades');
    var formData = new FormData($editDetails[0]);
    var token = $('input[name=_token]').val();


    $.ajax({
        url: '/calendario/misactividades',
        headers: {'X-CSRF-TOKEN':token},
        method: 'post',
        contentType: false,
        processData: false,
        data: formData })

        .done(function (data) {
            if (data.success){

                console.info(document.referrer);
                //location.replace(document.referrer);
                location.reload();
               }
        }).fail(function () {
            //alert('Error al Actualizar el Check de Mis Actividades');
            console.info('Error al Actualizar el Check de Mis Actividades');
        });

};

function onChangeColorSelected() {
    var colorsel = $(this).val();
    estilo='background-color:'+colorsel+'; color:#ffffff';
    $('#color').attr('style', estilo);
    $('#color').val(colorsel ).trigger('change');
};

function newActivity() {
    var fh_ini = FechaHora30();
    var fh_fin = moment(fh_ini).add(1, 'hours');

    $('#dateinicioAC').val(moment(fh_ini).format('DD-MM-YYYY'));
    $('#timeinicioAC').val(moment(fh_ini).format('HH:mm')).trigger('change');
    $('#datefinAC').val(moment(fh_fin).format('DD-MM-YYYY'));
    $('#timefinAC').val(moment(fh_fin).format('HH:mm')).trigger('change');
    $('#topActGrup').val('').trigger('change');
};

function newActivityTabla() {
    var fh_ini = FechaHora30();
    var fh_fin = moment(fh_ini).add(1, 'hours');

    $('#dateinicioAC').val(moment(fh_ini).format('DD-MM-YYYY'));
    $('#timeinicioAC').val(moment(fh_ini).format('HH:mm')).trigger('change');
    $('#datefinAC').val(moment(fh_fin).format('DD-MM-YYYY'));
    $('#timefinAC').val(moment(fh_fin).format('HH:mm')).trigger('change');
    $('#topActGrup').val('').trigger('change');
};

function newActivityGrupo() {
    var fh_ini = FechaHora30();
    var fh_fin = moment(fh_ini).add(1, 'hours');

    $('#dateinicioAG').val(moment(fh_ini).format('DD-MM-YYYY'));
    $('#timeinicioAG').val(moment(fh_ini).format('HH:mm')).trigger('change');
    $('#datefinAG').val(moment(fh_fin).format('DD-MM-YYYY'));
    $('#timefinAG').val(moment(fh_fin).format('HH:mm')).trigger('change');
    $('#topActGrup').val('').trigger('change');
};



    /*SUBIR CAMBIAR FOTO DE PERFIL A TRAVES DE AJAX*/
    var $avatarImage, $avatarInput, $avatarForm;

    $avatarImage = $('#avatarImage');
    $avatarInput = $('#avatarInput');
    $avatarForm = $('#avatarForm');

    $avatarImage.on('click', function () {
        $avatarInput.click();
    });



    $avatarInput.on('change', function (){
        var formData = new FormData($('#avatarForm')[0]);
        var token = $('input[name=_token]').val();
        formData.append('photo', $avatarInput[0].files[0]);



        $.ajax({
                url: '/perfil/updatephoto',
                headers: {'X-CSRF-TOKEN':token},
                method: 'post',
                contentType: false,
                processData: false,
                data: formData })

                .done(function (data) {
                    if (data.success)
                        $avatarImage.attr('src', data.path);
                }).fail(function () {
                    console.info('Error al Subir Foto de perfil');
                });
        });




         /*SUBIR CAMBIAR FOTO DE PERFIL A TRAVES DE AJAX*/
    var $logoImage, $logoInput, $logoForm;

    $logoImage = $('#logoImage');
    $logoInput = $('#logoInput');
    $logoForm = $('#logoForm');

    $logoImage.on('click', function () {
        $logoInput.click();
    });



    $logoInput.on('change', function (){
        var formData = new FormData($('#logoForm')[0]);
        var token = $('input[name=_token]').val();
        formData.append('photo', $logoInput[0].files[0]);

        $.ajax({
                url: '/empresa/updatelogo',
                headers: {'X-CSRF-TOKEN':token},
                method: 'post',
                contentType: false,
                processData: false,
                data: formData })

                .done(function (data) {
                    if (data.success)
                        $logoImage.attr('src', data.path);
                }).fail(function () {
                    console.info('Error al Subir Logo de la Empresa');
                });
        });


/******/
/*SUBIR ARCHIVO A TRAVEZ DE AJAX*/


$('.file_upload').each(function() {


    //console.info($(this).attr('id_cont'));

    var $documentInput, $documentForm, $documentList;

    $documentInput = $('#documentInput' + $(this).attr('id_cont'));
    $documentForm = $('#documentForm' + $(this).attr('id_cont'));
    $documentList = $('#documentList' + $(this).attr('id_cont'));


   $(this).on('click', function () {
        //alert('test');
    console.info( $documentInput.attr('id'));

       $documentInput.click();
    });



    $documentInput.on('change', function (){


        $('#idActidadDoc').val()

        var formData = new FormData($documentForm[0]);
        var token = $('input[name=_token]').val();
        formData.append('valorfile', $documentInput[0].files[0]);

        $.ajax({
            url: '/actividad/addfiles',
            headers: {'X-CSRF-TOKEN':token},
            method: 'post',
            contentType: false,
            processData: false,
            data: formData })
            .done(function (data) {
                if (data.success)
                    console.info(data.path);
                    $documentInput.attr('src', data.path);

                    $($documentList).append(""+data.path+"");
            }).fail(function () {
                console.info('Error al Subir Achivos a la Actividades');
            });
    });
});

/* Horas */
$('select[name=timeinicio]').on('change', function() {
    if ($('select[name=timeinicio] option:selected').next().next().val())
        $sig = $('select[name=timeinicio] option:selected').next().next();
    else
        if ($('select[name=timeinicio] option:selected').next().val())

            $sig = $('select[name=timeinicio] option:selected').next();
        else
            $sig = $('select[name=timeinicio] option:selected');

    $('select[name=timefin]').val($sig.val()).trigger('change');
});


$('input[name=dateinicio]').on('change', function() {
    $('input[name=datefin]').val($(this).val());
});

$(".fa-fw").click(function(){
    //alert($(this).attr("class"));
    $('input[name=icono]').val($(this).attr("class"));
    $('#icon').attr("class",$(this).attr("class"));
    $('#iconsModal').modal('toggle');
  });




/** EDIT FORM GRUPO DE ACTIVIDADES**/
$('#btnActividadGrupo').on('click', function (){
    $actividadGrupoForm = $('#formActividadGrupo');
    var formData = new FormData($actividadGrupoForm[0]);
    var token = $('input[name=_token]').val();

    $.ajax({
        url: '/actividad/storeactgrupo',
        headers: {'X-CSRF-TOKEN':token},
        method: 'post',
        contentType: false,
        processData: false,
        data: formData })

        .done(function (data) {
            if (data.success){
            console.info(data.path);
            //$documentInput.attr('src', data.path);
            if(data.ncont==0){
                location.replace(document.referrer)
            }else{
                $('#divActividadGrupo').append(""+data.path+"");
                $('#actGrupoModal').modal('toggle');
            }
        }
        }).fail(function () {
            console.info('Error al AGREGAR Actividades a una Actividad');
        });
});
/** EDIT FORM GRUPO DE ACTIVIDADES*/

/** ADD PARTICIPANTES DE ACTIVIDADES**/
$('#btnAddParticipante').on('click', function (){
    $addParticipanteForm = $('#formAddParticipante');
    var formData = new FormData($addParticipanteForm[0]);
    var token = $('input[name=_token]').val();

    $.ajax({
        url: '/actividad/adduser',
        headers: {'X-CSRF-TOKEN':token},
        method: 'post',
        contentType: false,
        processData: false,
        data: formData })

        .done(function (data) {
            if (data.success){
            console.info(data.path);
            //$documentInput.attr('src', data.path);
            if(data.ncont==0){
                location.replace(document.referrer)
            }else{
                $('#tblParticipantes').append(""+data.path+"");
                $('.btn-delete').click(function() {
                    elimiarpart($(this).attr('id_cont'));
                });

                $('#add-invitado').modal('toggle');
            }
        }
        }).fail(function () {
            console.info('Error al AGREGRA participanes a las Actividades');
        });
});
/** ADD PARTICIPANTES DE ACTIVIDADES*/



function FechaHora30()
{
    var fh = new Date();
    var mm = fh.getMinutes();
    if (mm > 30)
    {
        fh = moment(fh).add(1, 'hours');
        fh = moment(fh).add((mm*-1), 'minutes');
    }
    else
        if (mm > 0 && mm < 30)
            fh = moment(fh).add((mm*-1), 'minutes');

    return fh;
}

function FechaHoraCal30()
{
    var fh = new Date();
    var mm = fh.getMinutes();
    if (mm > 30)
    {
        fh = moment(fh).add(1, 'hours');
        fh = moment(fh).add((mm*-1), 'minutes');
    }
    else
        if (mm > 0 && mm < 30)
            fh = moment(fh).add((mm*-1), 'minutes');

    return fh;
}



/** SELECT TIPO DE ACTIVIDADES **/
$('select[name="tipoactuid"]').on('change', function(){

    var contenidoId = $(this).val();

    $.ajax({
        url: '/dashboard/gettc/'+contenidoId,
        method: 'get',
        contentType: false,
        processData: false,
    /*data: contenidoId */ })

        .done(function (data) {
            if (data.success){
                console.info(data.item);
                $('select[name="agrupartipocontuid"]').empty();
                $('select[name="agrupartipocontuid"]').append(""+data.item+"");
            }else{
                $('select[name="agrupartipocontuid"]').empty();
            }

        }).fail(function () {
            console.info('Error DASHBOARD');
        });
});

$('select[name="tipoactuid"]').on('change', function(){

    var contenidoId = $(this).val();

    $.ajax({
        url: '/dashboard/gettc2/'+contenidoId,
        method: 'get',
        contentType: false,
        processData: false,
        }).done(function (data) {
            if (data.success){
                console.info(data.item);
                $('select[name="agrupartipocontuid2"]').empty();
                $('select[name="agrupartipocontuid2"]').append(" - ");
                $('select[name="agrupartipocontuid2"]').append(""+data.item+"");
            }else{
                $('select[name="agrupartipocontuid2"]').empty();
            }

        }).fail(function () {
            console.info('Error DASHBOARD');
        });
});
/** EDIT FORM GRUPO DE ACTIVIDADES*/


/** Crear Actividad y Editar **/
$('#btnSaveEdit').on('click', function (){

    $createActividad=$('#formCreateActividad');
    var formData = new FormData($createActividad[0]);
    var token = $('input[name=_token]').val();

    $.ajax({
        url: '/actividad/store',
        headers: {'X-CSRF-TOKEN':token},
        method: 'post',
        contentType: false,
        processData: false,
        data: formData })

        .done(function (data) {
            console.info(data.idact);
            if (data.success)
                location.href='/actividad/'+ data.idact +'/edit';
        }).fail(function () {
            console.info('Error en la Ejecucion de Crear la Actividad');

        });
});
/** Crear Actividad y Editar  **/

/** Crear Actividad y Volver **/
$('#btnSaveBack').on('click', function (){
    $createActividad=$('#formCreateActividad');
    var formData = new FormData($createActividad[0]);
    var token = $('input[name=_token]').val();

    $.ajax({
        url: '/actividad/store',
        headers: {'X-CSRF-TOKEN':token},
        method: 'post',
        contentType: false,
        processData: false,
        data: formData })

        .done(function (data) {
            console.info(data.idact);
            if (data.success)
                location.reload();
        }).fail(function () {
            console.info('Error en la Ejecucion de Crear la Actividad');

        });
});
/** Crear Actividad y Editar  **/

    $('.btn-delete').click(function() {
        elimiarpart($(this).attr('id_cont'));
    });

    function elimiarpart(btnValor) {
        $remParticipanteForm = $('#formRemoveParticipante'+btnValor);
        var formData = new FormData($remParticipanteForm[0]);
        var token = $('input[name=_token]').val();

        $.ajax({
            url: '/actividad/remuser',
            headers: {'X-CSRF-TOKEN':token},
            method: 'post',
            contentType: false,
            processData: false,
            data: formData })

            .done(function (data) {
                if (data.success){
                console.info(data.path);
                console.info('SE ELIMINO EL REGISTRO');
                $('#part_'+btnValor).remove();
                }

            }).fail(function () {
                console.info('Error al ELIMIAR Participantes de una Actividades');
            });
    }


    $("#selectallw").on("click", function() {
        $(".casew").prop("checked", this.checked);
      });
    $("#selectallm").on("click", function() {
        $(".casem").prop("checked", this.checked);
    });

      // if all checkbox are selected, check the selectall checkbox and viceversa
    $(".casew").on("click", function() {
        if ($(".casew").length == $(".casew:checked").length) {
            $("#selectallw").prop("checked", true);
        } else {
            $("#selectallw").prop("checked", false);
        }
    });

    $(".casem").on("click", function() {
        if ($(".casem").length == $(".casem:checked").length) {
            $("#selectallm").prop("checked", true);
        } else {
            $("#selectallm").prop("checked", false);
        }
    });

    /**************************************************************/

    $("#selectallwe").on("click", function() {
        $(".casewe").prop("checked", this.checked);
      });
    $("#selectallme").on("click", function() {
        $(".caseme").prop("checked", this.checked);
    });

    $(".casewe").on("click", function() {
        if ($(".casewe").length == $(".casewe:checked").length) {
            $("#selectallwe").prop("checked", true);
        } else {
            $("#selectallwe").prop("checked", false);
        }
    });



    $(".iradio").click(function () {
        var idradio=$(this).attr('name');
        var valot=$('input[name='+ idradio + ']:checked').val();;
        $('#'+idradio).val(valot);
       //alert('Click en un Input Radio: ' + valot);

    });
