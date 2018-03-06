<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;
/* @var $this yii\web\View */
/* @var $model app\models\DebitoAutomatico */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="debito-automatico-form">

    <?php $form = ActiveForm::begin(['id'=>'form-debitoautomatico']); ?>
    <div class="row">
        <div class="col-sm-6">
            <?= $form->field($model, 'nombre')->textInput(['maxlength' => true]) ?>
        </div>
    </div>
        
    <div class="row">
        <div class="col-sm-6">
            <?= $form->field($model, 'tipo_archivo')->dropDownList(['CBU'=>'Debitos x CBU','TC'=>'Debitos x T.Credito'],['prompt'=>'Seleccione..']); ?>
        </div>
    </div>
    
    <div class="row">
        <div class="col-sm-3">
            <?= $form->field($model, 'xfecha_debito')->widget(
                    DatePicker::className(),([
                                        'language'=>'es',
                                        
                                        'pluginOptions' => [
                                            'autoclose'=>true,
                                            'format' => 'dd-mm-yyyy'
                                        ]
                                    ]));?>
        </div>
    </div>
    
    <div class="row" id='periodo'>
        <div class="col-sm-3">
            <?= $form->field($model, 'xinicio_periodo')->widget(
                    DatePicker::className(),([
                                        'language'=>'es',
                                        
                                        'pluginOptions' => [
                                            'autoclose'=>true,
                                            'format' => 'dd-mm-yyyy'
                                        ]
                                    ]));?>
        </div>
        <div class="col-sm-3">
            <?= $form->field($model, 'xfin_periodo')->widget(
                    DatePicker::className(),([
                                        'language'=>'es',
                                        'type' => DatePicker::TYPE_INPUT,
                                        'pluginOptions' => [
                                            'autoclose'=>true,
                                            'format' => 'dd-mm-yyyy'
                                        ]
                                    ]));?>
        </div>
    </div>

   
    <div class="form-group">
        <?= Html::submitButton('<i class=\'fa fa-save\'></i> Generar', 
            ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary','id'=>'btn-envio']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<?php
$this->registerJs("
$(document).ready(function(){
    $('#form-debitoautomatico').on('beforeValidate',function(e){
        $('#btn-envio').attr('disabled','disabled');
        $('#btn-envio').html('<i class=\'fa fa-spinner fa-spin\'></i> Procesando...');        
    });
    $('#form-debitoautomatico').on('afterValidate',function(e, messages){
        if ($('#form-debitoautomatico').find('.has-error').length > 0){
            $('#btn-envio').removeAttr('disabled');
            $('#btn-envio').html('<i class=\'fa fa-save\'></i> Guardar...');
        }
    });
    
});         
", \yii\web\View::POS_READY,'preventSubmitForm');
?>
<?php
$this->registerJs("      
function ayuda(){         
    var intro = introJs();
      intro.setOptions({
        nextLabel: 'Siguiente',
        prevLabel: 'Anterior',
        skipLabel:'Terminar',
        doneLabel:'Cerrar',
        steps: [      
            { 
                intro: 'Formulario para la generación de un nuevo Debito Automático.'
            },  
            {
                element: document.querySelector('#debitoautomatico-nombre'),
                intro: 'indique un nombre para identificar al debito. Recomendación NOMBRE DEL MES.'
            }, 
            {
                element: document.querySelector('#debitoautomatico-tipo_archivo'),
                intro: 'Indique si viaja por CBU o TC.'
            },
            {
                element: document.querySelector('#debitoautomatico-xfecha_debito'),
                intro: 'Fecha del vencimiento del debito - Fecha final del barrido.'
            },   
            {
                element: document.querySelector('#periodo'),
                intro: 'Especifique el rango del periodo para barrer con los servicos adherir al debito.'
            }
        ]
      });
      intro.start();
}      
", \yii\web\View::POS_END,'ayuda');
?>