<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\BonificacionFamiliar */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="bonificacion-familiar-form">

    <?php $form = ActiveForm::begin([
        'id'=>'formAsignacionBonificacion',
        'enableClientValidation'=>true,
    ]); ?>

        <?= $form->field($model, 'id_bonificacion')->dropDownList(app\models\CategoriaBonificacion::getDetalleBonificacionesActivasDrop(),
                    ['class' => '',
                    'prompt'=>'Seleccione']); ?>
   
        <?= Html::submitButton('<i class="fa fa-share-square-o"></i> ASIGNAR', ['class' =>'btn btn-success']) ?>    

    <?php ActiveForm::end(); ?>
</div>