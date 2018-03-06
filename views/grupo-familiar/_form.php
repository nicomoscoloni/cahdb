<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;


/* @var $this yii\web\View */
/* @var $model app\models\GrupoFamiliar */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="grupo-familiar-form">

    <?php $form = ActiveForm::begin(['id'=>'form-grupofamiliar']); ?>

    <div class="row">
        <div class="col-sm-7">
            <?= $form->field($model, 'apellidos') ?>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-7">
            <?= $form->field($model, 'descripcion') ?>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-3">
            <?= $form->field($model, 'folio') ?>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-6">
            <?= $form->field($model, 'id_pago_asociado')->dropDownList(\app\models\FormaPago::getFormasPago(),['prompt'=>'Select...']) ?>
        </div>
    </div>
    
    <div class="row">
        <div class="col-sm-6">
                    <?php 
            if($model->id_pago_asociado==4)
                echo $form->field($model, 'cbu_cuenta')->textInput();
            else
                echo $form->field($model, 'cbu_cuenta')->textInput(['readonly' => true]);?>

          
        </div>
    </div>

    <div class="row">
        <div class="col-sm-3">
              <?php 
            if($model->id_pago_asociado==5)
                echo $form->field($model, 'nro_tarjetacredito')->textInput();
            else
                echo $form->field($model, 'nro_tarjetacredito')->textInput(['readonly' => true]);?>
           
        </div>
        <div class="col-sm-3">
              <?php 
            if($model->id_pago_asociado==5)
                echo $form->field($model, 'tarjeta_banco')->textInput();
            else
                echo $form->field($model, 'tarjeta_banco')->textInput(['readonly' => true]);?>
           
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton("<i class='fa fa-save'></i> GUARDAR", ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary','id'=>'btn-envio']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
<?php 
    $this->registerJsFile('@web/js/grupoFamiliar.js', ['depends'=>[app\assets\AppAsset::className()]]);
?>

<script type="text/javascript">
function ayuda(){         
    var intro = introJs();
      intro.setOptions({
        nextLabel: 'Siguiente',
        prevLabel: 'Anterior',
        skipLabel:'Terminar',
        doneLabel:'Cerrar',
        steps: [      
            { 
                intro: "Formulario Alta de Grupo Familiar."
            },  
                       
            {
                element: document.querySelector('#grupofamiliar-apellidos'),
                intro: "Apellido del grupo familiar."
            },
            {
                element: document.querySelector('#grupofamiliar-folio'),
                intro: "Nro de folio."
            },
            {
                element: document.querySelector('#grupofamiliar-id_pago_asociado'),
                intro: "Indique el pago asosiado, segun el mismo debe completar el CBU o NRO de TC."
            },           
            {
                element: document.querySelector('#btn-envio'),
                intro: "Presione para confirmar el alta."
            },
        ]
      });
      intro.start();
}      
</script>