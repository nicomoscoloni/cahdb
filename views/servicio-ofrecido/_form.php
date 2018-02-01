<?php

use yii\helpers\Html;
//use yii\widgets\ActiveForm;
use kartik\widgets\ActiveForm;
use kartik\widgets\DatePicker;



/* @var $this yii\web\View */
/* @var $model app\models\ServicioOfrecido */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="servicio-ofrecido-form">
    <?php $form = ActiveForm::begin([
        'id'=>'form-servicioofrecido',
        
    ]); ?>
   
    <div class="row">
        <div class="col-sm-6">
            <?= $form->field($model, 'id_tiposervicio')->dropDownList(app\models\CategoriaServicioOfrecido::getTipoServicios() ,['prompt'=>'Seleccione..']) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-6">
            <?= $form->field($model, 'nombre') ?>
        </div>
    </div>
    
    <div class="row">
        <div class="col-sm-6">
            <?= $form->field($model, 'descripcion') ?>
        </div>
    </div>
    
    <div class="row">
        <div class="col-sm-6">
            <?= $form->field($model, 'importe',['addon' => ['prepend' => ['content'=>'<i class="glyphicon glyphicon-phone"></i>']]]) ?>
        </div>
    </div>
    
    <div class="row">
        <div class="col-sm-6">
            <?= $form->field($model, 'importe_hijoprofesor',['addon' => ['prepend' => ['content'=>'<i class="glyphicon glyphicon-phone"></i>']]]) ?>
        </div>
    </div>
    
    <div class="row">
        <div class="col-sm-3">
            <?= $form->field($model, 'xfecha_inicio')->widget(
                    DatePicker::className(),([
                                        'language'=>'es',
                                        'type' => DatePicker::TYPE_INPUT,
                                        'pluginOptions' => [
                                            'autoclose'=>true,
                                            'format' => 'dd-mm-yyyy'
                                        ]
                                    ])
                    );?>
        </div>
        <div class="col-sm-3">
            <?= $form->field($model, 'xfecha_fin')->widget(
                    DatePicker::className(),([
                                        'language'=>'es',
                                        'type' => DatePicker::TYPE_INPUT,
                                        'pluginOptions' => [
                                            'autoclose'=>true,
                                            'format' => 'dd-mm-yyyy'
                                        ]
                                    ])
                    );?>
        </div>
    </div>
    
    <div class="row">
        <div class="col-sm-3">
            <?= $form->field($model, 'xfecha_vencimiento')->widget(
                    DatePicker::className(),([
                                        'language'=>'es',
                                        'type' => DatePicker::TYPE_INPUT,
                                        'pluginOptions' => [
                                            'autoclose'=>true,
                                            'format' => 'dd-mm-yyyy'
                                        ]
                                    ])
                    );?>
        </div>
        
        <div class="col-sm-3">
            <?= $form->field($model, 'devengamiento_automatico')->dropDownList(['0'=>'NO','1'=>'SI'] ,['prompt'=>'Seleccione..']) ?>
        </div> 
    </div>

    <div class="form-group">
        <?= Html::submitButton("<i class='fa fa-save'></i> GUARDAR", ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary','id'=>'btn-envio']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<style type="text/css">
   #form-servicioofrecido .form-group {
    margin-bottom: 6px;
}
</style>
<?php
$this->registerJs('
$(document).ready(function(){
    $("#form-servicios").on("beforeValidate",function(e){
        $("#btn-envio").attr("disabled","disabled");
        $("#btn-envio").html("<i class=\'fa fa-spinner fa-spin\'></i> Procesando...");        
    });
    
    $("#form-servicios").on("afterValidate",function(e, messages){
        if ( $("#form-servicios").find(".has-error").length > 0){
            $("#btn-envio").removeAttr("disabled");
            $("#btn-envio").html("<i class=\'fa fa-save\'></i> Guardar...");
        }
    });
});         
', \yii\web\View::POS_READY,'js-preventsubmit');
?>