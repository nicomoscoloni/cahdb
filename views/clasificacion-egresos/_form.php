<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ClasificacionEgresos */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="clasificacion-egresos-form">

    <?php $form = ActiveForm::begin([
         'options' => ['class' => 'form-ajax-crud']
    ]); ?>

    <?= $form->field($model, 'descripcion')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Guardar' : 'Actualizar', ['class' => 'btn btn-success btn-enviar invisible']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
