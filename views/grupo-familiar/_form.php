<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\GrupoFamiliar */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="grupo-familiar-form">

    <?php $form = ActiveForm::begin(['id'=>'form-grupofamiliar']); ?>

    <div class="row">
        <div class="col-sm-7">
            <?= $form->field($model, 'apellidos') ?>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-7">
            <?= $form->field($model, 'descripcion') ?>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-3">
            <?= $form->field($model, 'folio') ?>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-6">
            <?= $form->field($model, 'id_pago_asociado')->dropDownList(\app\models\FormaPago::getFormasPago(),['prompt'=>'Select...']) ?>
        </div>
    </div>
    
    <div class="row">
        <div class="col-sm-6">
                    <?php 
            if($model->id_pago_asociado==4)
                echo $form->field($model, 'cbu_cuenta')->textInput();
            else
                echo $form->field($model, 'cbu_cuenta')->textInput(['readonly' => true]);?>

          
        </div>
    </div>

    <div class="row">
        <div class="col-sm-3">
              <?php 
            if($model->id_pago_asociado==5)
                echo $form->field($model, 'nro_tarjetacredito')->textInput();
            else
                echo $form->field($model, 'nro_tarjetacredito')->textInput(['readonly' => true]);?>
           
        </div>
        <div class="col-sm-3">
              <?php 
            if($model->id_pago_asociado==5)
                echo $form->field($model, 'tarjeta_banco')->textInput();
            else
                echo $form->field($model, 'tarjeta_banco')->textInput(['readonly' => true]);?>
           
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton("<i class='fa fa-save'></i> GUARDAR", ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary','id'=>'btn-envio']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<?php
$this->registerJs("        
    $('#grupofamiliar-id_pago_asociado').on('change',function() { 
       val = $(this).val();
       
       $('#grupofamiliar-cbu_cuenta').attr('readonly','readonly');
       $('#grupofamiliar-nro_tarjetacredito').attr('readonly','readonly');
       $('#grupofamiliar-tarjeta_banco').attr('readonly','readonly');  
       
        if (val == '4'){ 
            $('#grupofamiliar-cbu_cuenta').removeAttr('readonly');                     
        }
        if (val== '5'){        
            $('#grupofamiliar-nro_tarjetacredito').removeAttr('readonly');
            $('#grupofamiliar-tarjeta_banco').removeAttr('readonly');                      
        }       
   });
", \yii\web\View::POS_READY,'selectpagos');
?>

<?php
$this->registerJs("
$(document).ready(function(){
    $('#form-grupofamiliar').on('beforeValidate',function(e){
        $('#btn-envio').attr('disabled','disabled');
        $('#btn-envio').html('<i class=\'fa fa-spinner fa-spin\'></i> Procesando...');        
    });
    $('#form-grupofamiliar').on('afterValidate',function(e, messages){
        if ($('#form-grupofamiliar').find('.has-error').length > 0){
            $('#btn-envio').removeAttr('disabled');
            $('#btn-envio').html('<i class=\'fa fa-save\'></i> Guardar...');
        }
    });
    
});         
", \yii\web\View::POS_READY,'preventSubmitForm');
?>