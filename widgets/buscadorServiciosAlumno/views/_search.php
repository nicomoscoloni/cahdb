<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\search\ServicioAlumnoSearch */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="box  box-solid box-primary">
    <div class="box-header with-border">
        <i class="glyphicon glyphicon-search"></i> <h3 class="box-title"> Busqueda Avanzada </h3>    
    </div>
    <div class="box-body">

            <?php $form = ActiveForm::begin([                            
                            'method' => 'POST',
                        ]); 
            ?>
    
        <div class="row">
            <div class="col-sm-3">
                <?= $form->field($model, 'apellidoFamilia'); ?>
            </div>
            <div class="col-sm-2">
                <?= $form->field($model, 'folioFamilia'); ?>
            </div>
        </div>
           
           
        <div class="row">
            <div class="col-sm-3">
                <?= $form->field($model, 'documentoAlumno'); ?>
            </div>
            <div class="col-sm-3">
                <?= $form->field($model, 'apellidoAlumno'); ?>
            </div>
            <div class="col-sm-3">
                <?= $form->field($model, 'nombreAlumno'); ?>
            </div>
        </div>
    
        <div class="row">
            <?php   
                $divisiones = array();
            ?>
            <div class="col-sm-3">
                <?= $form->field($model, 'establecimiento')->dropDownList(app\models\Establecimiento::getEstablecimientos(),
                        [
                        'prompt'=>'Seleccione',
                        'onchange'=>'
                            $.get( "'. \yii\helpers\Url::toRoute('establecimiento/mis-divisionesescolares').'", { idEst: $(this).val() } )
                                        .done(function( data )
                                        {

                                            $("#servicioalumnosearch-division_escolar").html(data);
                                        });'
                        ]);
                ?>
            </div>

            <div class="col-sm-3">
                <?= $form->field($model, 'division_escolar')->dropDownList($divisiones,['prompt'=>'Seleccione Estab']) ?>
            </div>
        </div> 
        
        <div class="row">
            
           
                <?= Html::submitButton('Buscar', ['class' => 'btn btn-primary']) ?>
            
        </div>

        <?php ActiveForm::end(); ?>
        
    </div>
</div>
