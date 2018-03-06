/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$(document).ready(function () {
    $(document).on('pjax:beforeSend', '#pjax-servicioofrecido', function() { 
        $("body").loading({message: 'Aguarde procesando....'});
    });
    $(document).on('pjax:complete', '#pjax-servicioofrecido', function() { 
        $("body").loading('stop');
    });
    
    
    $('#form-servicioofrecido').on('beforeValidate',function(e){
        $('#btn-envio').attr('disabled','disabled');
        $('#btn-envio').html('<i class=\'fa fa-spinner fa-spin\'></i> Procesando...');        
    });
    $('#form-servicioofrecido').on('afterValidate',function(e, messages){
        if ($('#form-servicioofrecido').find('.has-error').length > 0){
            $('#btn-envio').removeAttr('disabled');
            $('#btn-envio').html('<i class=\'fa fa-save\'></i> Guardar...');
        }
    });
});       