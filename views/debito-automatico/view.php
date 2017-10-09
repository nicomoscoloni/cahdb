<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\form\ActiveForm;
use yii\helpers\Url;

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
       <i class="fa fa-bell"></i>    <h3 class="box-title"> DETALLE DEBITO AUTOMATICOS </h3> 
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
                <?= Html::button('<i class="glyphicon glyphicon-floppy-save"></i>  ARCHIVO A ENVIAR', ['class' => 'btn btn-primary',
                        'onclick'=>'js:{downListado("'.Url::to(['debito-automatico/descargar-archivo-envio','id'=>$model->id]) .'");}']); ?>
                <?= Html::button('<i class="glyphicon glyphicon-hand-right"></i>  VERIFICAR ARCHIVO', ['value'=>yii\helpers\Url::to(['convertir-a-excel', 'id' => $model->id]), 'class' => 'btn btn-success','id'=>'btn-verificar']) ?>
            
               <?= Html::button('PROCESAR ARCHIVO', ['value'=> yii\helpers\Url::to(['procesar','id'=>$model->id]), 'class' => 'btn btn-primary', 'id'=>'btn-procesa']) ?>
            </p>    
        </div>
      </div>
      
    </div>
</div>

<?php
$this->registerJs("  
$('body').on('beforeSubmit', 'form#form-procesamiento', function () {
        var form = $(this);  
        alert('ddd');
        alert(form.attr('action'));
        
        // submit form
        
        $.ajax({
            url    : form.attr('action'),
            type   : 'post',
            data   : form.serialize(),
            dataType: 'json',
            success: function (response) {                          
            },
            error  : function () {                
            }
        });        
        return false;
});

    

$('#btn-procesa').on('click',function(){               
    $('#modalProcesa').modal('show').find('#modalContent').load(jQuery(this).attr('value'));
}); 
    
$('#btn-verificar').on('click',function(){
    $('body').loading({message: 'ESPERE... procesando', theme:'dark'});
    $.ajax({
        url    : $(this).attr('value'),
        type   : 'post',            
        dataType: 'json',
        success: function (response){
            console.log(response);
            $('body').loading('stop');  
            if(response.result_error=='0'){
                window.location.href = response.result_texto; 
            }else{
                new PNotify({
                    title: 'Error',
                    text: response.message,
                    icon: 'glyphicon glyphicon-envelope',
                    type: 'error'
                });
            }
        }     
    }); 
});     
   
function downListado(xhref){    
    $('body').loading({message: 'ESPERE... procesando'});
    $.ajax({
         url    : xhref,
         type   : 'post',            
         dataType: 'json',
         success: function (response){ 
            $('body').loading('stop');  
            if(response.result_error==='0'){
                window.location.href = response.result_texto; 
            }else{
                new PNotify({
                    title: 'Error',
                    text: response.message,
                    icon: 'glyphicon glyphicon-envelope',
                    type: 'error'
                });
            }
        },
        error: function(XHR) {
                   $('body').loading('stop');                    
                   if (XHR.statusText == 'Unauthorized')
                    {
                        new PNotify({
                            title: 'ERROR!!!',
                            text: 'USTED NO TIENE LOS PERMISOS SUFICIENTES PARA LLEVAR A CABO LA TAREA SOLICITADA',
                            icon: 'ui-icon ui-icon-mail-closed',
                            opacity: .8,
                            type: 'success'
                           
                        });
                    }else
                    if( ((XHR.status == '403') || ((XHR.status == 403))) && ((XHR.statusText == 'Forbidden'))){
                            new PNotify({
                                title: 'ERROR!!!',
                                text: 'USTED NO TIENE LOS PERMISOS SUFICIENTES PARA LLEVAR A CABO LA TAREA SOLICITADA',
                                icon: 'ui-icon ui-icon-mail-closed',
                                opacity: .8,
                                type: 'success'                           
                            });  
                     }
                }
    }); 
} 
", \yii\web\View::POS_READY,'preventSubmitForm');
?>