<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\EgresoFondoFijo */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="egreso-fondo-fijo-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'id_fondofijo')->textInput() ?>

    <?= $form->field($model, 'id_clasificacionegreso')->textInput() ?>

    <?= $form->field($model, 'fecha_compra')->textInput() ?>

    <?= $form->field($model, 'proovedor')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'descripcion')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'importe')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'nro_factura')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'nro_rendicion')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'bien_uso')->checkbox() ?>

    <?= $form->field($model, 'rendido')->checkbox() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
