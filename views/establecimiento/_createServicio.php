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
        
        <?php $form = ActiveForm::begin(['id'=>'form-alta-servicios']); ?>
        
        
            <div class="row">
                <div class="col-sm-6">
                    <?=  $form->field($model, 'id_servicio')->dropDownList(app\models\ServicioOfrecido::getServiciosConDetalle(),
                        ['prompt'=>'Seleccione...', 
                        'id'=>'miservicio', 
                        'class'=>'form-control']); ?>   
                </div>            
            </div>  
        
        <div id="chau"></div>
           
            <div class="form-group">
                <?= Html::submitButton($model->isNewRecord ? 'Guardar' : 'Actualizar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary','id'=>'btn-envio']) ?>
            </div>
        <?php ActiveForm::end(); ?>
    </div>
        
    </div>
</div>
<?php
$this->registerJs('
    $("#miservicio").on("change",function(){
        var est='.$model->establecimiento.';
        var ser = $(this).val();
        
        
        $.ajax({
            url: \''. yii\helpers\Url::toRoute(['establecimiento/hola']) .'\',
            data: {idServ:+ser,idEst:+est},
            dataType: \'json\',
            beforeSend: function(response){
                
            },
            success: function(response){
                if(response.error===\'0\'){
                    $(\'#chau\').html(response.vista);   
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
<?php
$this->registerJs('
$(document).ready(function(){

    $("#form-alta-servicios").on("beforeValidate",function(e){
        $("#btn-envio").attr("disabled","disabled");
        $("#btn-envio").html("<i class=\'fa fa-spinner fa-spin\'></i> Procesando...");        
    });
    
    $("#form-alta-servicios").on("afterValidate",function(e, messages){
        if ( $("#form-alta-servicios").find(".has-error").length > 0){
            $("#btn-envio").removeAttr("disabled");
            $("#btn-envio").html("<i class=\'fa fa-save\'></i> Guardar...");
        }
    });    
});         
', \yii\web\View::POS_READY,'js-cargaservicios');
?>