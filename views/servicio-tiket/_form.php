<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ServicioTiket */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="servicio-tiket-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'id_tiket')->textInput() ?>

    <?= $form->field($model, 'id_servicio')->textInput() ?>

    <?= $form->field($model, 'tipo_servicio')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'importe')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
