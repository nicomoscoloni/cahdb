<?php

use yii\helpers\Html;
use kartik\form\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\ServicioEstablecimiento */ 
/* @var $form yii\widgets\ActiveForm */
?>

<div class="box box-solid box-colegio">
    <div class="box-header with-border">
        <i class="fa fa-university"></i> 
        <h3 class="box-title"> Alta Servicios / Establecimiento </h3>
    </div>
    <div class="box-body">
        <div class="servicio-establecimiento-form">
                <div class="row">
                    <div class="col-sm-6">
                        <?= Html::activeDropDownList($model, 'id_servicio', app\models\ServicioOfrecido::getServiciosConDetalle(),
                            ['prompt'=>'Seleccione...', 
                            'id'=>'miservicio', 
                            'class'=>'form-control']); ?>   
                    </div>            
                </div>  
                <div id="servicios-asign"></div>
        </div>        
    </div>
</div>
<?php
$this->registerJs('
    $("#miservicio").on("change",function(){        
        var est='.$model->establecimiento.';
        var ser = $(this).val();        
        
        $.ajax({
            url: \''. yii\helpers\Url::toRoute(['establecimiento/get-servicios']) .'\',
            data: {idServ:+ser,idEst:+est},
            dataType: \'json\',
            beforeSend: function(response){                
            },
            success: function(response){
                if(response.error===\'0\'){
                    console.log(response);
                    $(\'#servicios-asign\').html(response.vista);   
                }
            },
            error:function(){
                console.log("ERROR INERNO");
            }
        });
        return false;
    });
',
 yii\web\View::POS_READY,'multiselect');?>