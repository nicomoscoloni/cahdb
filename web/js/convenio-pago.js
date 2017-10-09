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
        $('#envios').val(1);
       
    });
});