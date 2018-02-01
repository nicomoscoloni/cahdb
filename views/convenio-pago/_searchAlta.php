<?php

use kartik\form\ActiveForm;
use yii\helpers\Html;

?>

    <div id="form-cobroservicio" class="row">
        <div class="col-sm-8 col-sm-offset-2">        
            <div class="box box-warning">
                <div class="box-header with-border">
                    <i class="glyphicon glyphicon-search"></i><h3 class="box-title"> Busqueda Grupo Familiar </h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    </div>
                </div>
                <div class="box-body">

                    <?php $form = ActiveForm::begin(['id'=>'form-conveniopago',
                        'type' => ActiveForm::TYPE_HORIZONTAL,
                        'enableClientValidation' => false,
                        'formConfig' => ['labelSpan' => 3, 'deviceSize' => ActiveForm::SIZE_SMALL]]); ?>

                    <div class="row">
                        <div class="col-sm-8">
                            <?= $form->field($modelGrupoFamiliar, 'apellidos',['addon' => ['prepend' => ['content'=>'<i class="glyphicon glyphicon-user"></i>']]]) ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-8">
                            <?= $form->field($modelGrupoFamiliar, 'folio',['addon' => ['prepend' => ['content'=>'<i class="glyphicon glyphicon-user"></i>']]]) ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-8">
                            <?= $form->field($modelGrupoFamiliar, 'responsable',['addon' => ['prepend' => ['content'=>'<i class="glyphicon glyphicon-user"></i>']]]) ?>
                        </div>
                    </div>
                    <div>
                            <?= Html::submitButton('<i class=\'fa fa-search\'></i> Buscar', 
                            ['class' => 'btn btn-primary', 'id'=>'btn-envio']) ?>
                    </div>  

                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>  
