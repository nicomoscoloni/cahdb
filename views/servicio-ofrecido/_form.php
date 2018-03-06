<?php

use yii\helpers\Html;
//use yii\widgets\ActiveForm;
use kartik\widgets\ActiveForm;
use kartik\widgets\DatePicker;

/* @var $this yii\web\View */
/* @var $model app\models\ServicioOfrecido */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="servicio-ofrecido-form">
    <?php $form = ActiveForm::begin([
        'id'=>'form-servicioofrecido',
        'type' => ActiveForm::TYPE_HORIZONTAL,
        'formConfig' => ['deviceSize' => ActiveForm::SIZE_SMALL],
        'options' => [
                'class' => 'form-prev-submit'
            ]
    ]); ?>
   
    
    <?= $form->field($model, 'id_tiposervicio')->dropDownList(app\models\CategoriaServicioOfrecido::getTipoServicios() ,['prompt'=>'Seleccione..']) ?>

    <?= $form->field($model, 'nombre') ?>

    <?= $form->field($model, 'descripcion') ?>

    <?= $form->field($model, 'importe',['addon' => ['prepend' => ['content'=>'<i class="glyphicon glyphicon-usd"></i>']]]) ?>

    <?= $form->field($model, 'importe_hijoprofesor',['addon' => ['prepend' => ['content'=>'<i class="glyphicon glyphicon-usd"></i>']]])->label('Imp. HP') ?>

    <?= $form->field($model, 'xfecha_inicio')->widget(
            DatePicker::className(),([
                                'language'=>'es',
                                'type' => DatePicker::TYPE_INPUT,
                                'pluginOptions' => [
                                    'autoclose'=>true,
                                    'format' => 'dd-mm-yyyy'
                                ]
                            ])
            );?>

    <?= $form->field($model, 'xfecha_fin')->widget(
            DatePicker::className(),([
                                'language'=>'es',
                                'type' => DatePicker::TYPE_INPUT,
                                'pluginOptions' => [
                                    'autoclose'=>true,
                                    'format' => 'dd-mm-yyyy'
                                ]
                            ])
            ); ?>

    <?= $form->field($model, 'xfecha_vencimiento')->widget(
            DatePicker::className(),([
                                'language'=>'es',
                                'type' => DatePicker::TYPE_INPUT,
                                'pluginOptions' => [
                                    'autoclose'=>true,
                                    'format' => 'dd-mm-yyyy'
                                ]
                            ])
            );?>
    
            <?= $form->field($model, 'devengamiento_automatico')->dropDownList([0=>'No',1=>'Si'] ,['prompt'=>'Seleccione..',
                'options'=>[$model->xdevengamiento_automatico=>['Selected'=>true]]]) ?>
        
     
    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10 ">
            <?= Html::submitButton('<i class="fa fa-save"></i> Guardar', ['class' => 'btn btn-primary btn-flat btn-block','id'=>'btn-envio']) ?>
        </div>
    </div>    
       

    <?php ActiveForm::end(); ?>

</div>

<style type="text/css">
   #form-servicioofrecido .form-group {
    margin-bottom: 6px;
}
</style>
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
                intro: 'Formulario para el Alta/Edición servicio.'
            },  
            {
                element: document.querySelector('#servicioofrecido-id_tiposervicio'),
                intro: 'Seleccione la categoria del Servicio.'
            }, 
            {
                element: document.querySelector('#servicioofrecido-nombre'),
                intro: 'Nombre del servicio a dar de alta.'
            },
            {
                element: document.querySelector('#servicioofrecido-importe'),
                intro: 'Importe del servicio.'
            },            
            {
                element: document.querySelector('#servicioofrecido-importe_hijoprofesor'),
                intro: 'Importe para los hijos de profesores.'
            },             
            {
                element: document.querySelector('#btn-envio'),
                intro: 'Si desea realizar una nueva alta, presione sobre este botón.'
            },
        ]
      });
      intro.start();
}
        
", \yii\web\View::POS_END,'ayuda');
?>
<?php 
    $this->registerJsFile('@web/js/servicio-ofrecido.js', ['depends'=>[app\assets\AppAsset::className()]]);
?>