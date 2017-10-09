function cargaAjax(xhref,grilla=null){    
    if(grilla == null)
        $('#grilla-ajax').val('#pjax-grid');
    else
        $('#grilla-ajax').val(grilla); 
    
    $.ajax({
        url    : xhref,                 
        dataType: "json",
        success: function (response){              
            if (response.form==='1' && response.error == '0'){
                $('#ModalCrudAjax').modal('show').find('#modalContent').html("");
                $('#ModalCrudAjax').modal('show').find('#modalContent').html(response.vista);   
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
    });
    
    return false;   
}

function editAjax(xhref,grilla=null){
    if(grilla == null)
        $('#grilla-ajax').val('#pjax-grid');
    else
        $('#grilla-ajax').val(grilla);
    
    $.ajax({
        url    : xhref,                 
        dataType: "json",
        success: function (response){
            if(response.form==='1' && response.error == '0'){
                $('#ModalCrudAjax').modal('show').find('#modalContent').html("");
                $('#ModalCrudAjax').modal('show').find('#modalContent').html(response.vista);   
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
    });
    
    return false;  
}



function deleteAjax(xhref,grilla=null){
    if(grilla == null)
        $('#grilla-ajax').val('#pjax-grid');
    else
        $('#grilla-ajax').val(grilla);    
    
    bootbox.confirm({
        message: "Esta seguro que dea realizar la eliminacion?",
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
                                text: response.message,
                                icon: 'glyphicon glyphicon-envelope',
                                type: 'success'
                            });
                            grillaajax = $('#grilla-ajax').val();
                            $.pjax.reload({container:"#pjax-grid",timeout:false});
                            
                         }else{                             
                            new PNotify({
                                title: 'Error',
                                text: response.message,
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



$(document).ready(function () {
    $('.btn-submit-form').click(function(){       
        $('.btn-enviar').click();
    });    
    
    $("body").on("beforeSubmit", "form.form-ajax-crud", function () {
        var form = $(this);
        // return false if form still have some validation errors        
        /*
        if (form.find(".has-error").length) {
            alert("CON ERRORES");
            return false;
        }
        */
        $("body").loading({message: 'ESPERE... procesando'});
        // submit form
        $.ajax({
            url    : form.attr("action"),
            type   : "post",
            data   : form.serialize(),
            dataType: "json",
            success: function (response) {
                if(response.error == '0'){
                    if (response.form=='1'){
                        $("body").loading('stop'); 
                        $("#ModalCrudAjax").modal("show").find("#modalContent").html("");
                        $("#ModalCrudAjax").modal("show").find("#modalContent").html(response.vista);
                    }
                    if(response.carga == '1' && response.form == '0') {                    
                        new PNotify({
                                title: 'Correcto',
                                text: response.mensaje,
                                icon: 'glyphicon glyphicon-envelope',
                                type: 'success'
                            });
                        $("#ModalCrudAjax").modal("toggle");
                        grillaajax = $('#grilla-ajax').val();                        
                        $.pjax.reload({container: grillaajax, timeout:false});                        
                    }
                }
                else{                    
                    new PNotify({
                                title: 'ERROR',
                                text: response.message,
                                icon: 'glyphicon glyphicon-envelope',
                                type: 'error'
                            });
                    $("#ModalCrudAjax").modal("toggle");
                    grillaajax = $('#grilla-ajax').val();
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
        
        return false;
    }); 
        
});

   