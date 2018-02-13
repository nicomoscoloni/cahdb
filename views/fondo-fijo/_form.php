<?php

use yii\helpers\Html;
//use yii\widgets\ActiveForm;
use kartik\widgets\ActiveForm;
use kartik\widgets\DatePicker;

/* @var $this yii\web\View */
/* @var $model app\models\FondoFijo */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="fondo-fijo-form">

    <?php $form = ActiveForm::begin([
        'id'=>'form-fondofijo',
        'type'=> kartik\form\ActiveForm::TYPE_HORIZONTAL,
        'formConfig'=> ['labelSpan'=>2,]
    ]); ?>
    
    <?= $form->field($model, 'id_establecimiento')->dropDownList(app\models\Establecimiento::getEstablecimientos(),
            ['class' => '',
            'prompt'=>'Seleccione',            
            ]);
    ?>
 
    <?= $form->field($model, 'monto_actual')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'alerta_tope_maximo')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'tope_compra')->textInput(['maxlength' => true]) ?>

        <div class="form-group">
            <div class="col-sm-offset-3 col-sm-9">
                <?= Html::submitButton("<i class='fa fa-save'></i> " . ' GUARDAR', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary', 'id' => 'btn-envio']) ?>
            </div>
        </div>
    <?php ActiveForm::end(); ?>
</div>
<?php
$this->registerJs('  
$(document).ready(function(){
    $("#form-fondofijo").on("beforeValidate",function(e){
        $("#btn-envio").attr("disabled","disabled");
        $("#btn-envio").html("<i class=\'fa fa-spinner fa-spin\'></i> Procesando...");        
    });
    
    $("#form-fondofijo").on("afterValidate",function(e, messages){
        if ( $("#form-fondofijo").find(".has-error").length > 0){
            $("#btn-envio").removeAttr("disabled");
            $("#btn-envio").html("<i class=\'fa fa-save\'></i> Guardar...");
        }
    });
    
});         
', \yii\web\View::POS_READY,'js-fondofijo');
?>