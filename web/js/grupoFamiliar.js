/*
 * Funcionalidad para la asignaciòn de responsables al grupo familiar-
 * 
 * Lo primeromeabre el modal con el grid para busqueda y asigancion;
 * la segunda es la funcion encarga de asignaciòn
 */
$("#btn-asignar-responsable").click(function(){     
    xhref = $(this).attr("value");
    $.ajax({
        url    : xhref,                 
        dataType: "json",
        success: function (response){            
            if(response.error==='0'){
                $('#modalAsignacionResponsable').modal('show').find('#modalContent').html(response.vista);   
            }else{
                new PNotify({
                    title: 'Error',
                    text: response.message,
                    icon: 'glyphicon glyphicon-envelope',
                    type: 'error'
                });
            }
        },
        error: function(error){
                new PNotify({
                    title: 'Error',
                    text: 'Error al querer asignar responsables. Intente nuevamente y en caso de persistir el error comuniquese con su administrador',
                    icon: 'glyphicon glyphicon-envelope',
                    type: 'error'
                });
        }
    });
    
});

function asignarResponsable(btn){
    xhref = $(btn).attr("value");     
    responsbale = $('input:radio[name=radioButtonSelection]:checked').val(); 
    $("body").loading({message: 'AGUARDE... procesando.'});
    $.ajax({
        url    : xhref,
        type   : "get",
        data   : {"tipores": $('#tipores').val(), "familia": $('#familia').val(), "idresponsable": responsbale},
        dataType: "json",
        success: function (response) {
            if(response.error == 0){                                   
                if(response.carga == 1){  
                    $("#modalAsignacionResponsable").modal("toggle"); 
                    grillaajax = '#pjax-responsable';
                    $.pjax.reload({container:grillaajax, timeout:false});                    
                }
                else
                if (response.carga==0){                    
                    $("#modalAsignacionResponsable").modal("show").find("#modalContent").html("");
                    $("#modalAsignacionResponsable").modal("show").find("#modalContent").html(response.vista);
                }
            }
            else{    
                new PNotify({
                    title: 'ERROR',
                    text: response.mensaje,
                    icon: 'glyphicon glyphicon-envelope',
                    type: 'error'
                });
                $("#modalAsignacionResponsable").modal("toggle");                                           
            }
        },
        error  : function () {
            console.log("internal server error");
        }
    }).done($("body").loading('stop'));
}



function cargarResponsable(btn){  
    xhref = jQuery(btn).attr("value");
    $.ajax({
        url    : xhref,                 
        dataType: "json",
        success: function (response){              
            if(response.error==='0'){
                $('#modalAsignacionResponsable').modal('show').find('#modalContent').html(response.vista);   
            }else{
                new PNotify({
                    title: 'Error',
                    text: response.message,
                    icon: 'glyphicon glyphicon-envelope',
                    type: 'error'
                });
            }
        },
        error: function(error){
                new PNotify({
                    title: 'Error',
                    text: 'Error en la Carga/Alta de Responsables. Intente nuevamente y en caso de persistir el error comuniquese con su administrador',
                    icon: 'glyphicon glyphicon-envelope',
                    type: 'error'
                });
        }
    });
    
    
}

function actualizarResponsable(xhref){ 
    $.ajax({
        url    : xhref,                 
        dataType: "json",
        success: function (response){              
            if(response.error==='0'){
                $('#modalAsignacionResponsable').modal('show').find('#modalContent').html(response.vista);   
            }else{
                new PNotify({
                    title: 'Error',
                    text: response.message,
                    icon: 'glyphicon glyphicon-envelope',
                    type: 'error'
                });
            }
        },
        error: function(error){
            new PNotify({
                    title: 'Error',
                    text: 'Error en la Actualizacion de Responsables. Intente nuevamente y en caso de persistir el error comuniquese con su administrador',
                    icon: 'glyphicon glyphicon-envelope',
                    type: 'error'
                });
        }
    });
}

function quitarResponsable(xhref){       
    bootbox.confirm({
        message: "Esta seguro que desea realizar la eliminación?",
        buttons: {
            confirm: {
                label: '<i class="glyphicon glyphicon-ok"></i> Si',
                className: 'btn-success'
            },
            cancel: {
                label: '<i class="glyphicon glyphicon-remove"></i> No',
                className: 'btn-danger'
            }
        },
        callback: function (result) {
            $("body").loading({message: 'AGUARDE... procesando.'});
            if(result===true){                
                $.ajax({
                     url    : xhref,
                     type   : "post",            
                     dataType: "json",
                     success: function (response){
                         if(response.error==0){                             
                            new PNotify({
                                title: 'Correcto',
                                text: response.mensaje,
                                icon: 'glyphicon glyphicon-envelope',
                                type: 'success'
                            });                            
                            $.pjax.reload({container:"#pjax-responsable",timeout:false});                            
                         }else{                             
                            new PNotify({
                                title: 'Error',
                                text: response.mensaje,
                                icon: 'glyphicon glyphicon-envelope',
                                type: 'error'
                            });
                         }
                     },
                     error  : function (error) {                            
                            if(error.status == 403 && error.statusText=='Forbidden') 
                                mensaje = 'Usted no dispone de los permisos suficientes para realizar esta tarea';
                            else
                                mensaje = 'Se produjo un error en la ejecucion de la tarea. Intente nuevamente y si persiste el error comuniquese con su administrador';

                            new PNotify({
                                title: 'Error',
                                text: mensaje,
                                icon: 'glyphicon glyphicon-envelope',
                                type: 'error'
                            });      
                     }
                }).done(function(o) {
                    $("body").loading('stop');       
                });
            }else{
                $("body").loading('stop');    
            }
            
        }
    });
    
    return false;       
} //fin deleteAjax 

$("body").on("beforeSubmit", "form.form-carga-responsable", function () {
    var grillaajax = '#pjax-responsable';
    var form = $(this);
    
    $('#btn-enviar').attr('disabled','disabled');
    $('#btn-enviar').html('<i class=\'fa fa-spinner fa-spin\'></i> Procesando...');  
        
    // submit form        
    $.ajax({
        url    : form.attr("action"),
        type   : "post",
        data   : form.serialize(),
        dataType: "json",
        success: function (response) {
            if(response.error == 0){                                   
                if(response.carga == 1){  
                    $("#modalAsignacionResponsable").modal("toggle"); 
                    grillaajax = '#pjax-responsable';
                    $.pjax.reload({container:grillaajax, timeout:false});                    
                }
                else
                if (response.carga==0){
                    $("body").loading('stop');
                    $("#modalAsignacionResponsable").find("#modalContent").html("");
                    $("#modalAsignacionResponsable").modal("show").find("#modalContent").html(response.vista);
                }
            }
            else{    
                new PNotify({
                            title: 'ERROR',
                            text: response.message,
                            icon: 'glyphicon glyphicon-envelope',
                            type: 'error'
                        });
                $("#modalAsignacionResponsable").modal("toggle");
                grillaajax = '#pjax-responsable';                                     
                $.pjax.reload({container:grillaajax,timeout:false});                            
            }
        },
        error  : function () {
            console.log("internal server error");
        }
    });

    return false;
});

