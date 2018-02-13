<?php

use yii\helpers\Html;
//use yii\widgets\ActiveForm;
use kartik\widgets\ActiveForm;
use kartik\widgets\DatePicker;

/* @var $this yii\web\View */
/* @var $model app\models\EgresoFondoFijo */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="egreso-fondo-fijo-form">

    <?php $form = ActiveForm::begin([
        'id'=>'form-egresos-fondofijo',
        'type'=> kartik\form\ActiveForm::TYPE_HORIZONTAL,
        'formConfig'=> ['labelSpan'=>2,]
    ]); ?>

    <?= $form->field($model, 'id_clasificacionegreso')->dropDownList(app\models\ClasificacionEgresos::getClasificacionEgresos(),
            ['class' => '',
            'prompt'=>'Seleccione',            
            ]);
    ?>

    

    <?= $form->field($model, 'xfecha_compra')->widget(
                    DatePicker::className(),([
                                        'language'=>'es',                                        
                                        'pluginOptions' => [
                                            'autoclose'=>true,
                                            'format' => 'dd-mm-yyyy'
                                        ]
                                    ])
                    );?>

    <?= $form->field($model, 'proovedor')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'descripcion')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'importe',['addon' => ['prepend' => ['content'=>'<i class="glyphicon glyphicon-phone"></i>']]]) ?>

    <?= $form->field($model, 'nro_factura')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'bien_uso')->checkbox() ?>

   

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
