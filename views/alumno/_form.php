<?php

use app\models\Establecimiento;

use yii\helpers\Html;
use kartik\form\ActiveForm;
use kartik\widgets\ActiveField;
use kartik\widgets\DepDrop;
use yii\helpers\Url;
use yii\web\View;
use kartik\widgets\DatePicker;

use app\assets\AlumnoAssets;
AlumnoAssets::register($this);

/* @var $this yii\web\View */
/* @var $model common\models\Alumno */
/* @var $modelPersona common\models\Persona */
/* @var $form yii\widgets\ActiveForm */
?>

<?php
yii\bootstrap\Modal::begin([        
    'id'=>'modalfamilia',
    'class' =>'modal-scrollbar',
    'size'=>'modal-lg',
    ]);
    echo "<div id='modalContent'></div>";
yii\bootstrap\Modal::end();
?>

<div class="alumno-form">

    <?php $form = ActiveForm::begin(
                            [
                            'id'=>'form-empadronamiento',
                            'enableClientValidation'=>true
                            ]); ?>
    
    <div class="row form-group datagrupofamiliar">
        <div class="col-sm-2">
            <?php
                echo Html::button('Buscar Familia', ['value'=> Url::to(['buscarFamilia']), 
                             'class'=>'btn btn-success', 'id'=>'buscarFamiliaBtn']);
            ?>
        </div>
        
        <div class="col-sm-3">        
            <div class="input-group">
                <span class="input-group-addon">Familia</span>
                <?= Html::activeInput('text',$modelGrupoFamiliar, 'apellidos', ['id' => 'apellidoFamilia', 'class' => 'form-control']); ?>
            </div>
        </div>
        <div class="col-sm-2">
            <div class="input-group">
                <span class="input-group-addon">Folio</span>
                <?= Html::activeInput('text', $modelGrupoFamiliar, 'folio', ['id' => 'folioFamilia', 'class' => 'form-control']); ?>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="input-group">
                <span class="input-group-addon">Responsable</span>
                <?= Html::activeInput('text', $modelGrupoFamiliar, 'miResponsableCabecera', ['id' => 'responsableFamilia', 'class' => 'form-control']); ?>
            </div>           
        </div>
        <?php 
            if(!empty($modelGrupoFamiliar->id)) 
                echo   Html::hiddenInput('mifamilia',$modelGrupoFamiliar->id,['id'=>'mifamilia','name'=>'mifamilia']); 
            else
                echo   Html::hiddenInput('mifamilia','0',['id'=>'mifamilia','name'=>'mifamilia']); ?> 

    </div>
    <div class="row form-group">
        <div class="col-sm-12">
            <?=dmstr\helpers\Html::error($model, 'id_grupofamiliar'); ?>
        </div>
    </div>
          
    <?= app\widgets\formulariopersona\FormularioPersona::widget(['model' => $modelPersona]); ?>
   
    <div class="row">
        <div class="col-sm-3">
            <?= $form->field($model, 'nro_legajo') ?>
        </div>
        <div class="col-sm-3">
            <?= $form->field($model, 'xfecha_ingreso')->widget(
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
    </div>
    
    <div class="row"  id="establecimientodivision">
        <?php
        if(!empty($model->id_divisionescolar)){
            $divisiones = \app\models\DivisionEscolar::find()->joinWith('establecimiento e')->where(['=', 'e.id', $model->divisionescolar->id_establecimiento])->asArray()->all();
            $divisiones = yii\helpers\ArrayHelper::map($divisiones, 'id', 'nombre');
           
        }else
            $divisiones = array();
        ?>
        
        <div class="col-sm-3">
            <?= $form->field($model, 'establecimiento')->dropDownList(Establecimiento::getEstablecimientos(),
                    ['class' => '',
                    'prompt'=>'Seleccione',
                    'onchange'=>'
                        $.get( "'.Url::toRoute('establecimiento/drop-mis-divisionesescolares').'", { idEst: $(this).val() } )
                                    .done(function( data )
                                    {                                        
                                        $("#alumno-id_divisionescolar").html(data);
                                    });'
                    ]);
            ?>
        </div>
       
        <div class="col-sm-3">
            <?= $form->field($model, 'id_divisionescolar')->dropDownList($divisiones,['prompt'=>'Seleccione Estab']) ?>
        </div>
    </div>
    
    <div class="row">
        <div class="col-sm-3">
            <?= $form->field($model, 'hijo_profesor')->dropDownList(['0'=>'NO','1'=>'SI'],['prompt'=>'Seleccione Estab']) ?>
        </div>
    </div>
    
    <div class="form-group">
        <?= Html::submitButton("<i class='fa fa-save'></i> Guardar...", ['class' => 'btn btn-primary','id'=>'btn-envio']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>

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
                intro: 'Formulario Alta / Edición Alumno. <br /> Seleccione el grupo familiar, y complete los campos requeridos. '
            },  
            {
                element: document.querySelector('.datagrupofamiliar'),
                intro: 'Seleccione grupo familiar.'
            }, 
            {
                element: document.querySelector('#persona-apellido'),
                intro: 'Complete los datos personales del alumno.'
            },
                        
            
            {
                element: document.querySelector('#alumno-nro_legajo'),
                intro: 'Ingrese el Nro de legajo.'
            },
            {
                element: document.querySelector('#establecimientodivision'),
                intro: 'Seleccione el establecimiento y la división.'
            },
            {
                element: document.querySelector('#alumno-hijo_profesor'),
                intro: 'Indique si el mismo es hijo de profesor o no.'
            },
            
            
        ]
      });
      intro.start();
}
  
", \yii\web\View::POS_END,'ayuda');
?>