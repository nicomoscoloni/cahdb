<?php

use yii\helpers\Html;
//use yii\widgets\ActiveForm;
use kartik\widgets\ActiveForm;
use kartik\widgets\DatePicker;

/* @var $this yii\web\View */
/* @var $model common\models\Establecimiento */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="establecimiento-form">

    <?php $form = ActiveForm::begin(['id'=>'form-establecimiento']); ?>
    

    <div class="row">
        <div class="col-sm-5">
            <?= $form->field($model, 'nombre') ?>
        </div>
    </div>
    
    <div class="row">
        <div class="col-sm-3">
            <?= $form->field($model, 'xfecha_apertura')->widget(
                    DatePicker::className(),([
                                        'language'=>'es',                                        
                                        'pluginOptions' => [
                                            'autoclose'=>true,
                                            'format' => 'dd-mm-yyyy'
                                        ]
                                    ])
                    );?>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-5">
            <?= $form->field($model, 'calle') ?>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-5">
            <?= $form->field($model, 'telefono',['addon' => ['prepend' => ['content'=>'<i class="glyphicon glyphicon-phone"></i>']]]) ?>
        </div>
    </div>
    
    <div class="row">
        <div class="col-sm-5">
            <?= $form->field($model, 'celular',['addon' => ['prepend' => ['content'=>'<i class="glyphicon glyphicon-phone"></i>']]]) ?>
        </div>
    </div>
    
    <div class="row">
        <div class="col-sm-5">
            <?= $form->field($model, 'mail',['addon' => ['prepend' => ['content'=>'@']]])->input('email') ?>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-5">
            <?= $form->field($model, 'nivel_educativo'); ?>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton("<i class='fa fa-save'></i> ".' GUARDAR', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary','id'=>'btn-envio']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<?php
$this->registerJs('  
$(document).ready(function(){
    $("#form-establecimiento").on("beforeValidate",function(e){
        $("#btn-envio").attr("disabled","disabled");
        $("#btn-envio").html("<i class=\'fa fa-spinner fa-spin\'></i> Procesando...");        
    });
    
    $("#form-establecimiento").on("afterValidate",function(e, messages){
        if ( $("#form-establecimiento").find(".has-error").length > 0){
            $("#btn-envio").removeAttr("disabled");
            $("#btn-envio").html("<i class=\'fa fa-save\'></i> Guardar...");
        }
    });
    
});         
', \yii\web\View::POS_READY,'js-establecimiento');
?>