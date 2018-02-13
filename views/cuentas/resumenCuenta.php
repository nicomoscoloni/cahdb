<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;

?>

<div class="box box-solid box-colegio">
    <div class="box-header with-border">
        <i class="fa fa-arrow-circle-o-right"></i><h3 class="box-title"> Cuenta :  </h3>
    </div>
    <div class="box-body">
        <div class="row">
            <div class="col-sm-8 col-sm-offset-2 col-xs-12">
                <table>
                    <tr>
                        <td width="25%">
                            <img class="img-responsive" src="<?php echo Yii::getAlias('@web') . "/images/cajaregistradora.png"; ?>" alt="cp_dollar" />  </td>
                        <td style="padding-left: 10px;"> 
                            <h2 class="text-light-blue bold"> CUENTA <?php echo $modelCuenta->nombre; ?> </h2>
                            <span class="text-light-blue bold text-det-caja"> Fecha Apertura: </span> <span class="bold text-det-caja"><?php echo \Yii::$app->formatter->asDate($modelCuenta->fecha_apertura); ?></span><br /> 
                            <span class="text-light-blue bold text-det-caja"> SALDO ACTUAL: </span> <span class="bold text-det-caja"> $ <?php echo $modelCuenta->saldo_actual; ?> </span><br />
                            <br />
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        
        <div class="row">
           <?php echo $this->render('_searchMovimientos',['model'=>$searchModelMC]); ?>
        </div><!-- search-form -->
        <h6 class="bold"> Detalles Movimientos: </h6>
        <p> 
                </p>
        <?php Pjax::begin([
                        'id'=>'pjax-detalle-movimientos',                       
                        'enablePushState' => false,
                       ]);     
        echo
        GridView::widget([
            'id'=>'grid-movimientos',
            'dataProvider' => $misMovimientos,            
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                
                'detalle_movimiento',
                'fecha_realizacion:date',
                [
                    'label' => 'Movimiento',
                    'attribute'=>'tipo_movimiento',
                    'contentOptions'=>['class'=>'columncenter'],
                    'value' => function($model) {
                        return $model->tipo_movimiento;
                    },
                ],    
                [
                    'label' => 'Monto',
                    'attribute'=>'importe',
                    'contentOptions'=>['class'=>'columncenter'],
                    'value' => function($model) {
                        return "$ ".$model->importe;
                    },
                ],                        
                
            ],
        ]);
        ?>
        <?php Pjax::end(); ?>
    </div>
</div>
<?php
$this->registerJs("
$(document).ready(function(){
    $('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
    });
});         
", \yii\web\View::POS_READY, 'search');
?>
<style type="text/css">
    #search{font-size: 16px;}
    .search-button{padding: 8px;
                   color: #fff;
                   background: #428bca;} 
    .text-det-caja{font-size: 20px;}
    .bold{font-weight: bold;}
</style>
<script type="text/javascript">  
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
         error: function(XHR) {
                   $("body").loading('stop'); 
                   console.log(XHR);
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