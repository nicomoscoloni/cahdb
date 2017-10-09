<?php
use common\models\TipoDocumento;
use common\models\TipoSexo;

use yii\helpers\Html;
use kartik\form\ActiveForm;
use kartik\widgets\ActiveField;
use kartik\widgets\DatePicker;

/* @var $this yii\web\View */
/* @var $model common\models\Persona */
/* @var $form yii\widgets\ActiveForm */
?>

<?php 
if($model->isNewRecord)
    $url =   \yii\helpers\Url::to(['grupo-familiar/carga-responsable','idFamilia'=>$modelResponsable->id_grupofamiliar]);
else
    $url =   \yii\helpers\Url::to(['grupo-familiar/actualizar-responsable','id'=>$modelResponsable->id]);

    
$form = ActiveForm::begin([
                            'id'=>'form-persona',
                            'action'=> $url,
                            'options' => ['class' => 'form-carga-responsable']
                            ]); ?>
    
<div class="box box-success">     
    <div class="box-body scrollbox">
          
        <input type="hidden" name="idFamilia" id="idFamilia" value="<?= $modelResponsable->id_grupofamiliar; ?>" /> 
   
        <div class="row">
            <div class="col-sm-3 col-xs-4">
                <?= $form->field($modelResponsable, 'tipo_responsable')->dropDownList(\app\models\TipoResponsable::getTipoResponsables(),['prompt'=>'Seleccione'])  ?>
            </div>
        </div>
        
        <?= app\widgets\formulariopersona\FormularioPersona::widget(['model' => $model]); ?>


        <div class="form-group group-invisible">
            <?= Html::submitButton($model->isNewRecord ? 'Guardar' : 'Actualizar', ['id' => 'btn-enviar','class'=>'btn btn-primary']) ?>
        </div>
       
    </div>   
</div>
<?php ActiveForm::end(); ?>