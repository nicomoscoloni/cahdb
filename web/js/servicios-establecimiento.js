function asignarServicioDivision(xhref){      
    $('body').loading({message: 'AGUARDE... procesando.'}); 
    
    $.ajax({
        url    : xhref,                   
        dataType: 'json',
        success: function (response){                         
            if(response.error==0){
                $.pjax({container: '#pjax-divisionesservicios', timeout: false}).done(function(){
                    $('body').loading('stop')                   
                });                            
            }else{
                $('body').loading('stop');
                new PNotify({
                    title: 'Error',
                    text: 'Se produjo un error al asignar el servicio',
                    icon: 'glyphicon glyphicon-envelope',
                    type: 'error'
                });
            }
        }
    });
    
    return false;       
} //fin deleteAjax 

function quitarServicio(xhref){
    
    $('body').loading({message: 'AGUARDE... procesando.'}); 
    
    $.ajax({
        url    : xhref,
        type   : 'post',            
        dataType: 'json',
        success: function (response){                         
            if(response.error==0){
                $.pjax({container: '#pjax-servicioslibres', timeout: false}).done(function(){ 
                    $.pjax({container: '#pjax-serviciosadheridos', timeout: false}).done(function() { 
                        $('body').loading('stop'); })});
                            
            }else{
                $('body').loading('stop');
                new PNotify({
                    title: 'Error',
                    text: 'Se produjo un error al asignar el servicio',
                    icon: 'glyphicon glyphicon-envelope',
                    type: 'error'
                });
            }
        }
    });
    
    return false;       
} //fin deleteAjax 