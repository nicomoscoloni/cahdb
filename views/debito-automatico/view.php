<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\form\ActiveForm;
use yii\helpers\Url;


use app\assets\DebitoAutomaticoAssets;
DebitoAutomaticoAssets::register($this);

/* @var $this yii\web\View */
/* @var $model app\models\DebitoAutomatico */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Debito Automaticos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>


<?php
yii\bootstrap\Modal::begin([        
    'id'=>'modalProcesa',
    'class' =>'modal-scrollbar',
    'size'=>'modal-lg',
    ]);
    echo "<div id='modalContent'>";
    echo "<div id='form-upload'>";
        
            $form = ActiveForm::begin(['id'=>'form-procesamiento',
                'action'=> Url::to(['/debito-automatico/procesar','id'=>$model->id]),
                'options'=>['enctype'=>'multipart/form-data']]); 
            
            echo "<input type='file' id='DebitoAutomatico_archivoentrante' name='DebitoAutomatico[archivoentrante]' size='40' />";
            echo Html::submitButton('<i class=\'fa fa-save\'></i> Guardar', 
                   ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary','id'=>'btn-envio']);
            
            ActiveForm::end(); 
        echo "</div>";
    echo "</div>";
yii\bootstrap\Modal::end();
?>

<div class="box box-colegio">
    <div class="box-header with-border">        
       <i class="fa fa-bell"></i>    <h3 class="box-title"> Detalle Débito Automático </h3> 
    </div>
    <div class="box-body">

      <div class="row">
        <div class="col-sm-8 col-sm-offset-2">
            <table>
                <tr>
                    <td width="25%"> 
                        <img class="img-responsive" src="<?php echo Yii::getAlias('@web') . "/images/convenio.png"; ?>" alt="cp_dollar" />  
                    </td>
                    <td>
                        <?= DetailView::widget([
                            'model' => $model,
                            'attributes' => [
                                'nombre',
                                'tipo_archivo',
                                [
                                    'label' => 'Periodo',  
                                    'format'=>'html',
                                    'value' => $model->periodoBarrido,
                                ],                                
                                'fecha_creacion:date',
                                'fecha_debito:date',
                                [
                                    'label' => 'Reg. Enviados',
                                    'value' => $model->registros_enviados,
                                ],
                                [
                                    'label' => 'Reg. Correctos',
                                    'value' => $model->registros_correctos,
                                ],                                   
                                [
                                    'label' => 'Procesado',  
                                    'format'=>'html',
                                    'value' => $model->Procesado,
                                ],
                            ],
                        ]); ?>

                    </td>
                </tr>
            </table>
        </div>
      </div>
        
        
        

      <div class="row">
        <div class="col-sm-8 col-sm-offset-2">
            <p>
                <?= Html::button('<i class="glyphicon glyphicon-floppy-save"></i>  Archivo', ['class' => 'btn btn-primary',
                        'onclick'=>'js:{downArchivoBanco("'.Url::to(['debito-automatico/descargar-archivo-envio','id'=>$model->id]) .'");}']); ?>
                
                <?= Html::button('<i class="glyphicon glyphicon-hand-right"></i>  Convertir a Excel', ['value'=>yii\helpers\Url::to(['convertir-a-excel', 'id' => $model->id]), 'class' => 'btn btn-success','id'=>'btn-verificar']) ?>
            
               <?= Html::button('Procesar Devolución', ['value'=> yii\helpers\Url::to(['procesar','id'=>$model->id]), 'class' => 'btn btn-primary', 'id'=>'btn-procesa']) ?>
            </p>    
        </div>
      </div>
      
    </div>
</div>