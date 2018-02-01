/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


var establecimiento = {
    misDivisionesEscolares: function(xhref){        
        $.ajax({
            url    : xhref,             
                        
            success: function (response){              
                $('#datosEst').html(response);
            },
            error: function(error){            
                            
            }
        });    
        return false;    
    },
    misServicios: function(xhref){        
        $.ajax({
            url    : xhref,  
            success: function (response){              
                $('#datosEst').html(response);
            },
            error: function(error){ 
            }
        });    
        return false;    
    },
}

var divisionescolar = {
    cargarDivision: function(xhref){        
        $.ajax({
            url    : xhref,                 
            dataType: "json",
            success: function (response){ 
                if (response.form==='1' && response.error == '0'){
                    $('#modal-divisiones').modal('show').find('#modalContent').html("");
                    $('#modal-divisiones').modal('show').find('#modalContent').html(response.vista);   
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
    },
    eliminarDivision: function(xhref){
        bootbox.confirm({
            message: "Está seguro que desea realizar la eliminación?",
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
                                var urlreload = $('#urlreloadpajaxdivisiones').val();
                                $.pjax.reload({container: "#pjax-divisiones", timeout:false, replace: false ,url: urlreload});                                
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
    }
}



$(document).ready(function () {
    $("body").on("click", ".btn-submit-form", function () {
        $('.btn-enviar').click();
    });    
    
    $("body").on("beforeSubmit", "form#form-divisiones", function () {
        var form = $(this);
        // return false if form still have some validation errors        
        /*
        if (form.find(".has-error").length) {
            alert("CON ERRORES");
            return false;
        }
        */
       var urlreload = $('#urlreloadpajaxdivisiones').val();
       
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
                        $("#modal-divisiones").modal("show").find("#modalContent").html("");
                        $("#modal-divisiones").modal("show").find("#modalContent").html(response.vista);
                    }
                    if(response.carga == '1' && response.form == '0') {                    
                        new PNotify({
                                title: 'Correcto',
                                text: response.mensaje,
                                icon: 'glyphicon glyphicon-envelope',
                                type: 'success'
                            });
                        $("#modal-divisiones").modal("toggle");
                        $.pjax.reload({container: "#pjax-divisiones",timeout:false,replace: false,url: urlreload});                        
                    }
                }
                else{                    
                    new PNotify({
                                title: 'ERROR',
                                text: response.mensaje,
                                icon: 'glyphicon glyphicon-envelope',
                                type: 'error'
                            });
                    $("#modal-divisiones").modal("toggle");                    
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