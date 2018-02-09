<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\search\DebitoAutomaticoSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="debito-automatico-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'nombre') ?>

    <?= $form->field($model, 'banco') ?>

    <?= $form->field($model, 'tipo_archivo') ?>

    <?= $form->field($model, 'fecha_creacion') ?>

    <?php // echo $form->field($model, 'fecha_procesamiento') ?>

    <?php // echo $form->field($model, 'inicio_periodo') ?>

    <?php // echo $form->field($model, 'fin_periodo') ?>

    <?php // echo $form->field($model, 'fecha_debito') ?>

    <?php // echo $form->field($model, 'procesado')->checkbox() ?>

    <?php // echo $form->field($model, 'registros_enviados') ?>

    <?php // echo $form->field($model, 'registros_correctos') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
