<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\search\GrupoFamiliarSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="grupo-familiar-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'apellidos') ?>

    <?= $form->field($model, 'descripcion') ?>

    <?= $form->field($model, 'folio') ?>

    <?= $form->field($model, 'id_pago_asociado') ?>

    <?php // echo $form->field($model, 'cbu_cuenta') ?>

    <?php // echo $form->field($model, 'nro_tarjetacredito') ?>

    <?php // echo $form->field($model, 'tarjeta_banco') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
