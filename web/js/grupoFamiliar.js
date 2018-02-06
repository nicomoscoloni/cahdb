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
                $('#modalAsignacionResponsable').modal('show').find('#modalContent').html(response.vista); 
        },
        error: function(error){
            new PNotify({
                    title: 'Error',
                    text: 'Error al querer asignar responsables. Intente nuevamente y en caso de persistir el error comuniquese con su administrador',
                    icon: 'glyphicon glyphicon-envelope',
                    type: 'error'
                });
        },
        
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
        success: function (response) {
                $("#modalAsignacionResponsable").modal("toggle");                 
                $.pjax.reload({container:'#pjax-responsables', timeout:false}); 
        },
        error  : function (error) {
                console.log(error);
                new PNotify({
                    title: 'Error',
                    text: 'Error al querer asignar responsables. Intente nuevamente y en caso de persistir el error comuniquese con su administrador',
                    icon: 'glyphicon glyphicon-envelope',
                    type: 'error'
                });
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
        message: "Está seguro que deséa realizar la eliminación?",
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
                     type   : "get",            
                     dataType: "json",
                     success: function (response){
                         $("body").loading('stop');
                         if(response.error==0){                             
                            new PNotify({
                                title: 'Correcto',
                                text: response.mensaje,
                                icon: 'glyphicon glyphicon-envelope',
                                type: 'success'
                            });                            
                            $.pjax.reload({container:"#pjax-responsables",timeout:false});                            
                         }
                     },
                     error  : function (error) { 
                            $("body").loading('stop');
                            $.pjax.reload({container:"#pjax-responsables",timeout:false}); 
                            new PNotify({
                                title: 'Error',
                                text: 'Error al intentar desvincular al responsbale del Grupo Familiar.',
                                icon: 'glyphicon glyphicon-envelope',
                                type: 'error'
                            });      
                     }
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
                    $("body").loading('stop');
                    $("#modalAsignacionResponsable").modal("toggle"); 
                    grillaajax = '#pjax-responsables';
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
                grillaajax = '#pjax-responsables';                                     
                $.pjax.reload({container:grillaajax,timeout:false});                            
            }
        },
        error  : function (xhr) {
            console.log(xhr);
            console.log("internal server error");
        }
    });

    return false;
});

