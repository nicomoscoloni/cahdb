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
       
            <?= $form->field($model, 'importe',['addon' => ['prepend' => ['content'=>'<i class="glyphicon glyphicon-phone"></i>']]]) ?>
       
    
 
            <?= $form->field($model, 'importe_hijoprofesor',['addon' => ['prepend' => ['content'=>'<i class="glyphicon glyphicon-phone"></i>']]])->label('Imp. HP') ?>
       
    
    <div class="row">
       
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
                    )->label('a');?>
        </div>
    </div>
    
    <div class="row">
        <div class="col-sm-3">
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
        </div>
        
        <div class="col-sm-3">
            <?= $form->field($model, 'devengamiento_automatico')->dropDownList(['0'=>'NO','1'=>'SI'] ,['prompt'=>'Seleccione..']) ?>
        </div> 
    </div>

    <div class="form-group">
        <?= Html::submitButton("<i class='fa fa-save'></i> GUARDAR", ['class' => $model->isNewRecord ? 'btn btn-success btn-envio' : 'btn btn-primary btn-envio','id'=>'btn-envio']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<style type="text/css">
   #form-servicioofrecido .form-group {
    margin-bottom: 6px;
}
</style>