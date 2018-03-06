/* funcionalidad para el view */
function downPdfConvenio(xhref){
    $("body").loading({message: 'ESPERE... procesando'});
    $.ajax({
         url    : xhref,
         type   : "post",            
         dataType: "json",
         success: function (response){             
             $("body").loading('stop');  
             if(response.result_error==='0'){
                window.location.href = response.result_texto; 
             }else{
                new PNotify({
                    title: 'Error',
                    text: response.message,
                    icon: 'glyphicon glyphicon-envelope',
                    type: 'error'
                });
             }
        },         
    }); 
}   

function enviarPdfConvenio(xhref){    
    $("body").loading({message: 'ESPERE... procesando'});
    $.ajax({
         url    : xhref,
         type   : "post",            
         dataType: "json",
         success: function (response){             
             $("body").loading('stop');  
             if(response.result_error==='0'){
                new PNotify({
                    title: 'Correcto',
                    text: 'Se envio de forma correcta en correo.',
                    icon: 'glyphicon glyphicon-envelope',
                    type: 'success'
                }); 
             }else{
                new PNotify({
                    title: 'Error',
                    text: response.message,
                    icon: 'glyphicon glyphicon-envelope',
                    type: 'error'
                });
             }
        },         
    }); 
}

/******************************/
function getUncheckeds(){
    var unch = [];
    /*corrected typo: $('[name^=someChec]') => $('[name^=misservicios]') */
    $('[name^=selection]').not(':checked,[name$=all]').each(function(){unch.push($(this).val());});
    return unch.toString();
}
       
$('#pjax-servicios-convenio').on('pjax:beforeSend', function (event, data, status, xhr, options) {
    seleccionados = $('#gridServiviosCP').yiiGridView('getSelectedRows').toString();     
    no_seleccionados = getUncheckeds();
    status.data = status.data+'&selects='+seleccionados;
    status.data = status.data+'&noselects='+no_seleccionados;       
});


$(document).ready(function(){ 
    $('#form-servicios').on('beforeSubmit',function(e, messages){
        $('#btn-generar-convenio').attr('disabled','disabled');
        $('#btn-generar-convenio').html('<i class=\'fa fa-spinner fa-spin\'></i> Procesando...');
        
        alert("asda");
        seleccionados = $('#gridServiviosCP').yiiGridView('getSelectedRows').toString();     
        no_seleccionados = getUncheckeds();
        $('#selects').val(seleccionados);
        $('#noselects').val(no_seleccionados);
        $('#envios').val(1);        
       
    });
});

/**************************************/
function addCuota(url){     
    var ordn = parseInt($('#ordn').val()) + 1;    
   
    $.ajax({
        'url': url,
        'dataType': 'json',
        'type': 'POST',
        'data': 'nro='+ordn,
        'beforeSend': function(xhr){

        },
        'success':function(data){            
            dataCuota = data.vista;
            $('#misCuotas').prepend(dataCuota);
            $('#ordn').val(ordn);       
        },
    });   
}

function eliminarcuota(nrocuota){
    if($('.groupcuota').length>1){
        $('#divcuota-'+nrocuota).remove();    
    }else{
                            new PNotify({
                                title: 'Error',
                                text: 'No se puede eliminar la cuota, al menos debe existir una de ellas',
                                icon: 'glyphicon glyphicon-envelope',
                                type: 'error'
                            });
    }
    
}

$('#form-convenio').on('beforeValidate',function(e){
    $('#btn-envio').attr('disabled','disabled');
    $('#btn-envio').html('<i class=\'fa fa-spinner fa-spin\'></i> Procesando...');        
});
$('#form-convenio').on('afterValidate',function(e, messages){
    if ($('#form-convenio').find('.has-error').length > 0){
        $('#btn-envio').removeAttr('disabled');
        $('#btn-envio').html('<i class=\'fa fa-save\'></i> Guardar...');
    }
});    