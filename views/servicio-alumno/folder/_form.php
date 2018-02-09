<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ServicioAlumno */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="servicio-alumno-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'id_servicio')->textInput() ?>

    <?= $form->field($model, 'id_alumno')->textInput() ?>

    <?= $form->field($model, 'fecha_otorgamiento')->textInput() ?>

    <?= $form->field($model, 'fecha_cancelamiento')->textInput() ?>

    <?= $form->field($model, 'importe_servicio')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'importe_descuento')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'importe_abonado')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'estado')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
