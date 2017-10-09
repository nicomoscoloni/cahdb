<?php
use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\date\DatePicker;
/* @var $this yii\web\View */
/* @var $model app\models\ConvenioPago */
/* @var $form yii\widgets\ActiveForm */

$this->title = 'Alta Convenio Pago';
?>
<div class="box box-solid box-colegio">
    <div class="box-header with-border">
        <i class="fa fa-handshake-o fa-2"></i><h3 class="box-title"> Alta Convenio </h3>
    </div>
    <div class="box-body">    

    <div class="convenio-pago-form">
        <?php $form = ActiveForm::begin(['id'=>'form-convenio','type' => ActiveForm::TYPE_HORIZONTAL]); ?>
        <input type="hidden" name="ordn" id="ordn" value="<?php echo count($modelCuotasConvenioPago); ?>" />
        
        <div class="row">
            <div  class="col-md-8 col-md-offset-2">
                <div class="row">                    
                        <?= $form->field($modelConvenionPago, 'nombre')->textInput() ?>                   
                </div>
                <div class="row">
                   
                        <?= $form->field($modelConvenionPago, 'xfecha_alta')->widget(
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
                <div class="row">                    
                        <?= $form->field($modelConvenionPago, 'saldo_pagar')->textInput() ?>                   
                </div>
                <div class="row">                   
                        <?= $form->field($modelConvenionPago, 'deb_automatico')->dropDownList(['0'=>'NO','1'=>'SI'],
                             ['prompt'=>'Seleccione...']); ?>                    
                </div>
                <?php if($modelConvenionPago->con_servicios=='0'){?>
                <div class="row">                   
                        <?= $form->field($modelConvenionPago, 'descripcion')->textarea() ?>                    
                </div>
                <?php } ?>
            </div>
        </div>
        
        <hr />
        
        <div class="row" id="groupCuotas">
            <div  class="col-md-8 col-md-offset-2">
                <?= Html::button('<span class="glyphicon glyphicon-plus"></span> Agregar Cuota',
                                ['class' => 'btn btn-info btn-xs','id'=>'btn-add-cuota',
                                 'onclick'=>'js:{addCuota(\''. yii\helpers\Url::to(['convenio-pago/add-cuota']).'\');}']); ?>  
            </div>            
            <div class="col-md-8 col-md-offset-2" id="misCuotas">
                <?php
                    if(!empty($modelCuotasConvenioPago)){
                        foreach ($modelCuotasConvenioPago as $key => $cuota){                          
                            echo $this->render('_formCuota',['model'=>$cuota, 'ordn'=>$key]);
                        }
                    }
                ?>
            </div>     
        </div> 
        <br />
        <div class="row">
            <div  class="col-md-8 col-md-offset-2">
                <?= Html::submitButton('<i class=\'fa fa-save\'></i> Generar', ['id'=>'btn-envio','class' =>  'btn btn-success']) ?>
            </div>
        </div>
        
        
        <?php ActiveForm::end(); ?>

    </div>
    <br />
    <?php if (!empty($dataProvider)){?>
    <div class="row">
        <div class="col-sm-8 col-sm-offset-2">
            <?= \yii\grid\GridView::widget([
                'id'=>'gridServiviosCP',
                'dataProvider' => $dataProvider,
                'columns' => [
                    [
                        'label' => 'Servicio',
                        'attribute'=>'id_servicio',
                        'value' => function($model) {
                            return $model->datosMiServicio;
                        },
                    ],  
                    [
                        'label' => 'Alumno',
                        'attribute'=>'id_alumno',
                        'value' => function($model) {
                            return $model->datosMiAlumno;
                        },
                    ],          
                    'importe_servicio',
                    'importe_descuento',
                    'importe_abonado', 
                                'importeAbonar', 
                ],
            ]); ?>       
        </div>
    </div>
    <?php } ?>
    </div>
</div>


<script type="text/javascript">
function addCuota(url){     
    var ordn = parseInt($('#ordn').val()) + 1;    
   
    $.ajax({
        'url': url,
        'dataType': 'json',
        'type': 'POST',
        'data': 'nro='+ordn,
        'beforeSend': function(xhr){

        },
        'success':function(data){            
            dataCuota = data.vista;
            $('#misCuotas').prepend(dataCuota);
            $('#ordn').val(ordn);       
        },
    });   
}

function eliminarcuota(nrocuota){
    if($('.groupcuota').length>1){
        $('#divcuota-'+nrocuota).remove();    
    }else{
                            new PNotify({
                                title: 'Error',
                                text: 'No se puede eliminar la cuota, al menos debe existir una de ellas',
                                icon: 'glyphicon glyphicon-envelope',
                                type: 'error'
                            });
    }
    
}

</script>
<?php
$this->registerJs("
$(document).ready(function(){
    $('#form-convenio').on('beforeValidate',function(e){
        $('#btn-envio').attr('disabled','disabled');
        $('#btn-envio').html('<i class=\'fa fa-spinner fa-spin\'></i> Procesando...');        
    });
    $('#form-convenio').on('afterValidate',function(e, messages){
        if ($('#form-convenio').find('.has-error').length > 0){
            $('#btn-envio').removeAttr('disabled');
            $('#btn-envio').html('<i class=\'fa fa-save\'></i> Guardar...');
        }
    });    
});         
", \yii\web\View::POS_READY,'preventSubmitForm');
?>