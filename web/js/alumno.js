function inactivarAlumno(xhref){    
    grillaajax = '#pjax-alumnos';
    
    bootbox.confirm({
        message: "Está seguro que desea INACTIVAR al Alumno?",
        buttons: {
            confirm: {
                label: 'Si',
                className: 'btn-success'
            },
            cancel: {
                label: 'No',
                className: 'btn-danger'
            }
        },
        callback: function (result) {
            if(result===true){     
                $("body").loading({message: 'ESPERE... procesando'});
                $.ajax({
                     url    : xhref,
                     type   : "post",            
                     dataType: "json",
                     success: function (response){  
                         if(response.error==0){                             
                            $.pjax.reload({container:grillaajax, timeout:false}); 
                            $(document).on('pjax:complete',grillaajax, function() {
                                new PNotify({
                                    title: 'Correcto',
                                    text: response.mensaje,
                                    icon: 'glyphicon glyphicon-envelope',
                                    type: 'success'
                                });   
                                $(document).off('pjax:complete',grillaajax);
                            });                              
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
                        if(error.status == 403 && error.statusText=='Forbidden') {                            
                            new PNotify({
                                title: 'Error',
                                text: 'Usted no dispone de los permisos suficientes para realizar esta tarea',
                                icon: 'glyphicon glyphicon-envelope',
                                type: 'error'
                            });
                        }
                     }
                     
                 }).done($("body").loading('stop'));
            }
        }
    });
    return false;       
}

function activarAlumno(xhref){    
    grillaajax = '#pjax-alumnos';
    
    bootbox.confirm({
        message: "Esta seguro que desea INACTIVAR al Alumno?",
        buttons: {
            confirm: {
                label: 'Si',
                className: 'btn-success'
            },
            cancel: {
                label: 'No',
                className: 'btn-danger'
            }
        },
        callback: function (result) {
            if(result===true){     
                $("body").loading({message: 'ESPERE... procesando'});
                $.ajax({
                     url    : xhref,
                     type   : "post",            
                     dataType: "json",
                     success: function (response){                         
                        if(response.error==0){                             
                            $.pjax.reload({container:grillaajax,timeout:false}); 
                            $(document).on('pjax:complete',grillaajax, function() { 
                                new PNotify({
                                    title: 'Correcto',
                                    text: response.mensaje,
                                    icon: 'glyphicon glyphicon-envelope',
                                    type: 'success'
                                });   
                                $(document).off('pjax:complete',grillaajax);
                            });                              
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
                        if(error.status == 403 && error.statusText=='Forbidden') {                            
                            new PNotify({
                                title: 'Error',
                                text: 'Usted no dispone de los permisos suficientes para realizar esta tarea',
                                icon: 'glyphicon glyphicon-envelope',
                                type: 'error'
                            });
                        }       
                    }
                 }).done($("body").loading('stop'));
            }
        }
    });
    return false;       
} 

function quitarBonificacion(xhref){
   
    $('#grilla-ajax').val('#pjax-bonificaciones');
    grillaajax = $('#grilla-ajax').val();
    
    bootbox.confirm({
        message: "Esta seguro que desea realizar la eliminación?",
        buttons: {
            confirm: {
                label: 'Si',
                className: 'btn-success'
            },
            cancel: {
                label: 'No',
                className: 'btn-danger'
            }
        },
        callback: function (result) {            
            if(result===true){
                $("body").loading({message: 'AGUARDE... procesando.'});
                $.ajax({
                     url    : xhref,
                     type   : "post",            
                     dataType: "json",
                     success: function (response){                         
                         if(response.error==0){   
                            $.pjax.reload({container:grillaajax,timeout:false}); 
                            $(document).on('pjax:complete',grillaajax, function() {
                                new PNotify({
                                    title: 'Correcto',
                                    text: response.mensaje,
                                    icon: 'glyphicon glyphicon-envelope',
                                    type: 'success'
                                });
                                 $(document).off('pjax:complete',grillaajax);
                            });     
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
                        if(error.status == 403 && error.statusText=='Forbidden') {                            
                            new PNotify({
                                title: 'Error',
                                text: 'Usted no dispone de los permisos suficientes para realizar esta tarea',
                                icon: 'glyphicon glyphicon-envelope',
                                type: 'error'
                            });
                        }       
                    }
                 }).done($("body").loading('stop'));
            }
        }
    });
    return false;  
}

/****************************************************************************/
/****************************************************************************/
$(document).ready(function () {
    //bloquemos la pantalla cuando se realiza apetion ajaz del gri de alumnos
    $(document).on('pjax:send', '#pjax-alumnos', function() {        
        $('body').loading({message: 'ESPERE... procesando'});
    });          
    //al finalizar lallamada ajax del render de pjax del grid de alumnos
    //actulizamos el combio de divisiones segun el establecimiento
    $(document).on('pjax:complete', function() {      
        establecimiento = $('#alumnosearch-establecimiento').val();        
        $.ajax({
            url    : 'index.php?r=establecimiento/mis-divisionesescolares',
            type   : 'GET',  
            data: { 'idEst': establecimiento},
            success: function (data){ 
                  $('#alumnosearch-id_divisionescolar').html('Seleccione');
                  $('#alumnosearch-id_divisionescolar').html(data);       
            },
        }).done($('body').loading('stop'));           
    });
});

/****************************************************************************/
/****************************************************************************/
$(document).ready(function () {
    
    $("#buscarFamiliaBtn").click(function(){                
        $("#modalfamilia").modal("show").find("#modalContent").load(jQuery(this).attr("value"));
    });   
    
    /*
     * Capturamos el evento pblicado de la familia seleccionada
     */
    $("body").on("familia:seleccionada", function(event, familia){        
        $("#mifamilia").val(familia.id);
        $("#apellidoFamilia").val(familia.apellidos);
        $("#folioFamilia").val(familia.folio);   
        $("#responsableFamilia").val(familia.responsbalePrincipal);
        $("#modalfamilia").modal("hide");
    });    
      
    $("#form-empadronamiento").on("beforeValidate",function(e){
        $("#btn-envio").attr("disabled","disabled");
        $("#btn-envio").html("<i class=\'fa fa-spinner fa-spin\'></i> Procesando...");        
    });
    
    $("#form-empadronamiento").on("afterValidate",function(e, messages){
        if ( $("#form-empadronamiento").find(".has-error").length > 0){
            $("#btn-envio").removeAttr("disabled");
            $("#btn-envio").html("<i class=\'fa fa-save\'></i> Guardar...");
        }
    });
      
});        

/****************************************************************************/
/****************************************************************************/
/*
 * Funciones para la asignacion y quitas de bonificaciones alumno
 */
$(document).ready(function () {

    $("#btn-asignar-bonificacion").click(function(){     
        xhref = $(this).attr("value")
        $.ajax({
            url    : xhref,                 
            dataType: "json",
            success: function (response){            
                if(response.error==='0'){                    
                    $('#modalBonificaciones').modal('show').find('#modalContent').html(response.vista);   
                }else{
                    new PNotify({
                        title: 'Error',
                        text: response.mensaje,
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


    $("body").on("beforeSubmit", "form#formAsignacionBonificacion", function () {
        var form = $(this);  

        $('#grilla-ajax').val('#pjax-bonificaciones');
        grillaajax = $('#grilla-ajax').val();

        // submit form
        $("body").loading({message: 'AGUARDE... procesando.'});
        $.ajax({
            url    : form.attr("action"),
            type   : "post",
            data   : form.serialize(),
            dataType: "json",
            success: function (response) {
                if(response.error == 0){                                   
                    if((response.carga == '1') && (response.error == '0')){ 

                        $.pjax.reload({container:grillaajax,timeout:false}); 
                        $(document).on('pjax:complete',grillaajax, function() {                               
                            
                            new PNotify({
                                    title: 'Correcto',
                                    text: response.mensaje,
                                    icon: 'glyphicon glyphicon-envelope',
                                    type: 'success'
                                });
                            $("#modalBonificaciones").modal("toggle");
                            $(document).off('pjax:complete',grillaajax);
                        }); 
                    }
                    else
                    if ((response.carga=='0') && (response.error=='0')){
                        
                        $("#modalBonificaciones").modal("show").find("#modalContent").html("");
                        $("#modalBonificaciones").modal("show").find("#modalContent").html(response.vista);
                    }
                }
                else{                    
                    
                    new PNotify({
                                title: 'ERROR',
                                text: response.message,
                                icon: 'glyphicon glyphicon-envelope',
                                type: 'error'
                            });
                    $("#modalBonificaciones").modal("toggle");                    
                }
            },
            error  : function () {
                console.log("internal server error");
            }
        }).done($("body").loading('stop'));        
        return false;
    });

 });