<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\search\ServicioAlumnoSearch */
/* @var $form yii\widgets\ActiveForm */
?>

    <div class="box-body" id="seachSA">

            <?php $form = ActiveForm::begin([                            
                            'method' => 'POST',
                        ]); 
            ?>
       
        <div class="row form-group required">
            <div class="col-sm-3">        
                <div class="input-group">
                    <span class="input-group-addon"> <?= Html::activeLabel($model, 'folioFamilia', ['label'=>'Folio', 'class' => 'control-label'])?> </span>
                    <?=  Html::activeInput('text', $model, 'folioFamilia',['class'=>'form-control','aria-required'=>"true"]); ?>
                </div>                
            </div>
            <div class="col-sm-4">        
                <div class="input-group">
                    <span class="input-group-addon"> <?= Html::activeLabel($model, 'apellidoFamilia', ['label'=>'Apellido', 'class' => 'control-label'])?> </span>
                    <?=  Html::activeInput('text', $model, 'apellidoFamilia',['class'=>'form-control','aria-required'=>"true"]); ?>
                </div>                
            </div>            
        </div>
        
        <div class="row form-group required">
            <div class="col-sm-3">        
                <div class="input-group">
                    <span class="input-group-addon"> <?= Html::activeLabel($model, 'documentoAlumno', ['label'=>'Doc', 'class' => 'control-label'])?> </span>
                    <?=  Html::activeInput('text', $model, 'documentoAlumno',['class'=>'form-control','aria-required'=>"true"]); ?>
                </div>                
            </div>
            <div class="col-sm-4">        
                <div class="input-group">
                    <span class="input-group-addon"> <?= Html::activeLabel($model, 'apellidoAlumno', ['label'=>'Apellido', 'class' => 'control-label'])?> </span>
                    <?=  Html::activeInput('text', $model, 'apellidoAlumno',['class'=>'form-control','aria-required'=>"true"]); ?>
                </div>                
            </div>
            <div class="col-sm-4">        
                <div class="input-group">
                    <span class="input-group-addon"> <?= Html::activeLabel($model, 'nombreAlumno', ['label'=>'Nombre', 'class' => 'control-label'])?> </span>
                    <?=  Html::activeInput('text', $model, 'nombreAlumno',['class'=>'form-control','aria-required'=>"true"]); ?>
                </div>                
            </div>
        </div>
        
        <div class="row form-group required">
            <div class="col-sm-4">        
                <div class="input-group">
                    <span class="input-group-addon"> <?= Html::activeLabel($model, 'sexo', ['label'=>'Estb','class' => 'control-label']) ?> </span>
                    <?php echo Html::activeDropDownList($model, 'establecimiento', app\models\Establecimiento::getEstablecimientos(), 
                            ['class'=>'form-control','prompt'=>'Seleccione',
                            'onchange'=>'
                                     $.get( "'. \yii\helpers\Url::toRoute('establecimiento/drop-mis-divisionesescolares').'", { idEst: $(this).val() } )
                                        .done(function( data )
                                        {

                                            $("#servicioalumnosearch-division_escolar").html(data);
                                        });']); ?>
                </div>
                
            </div>
            <div class="col-sm-4">        
                <div class="input-group">
                    <span class="input-group-addon"> <?= Html::activeLabel($model, 'division_escolar', ['label'=>'Division','class' => 'control-label']) ?> </span>
                    <?php echo Html::activeDropDownList($model, 'division_escolar', app\models\Establecimiento::getEstablecimientos(), 
                            ['class'=>'form-control','prompt'=>'Seleccione',
                            ]); ?>
                </div>
               
            </div>
        </div>
        
        <div class="row form-group required">           
            <div class="col-sm-4">        
                <div class="input-group">
                    <span class="input-group-addon"> <?= Html::activeLabel($model, 'estado', ['label'=>'Estado','class' => 'control-label']) ?> </span>
                    <?php echo Html::activeDropDownList($model, 'estado', $filtro_estados, 
                            ['class'=>'form-control', 'prompt'=>'Seleccione']); ?>
                </div>
               
            </div>
        </div>
        
        <div class="row">
            <?= Html::submitButton('Buscar', ['class' => 'btn btn-primary']) ?>
        </div>

        <?php ActiveForm::end(); ?>
        
    </div>
