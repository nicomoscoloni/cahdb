$(document).ready(function(){
    $(".form-prev-submit").on("beforeValidate",function(e){
        $(".btn-envio").attr("disabled","disabled");
        $(".btn-envio").html("<i class=\'fa fa-spinner fa-spin\'></i> Procesando...");        
    });
    
    $(".form-prev-submit").on("afterValidate",function(e, messages){
        if ( $(".form-prev-submit").find(".has-error").length > 0){
            $(".btn-envio").removeAttr("disabled");
            $(".btn-envio").html("<i class=\'fa fa-save\'></i> Guardar...");
        }
    });
});   

function downListado(xhref){    
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
    }).done(function(o) {
        $("body").loading('stop');       
    });
}