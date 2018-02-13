<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Tiket */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tiket-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'fecha_tiket')->textInput() ?>

    <?= $form->field($model, 'id_formapago')->textInput() ?>

    <?= $form->field($model, 'importe')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'detalles')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'id_cliente')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
