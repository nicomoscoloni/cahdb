<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\search\EgresoFondoFijoSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="egreso-fondo-fijo-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'id_fondofijo') ?>

    <?= $form->field($model, 'id_clasificacionegreso') ?>

    <?= $form->field($model, 'fecha_compra') ?>

    <?= $form->field($model, 'proovedor') ?>

    <?php // echo $form->field($model, 'descripcion') ?>

    <?php // echo $form->field($model, 'importe') ?>

    <?php // echo $form->field($model, 'nro_factura') ?>

    <?php // echo $form->field($model, 'nro_rendicion') ?>

    <?php // echo $form->field($model, 'bien_uso')->checkbox() ?>

    <?php // echo $form->field($model, 'rendido')->checkbox() ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
