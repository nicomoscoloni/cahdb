<?php

use yii\helpers\Html;
use kartik\form\ActiveForm;
use kartik\date\DatePicker;
use kartik\form\ActiveField;

/* @var $this yii\web\View */
/* @var $model app\models\Abogado */
/* @var $form yii\widgets\ActiveForm */

$this->title = 'Caja - Cobro de Ingresos';
?>

<div class="box box-solid box-colegio">
    <div class="box-header with-border">
        
        <i class="fa fa-dollar"> </i> <h3 class="box-title"> COBRO INGRESOS </h3>
    </div>
    <div class="box-body">
        <div class="form">
            
            <?php $form = ActiveForm::begin(['id'=>'form-ingresos']); ?>

                    <div class="row">        
                        <div class="col-sm-4">
                            <?= $form->field($modelTiket, 'cuentapagadora',['addon' => ['prepend' => ['content'=>'<i class="glyphicon glyphicon-briefcase"></i>']]])->dropDownList(\app\models\Cuentas::getDropMisCuentas(),['readonly'=>'readonly']);
                            ?> 
                        </div>     
                    </div>
                    <div class="row">        
                        <div class="col-sm-4">
                            <?= $form->field($modelTiket, 'id_formapago',['addon' => ['prepend' => ['content'=>'<i class="glyphicon glyphicon-usd"></i>']]])->dropDownList(\app\models\FormaPago::getFormasPago(),
                                 ['prompt'=>'Seleccione...']);
                            ?> 
                        </div>     
                    </div>
            
            
            <div class="row">        
                <div class="col-sm-3">
                    <?= $form->field($modelTiket, 'xfecha_tiket')->widget(
                    DatePicker::className(),([
                                        'language'=>'es',
                                        'removeButton' => false,
                                        'pluginOptions' => [
                                            'autoclose'=>true,
                                            'format' => 'dd-mm-yyyy',
                                            'endDate' => date('d/m/Y'),
                                        ]
                                    ]));?>
                </div>     
            </div>
            
            <div class="row">        
                <div class="col-sm-3">
                    <?= $form->field($modelTiket, 'importe',['addon' => ['prepend' => ['content'=>'<i class="glyphicon glyphicon-usd"></i>']]]);?>
                </div>     
            </div>
            
            <div class="row">        
                <div class="col-sm-6">
                    <?= $form->field($modelTiket, 'detalles')->textarea();?>
                </div>     
            </div>    
            
            <div class="form-group">
                <?= Html::submitButton('<i class=\'fa fa-save\'></i> Aceptar Cobro', 
                    ['class' => 'btn btn-primary','id'=>'btn-envio']) ?>
            </div>             

            <?php ActiveForm::end(); ?>

        </div><!-- form -->
    </div>
</div>

<?php
$this->registerJs("
$(document).ready(function(){
    $('#tiket-id_formapago').on('change',function() {        
        
        if ($(this).val() == '1'){           
            $('#tiket-cuentapagadora').val('1');
        }else{           
            $('#tiket-cuentapagadora').prop('selectedIndex','1')
            $('#tiket-cuentapagadora').val('2');
        }
    });
    
    $('#tiket-cuentapagadora').on('change',function() {
        $('#divcheque').css('display','none');

        if ( ($(this).val() == '1') || ($(this).val() == '2') ){
            $('#tiket-tipo_pago').val('1');
        }
    });


    $('#form-ingresos').on('beforeValidate',function(e){
        $('#btn-envio').attr('disabled','disabled');
        $('#btn-envio').html('<i class=\'fa fa-spinner fa-spin\'></i> Procesando...');        
    });
    
    $('#form-ingresos').on('afterValidate',function(e, messages){
        if ($('#form-ingresos').find('.has-error').length > 0){
            $('#btn-envio').removeAttr('disabled');
            $('#btn-envio').html('<i class=\'fa fa-save\'></i> Guardar...');
        }
    });
    
});         
", \yii\web\View::POS_READY,'preventSubmitForm');
?>