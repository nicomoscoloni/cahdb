<div class="box box-solid box-colegio">
    <div class="box-header with-border">
        <i class="fa fa-arrow-circle-o-right"></i><h3 class="box-title"> Detalle Comprobante Pago :  </h3>
    </div>
    <div class="box-body">
        <br />        
        <div class="row">
            <div class="col-sm-8 col-sm-offset-2 col-xs-12">

            <table>
                <tr>
                    <td width="25%"> <img class="img-responsive" src="<?php echo Yii::getAlias('@web') . "/images/cajaFacturas.png"; ?>" alt="cp_dollar" />  </td>
                    <td style="padding-left: 20px;">
                        <span class="text-light-blue bold" style="font-size: 22px; color: #a00519 !important; font-weight: bold;"> Tiket Nro:  <?php echo $tiket->id; ?> </span><br />
                        <span class="text-black bold"  style="font-size: 18px; font-weight: bold;">    Fecha Tiket:  </span><?php echo \Yii::$app->formatter->asDate($tiket->fecha_tiket); ?> <br />
                        <span class="text-black bold"  style="font-size: 18px; font-weight: bold;">    Monto: </span> <?php echo "$ ".$tiket->importe; ?> <br />                
                        <br/>
                        <span class="text-black bold"  style="font-size: 18px; font-weight: bold;"> Familia: </span> <?php echo $familia->apellidos ."  FOLIO: ". $familia->folio; ?> </span>                        
                        <br />
                            <?php
                            echo dmstr\helpers\Html::button('<i class="glyphicon glyphicon-print"> </i> Tiket', ['class' => 'btn btn-warning', 'id'=>'btn-pdf-tiket',
                                'onclick'=>'js:{downFactura("'. yii\helpers\Url::to(['caja/pdf-tiket','nroTiket'=>$tiket->id]) .'");}']);
                        ?>     
                    </td>
                </tr>
                <tr>
                      <td colspan="2">
                          <br />
                          <span style="font-size: 14px; font-weight: bold;"> Detalles Servicios Abonados: </span> <br />
                          <?php if (!empty($serviciosTiket)){
                                echo "<ul>";
                                foreach($serviciosTiket as $servicio)
                                {
                                    echo "<li class='text-blue' style='font-size: 14px; font-weight: bold;'>";
           
                                    echo $servicio->miDetalle; 
                                    echo "</li>";
                                } 
                                echo "</ul>";
                           }else{
                               echo $tiket->detalles;
                           } ?>
                      </td>
                  </tr>
            </table>
                
            
            </div>
        </div>



    </div>
</div>

<script type="text/javascript">  
function downFactura(xhref){
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
                error: function(XHR) {
                   $("body").loading('stop');                    
                   if (XHR.statusText == 'Unauthorized')
                    {
                        new PNotify({
                            title: 'ERROR!!!',
                            text: 'USTED NO TIENE LOS PERMISOS SUFICIENTES PARA LLEVAR A CABO LA TAREA SOLICITADA',
                            icon: 'ui-icon ui-icon-mail-closed',
                            opacity: .8,
                            type: 'success'
                           
                        });
                    }else
                    if( ((XHR.status == '403') || ((XHR.status == 403))) && ((XHR.statusText == 'Forbidden'))){
                            new PNotify({
                                title: 'ERROR!!!',
                                text: 'USTED NO TIENE LOS PERMISOS SUFICIENTES PARA LLEVAR A CABO LA TAREA SOLICITADA',
                                icon: 'ui-icon ui-icon-mail-closed',
                                opacity: .8,
                                type: 'success'                           
                            });  
                     }
                }
    }); 
}

function enviarFactura(xhref){
    $("body").loading({message: 'ESPERE... procesando'});
    $.ajax({
        url    : xhref,
        type   : "post",            
        dataType: "json",
        success: function (response){             
             $("body").loading('stop');  
             if(response.result_error==='0'){
                 new PNotify({
                    title: 'OK',
                    text: 'Se envio por correo el comprobante de pago',
                    icon: 'glyphicon glyphicon-envelope',
                    type: 'success'
                });
             }else{
                new PNotify({
                    title: 'Error',
                    text: 'No se pudo enviar el comprobante de pago. No se encuentra registrado el mail del Matrículado',
                    icon: 'glyphicon glyphicon-envelope',
                    type: 'error'
                });
             }
        },
                error: function(XHR) {
                   $("body").loading('stop');                    
                   if (XHR.statusText == 'Unauthorized')
                    {
                        new PNotify({
                            title: 'ERROR!!!',
                            text: 'USTED NO TIENE LOS PERMISOS SUFICIENTES PARA LLEVAR A CABO LA TAREA SOLICITADA',
                            icon: 'ui-icon ui-icon-mail-closed',
                            opacity: .8,
                            type: 'success'
                           
                        });
                    }else
                    if( ((XHR.status == '403') || ((XHR.status == 403))) && ((XHR.statusText == 'Forbidden'))){
                            new PNotify({
                                title: 'ERROR!!!',
                                text: 'USTED NO TIENE LOS PERMISOS SUFICIENTES PARA LLEVAR A CABO LA TAREA SOLICITADA',
                                icon: 'ui-icon ui-icon-mail-closed',
                                opacity: .8,
                                type: 'success'                           
                            });  
                     }
                }
    }); 
}
</script>