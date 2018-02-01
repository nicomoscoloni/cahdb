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

    $('#btn-generar-convenio').click(function(e){ 
        if($('#gridServiviosCP').yiiGridView('getSelectedRows').toString().length == 0){
            bootbox.confirm({
                message: 'Va a crear un Convenio de Pago sin servicios; realmente desea hacerlo?',
                buttons: {
                    confirm: {
                        label: '<i class=\'glyphicon glyphicon-ok\'></i> Si',
                        className: 'btn-success'
                    },
                    cancel: {
                        label: ' <i class=\'glyphicon glyphicon-remove\'></i> No',
                        className: 'btn-danger'
                    }
                },
                callback: function (result) {
                    if(result===true){  
                       $('#form-servicios').submit();
                    }
                }
            });
            e.preventDefault();
        }
    });
    
    $('#form-servicios').on('beforeSubmit',function(e, messages){
        $('#btn-generar-convenio').attr('disabled','disabled');
        $('#btn-generar-convenio').html('<i class=\'fa fa-spinner fa-spin\'></i> Procesando...');
        
        if ($('#gridServiviosCP').yiiGridView('getSelectedRows').toString().length == 0){            
            e.preventDefault();
        }
        seleccionados = $('#gridServiviosCP').yiiGridView('getSelectedRows').toString();     
        no_seleccionados = getUncheckeds();
        $('#selects').val(seleccionados);
        $('#noselects').val(no_seleccionados);
        $('#envios').val(1);
        
       
    });
});