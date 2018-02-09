<?php

use yii\helpers\Html;
use kartik\form\ActiveForm;
use kartik\date\DatePicker;
use yii\widgets\Pjax;
use yii\grid\GridView;

$this->title = 'Cobro de Servicios';

/* @var $this yii\web\View */
/* @var $model app\models\Abogado */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="box box-solid box-colegio">
    <div class="box-header">
        <i class="fa fa-dollar"> </i> <h3 class="box-title"> Cobro Servicios </h3>

    </div>
    <div class="box-body">
        <div class="form">
            
            <div class="row">
                <div class="col-sm-12">
                    <?php $form = ActiveForm::begin(['id'=>'form-servicios']); ?>  
                    
                    <div class="row">        
                        <div class="col-sm-4">
                            <?= $form->field($modelTiket, 'cuentapagadora',['addon' => ['prepend' => ['content'=>'<i class="glyphicon glyphicon-briefcase"></i>']]])->dropDownList(\app\models\Cuentas::getDropMisCuentas());
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
                        <div class="col-sm-4">
                            <?= $form->field($modelTiket, 'pagototal',['addon' => ['prepend' => ['content'=>'<i class="glyphicon glyphicon-briefcase"></i>']]])->dropDownList(['0'=>'NO','1'=>'SI']);
                            ?> 
                        </div>     
                    </div>
                    
                    <div class="row">        
                        <div class="col-sm-3">
                            <?= $form->field($modelTiket, 'importeservicios',['addon' => ['prepend' => ['content'=>'<i class="glyphicon glyphicon-usd"></i>']]])->textInput(['readonly'=>true]);?>
                        </div>     
                    </div>
                    
                    <div class="row">        
                        <div class="col-sm-3">
                            <?= $form->field($modelTiket, 'montoabonado',['addon' => ['prepend' => ['content'=>'<i class="glyphicon glyphicon-usd"></i>']]])->textInput();?>
                        </div>     
                    </div>
                    
                    <div class="form-group">
                        <?= Html::submitButton('<i class=\'fa fa-save\'></i> Aceptar Cobro', 
                            ['class' => 'btn btn-primary','id'=>'btn-envio']) ?>
                    </div>
                    
                </div>
            </div>
            <?= Html::error($modelTiket, 'cantidadservicios'); ?>
            <div class="row">
                <div class="col-sm-12">
                    <?php
                    Pjax::begin();     
                    echo GridView::widget([
                        'id'=>'servicios',
                        'dataProvider' => $serviciosImpagos,                
                        'columns' => [   
                            ['class' => 'yii\grid\CheckboxColumn',
                            'cssClass'=>'checkservicios',                            
                            'checkboxOptions' => function($model, $key, $index, $column) {                        
                                if($model['tiposervicio']=='CUOTAS'){
                                    if(in_array("CCP-".$model['nroservicio'], \Yii::$app->session->get('srvpagar')))
                                        return ["value" =>"CCP-".$model['nroservicio'], 'checked' => true];
                                    else
                                        return ["value" =>"CCP-".$model['nroservicio'], 'checked' => false];
                                }
                                if($model['tiposervicio']=='SERVICIOS'){
                                    if(in_array("SER-".$model['nroservicio'], \Yii::$app->session->get('srvpagar')))
                                        return ["value" =>"SER-".$model['nroservicio'], 'checked' => true];
                                    else
                                        return ["value" =>"SER-".$model['nroservicio'], 'checked' => false];
                                }
                            },
                            'header'=>''],
                            [
                                'label' => 'TIPO SERVICIO',  
                                'attribute'=>'tiposervicio',
                                'value' => function($model) {
                                    return $model['tiposervicio'];
                                },
                            ],        
                            [
                                'label' => 'Alumno',                       
                                'value' => function($model) {
                                    if($model['tiposervicio']=='CUOTAS'){
                                        return "Familia: " . \app\models\GrupoFamiliar::getDatosUnaFamilia($model['idalumno']);
                                    }    
                                    if($model['tiposervicio']=='SERVICIOS'){
                                        return "ALUMNO: " . \app\models\Alumno::getDatosUnAlumno($model['idalumno']);
                                    }

                                },
                            ], 
                            [
                                'label' => 'Servicio',                       
                                'value' => function($model) {
                                    if($model['tiposervicio']=='CUOTAS'){
                                        return \app\models\CuotaConvenioPago::getDetalleDatos($model['nroservicio']);
                                    }    
                                    if($model['tiposervicio']=='SERVICIOS'){
                                        return \app\models\ServicioAlumno::getDetalleDatos($model['nroservicio']);
                                    }

                                },
                            ],            
                            
                            'montoservicio',
                            'importe_descuento',
                            'importe_abonado',
                            [
                                'label' => 'Importe Pendiente',                                
                                'value' => function($model) {
                                    return ($model['montoservicio'] - $model['importe_abonado']);
                                },
                            ],            
                                
                        ],
                    ]); ?>
                    <?php Pjax::end(); ?> 
                    <?php ActiveForm::end(); ?>
                </div>
            </div>    
        </div>
    </div>
</div>

<?php
$this->registerJs("
$(document).ready(function(){    
    
    $('.checkservicios').change(function(event){    
        montoservicio = parseFloat($(this).parent().parent().children().last().text()); 
        montototal = parseFloat($('#tiket-importeservicios').val());

        if (this.checked){
            montototal = montototal + montoservicio;
            $('#tiket-importeservicios').val(montototal);
        } else {
            montototal = montototal - montoservicio;
            $('#tiket-importeservicios').val(montototal);
        }
    });
    
    $('#form-servicios').on('beforeSubmit',function(e, messages){        
        $('#btn-envio').attr('disabled','disabled');
        $('#btn-envio').html('<i class=\'fa fa-spinner fa-spin\'></i> Procesando...');

        var importe_servicios = parseFloat($('#tiket-importeservicios').val());
        var importe_abonado = parseFloat($('#tiket-montoabonado').val());

        if ( ($('#tiket-pagototal').val()=='0') && (importe_servicios != importe_abonado) && ($('#servicios').yiiGridView('getSelectedRows').length > 1) ){
            $('#btn-envio').removeAttr('disabled');
            $('#btn-envio').html('<i class=\'fa fa-save\'></i> Aceptar Cobro');
            e.preventDefault();
            new PNotify({
                                title: 'Error',
                                text: 'Solo se permite el pago parcial de un unico servicio',
                                icon: 'glyphicon glyphicon-envelope',
                                type: 'error'
                            });
            return false;      
        }
        
    });    
});         
", \yii\web\View::POS_READY,'preventSubmitForm');
?>