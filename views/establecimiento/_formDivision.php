<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\DivisionEscolar */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="division-escolar-form">

    <?php $form = ActiveForm::begin([
                    'enableClientValidation'=>true,
                    'options' => ['class' => 'form-ajax-crud']
                    ]); ?>
    <div class="row">
        <div class="col-sm-6">
            <?= $form->field($model, 'nombre')->textInput(['maxlength' => true]) ?>
        </div>
    </div>
    
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Guardar' : 'Actualizar', ['class' => 'btn btn-success btn-enviar invisible']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
