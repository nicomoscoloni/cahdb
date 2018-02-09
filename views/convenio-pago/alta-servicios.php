<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;

use app\assets\ConvenioPagoAssets;
ConvenioPagoAssets::register($this);


/* @var $this yii\web\View */
/* @var $model app\models\Abogado */

$this->title = 'Alta Convenio Pago';
?>
<div class="box box-solid box-colegio"> 
        <div class="box-header with-border">        
           <i class="fa fa-handshake-o fa-2"> </i> <h3 class="box-title"> Seleccion Servicios C.P   </h3> 
        </div>
        <div class="box-body">
            <div class="row"> <!-- row dettales delconvenio -->
                <div class="col-sm-8 col-sm-offset-2 col-xs-12">
                    <table>
                        <tr>
                            <td width="30%"> 
                                <img class="img-responsive" src="<?php echo Yii::getAlias('@web') . "/images/family.png"; ?>" alt="familia" />  
                            </td>
                            <td width="60%">
                                <h3 class="text-light-blue bold">    Familia / Folio: <?php echo $modelFamilia->folio; ?> </h3>
                                <span class="text-light-blue bold">  Apellido/s: </span> <?php echo $modelFamilia->apellidos; ?> <br />
                                <span class="text-light-blue bold">  Descripción: </span> <?php echo $modelFamilia->descripcion; ?> <br />
                             </td>
                        </tr>
                    </table>  
                </div>
            </div>
            
            <?php $form = yii\widgets\ActiveForm::begin(['id'=>'form-servicios']); ?>
            
            <?php   \yii\widgets\Pjax::begin([
                                'id'=>'pjax-servicios-convenio', 
                                'timeout' => false, 
                                'enablePushState' => false,
                                'clientOptions' => ['method' => 'POST']]);?>
                <?= GridView::widget([
                'id'=>'gridServiviosCP',
                'dataProvider' => $serviciosImpagos,                
                'columns' => [
                    ['class' => 'yii\grid\CheckboxColumn', 
                     'header'=>'',
                     'checkboxOptions' => function($model, $key, $index, $column) {                        
                        if(in_array($model->id, Yii::$app->session->get('srvpagar')))
                            return ['checked' => true];
                        else
                            return ['checked' => false];
                    } 
                    ],
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
            ]); 
                         \yii\widgets\Pjax::end();
                        ?>
            <input type="hidden" name="envios" id="envios" value="0" />
            <input type="hidden" name="noselects" id="noselects" value="" />
            <input type="hidden" name="selects" id="selects" value="" />
            
            <div class="form-group">
                <?= Html::submitButton('<i class=\'fa fa-save\'></i> Generar Convenio', ['class' => 'btn btn-success','id'=>'btn-generar-convenio']) ?>
            </div>  
            <?php yii\widgets\ActiveForm::end(); ?>

        </div>
</div>
<style type="text/css">
.btn.btn-box-tool > a.pdfServ{color: #ad0808 !important;}
.button-column > a.pdfServ{color: #ad0808 !important;}
.datAbogado{font-size: 14px; font-weight: bold; }
</style>