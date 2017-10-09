<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\Pjax;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model app\models\ConvenioPago */

$this->title = "Convenio Pago: " . $model->id;
?>
<div class="box box-solid box-index">
    <div class="box-header with-border">
        <i class="fa fa-arrow-circle-o-right"></i><h3 class="box-title"> Detalles Convenio Pago </h3>
    </div>
    <div class="box-body"> 

        <div class="row"> <!-- row dettales delconvenio -->
            <div class="col-sm-8 col-sm-offset-2 col-xs-12">
                <table>
                    <tr>
                        <td width="25%"> 
                            <img class="img-responsive" src="<?php echo Yii::getAlias('@web') . "/images/convenio.png"; ?>" alt="cp_dollar" />  </td>
                        <td width="60%">
                            <h3 class="text-light-blue bold">    Nro Convenio: <?php echo $model->id; ?> </h3>
                            <span class="text-light-blue bold">  Fecha Alta: </span> <?php echo \Yii::$app->formatter->asDate($model->fecha_alta); ?><br /> 
                            <span class="text-light-blue bold">  Grupo Familiar: </span> <?php echo $model->miFamilia->apellidos ." Folio: ".$model->miFamilia->folio; ?> <br />
                            <h3 class="text-light-blue bold">  Importe Convenio Pago:  $<?php echo $model->saldo_pagar; ?> </h3><br />
                            <br />
                            <div class="">
                            <?php
                            echo Html::button('<i class="glyphicon glyphicon-print"> </i> IMPRIR', ['class' => 'btn btn-info', 'id'=>'btn-pdf-convenio',
                                'onclick'=>'js:{downPdfConvenio("'. yii\helpers\Url::to(['convenio-pago/pdf','id'=>$model->id]) .'");}']);
                            echo " ";
                            echo Html::button('<i class="glyphicon glyphicon-envelope"> </i> ENVIAR CORREO', ['class' => 'btn btn-info', 'id'=>'btn-correo-convenio',
                                'onclick'=>'js:{enviarPdfConvenio("'. yii\helpers\Url::to(['convenio-pago/enviar-correo','id'=>$model->id]) .'");}']);
                            ?>
                            </div>
                        </td>
                    </tr>                  
                </table>          
            </div>
        </div> <!-- fin row dettales delconvenio -->
        
        <br />
        
        <div class="row">
            <div class="col-sm-8 col-sm-offset-2 col-xs-12">
            <div class="box box-warning">             
                <div class="box-header with-border">
                    <h3 class="box-title">Cuotas</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>                
                    </div>
                </div>     

                <div class="box-body">
                    <?php Pjax::begin(); ?>    
                    <?= GridView::widget([
                            'dataProvider' => $misCuotas,                            
                            'columns' => [                                
                                'nro_cuota',                                
                                [
                                    'label' => 'Fecha Pago',
                                    'attribute'=>'fecha_establecida',
                                    'format' =>'date',
                                    'contentOptions'=>['class'=>'columncenter'],
                                    'value' => function($model) {                                        
                                            return $model->fecha_establecida;                                        
                                    },
                                ],  
                                [
                                    'label' => 'Liquidada',
                                    'attribute'=>'pagada',
                                    'format' =>'raw',
                                    'contentOptions'=>['class'=>'columncenter'],
                                    'value' => function($model) {
                                        if ($model->pagada=='0'){
                                            return "<span class='label label-warning'>NO</span>";
                                        }else{
                                            return"<span class='label label-success'>SI</span>";
                                        }
                                    },
                                ],  
                                [
                                    'label' => 'Estado',
                                    'contentOptions'=>['class'=>'columncenter'],
                                    'attribute'=>'estado',
                                    'value' => function($model) {
                                        if ($model->estado=='A'){
                                            return "SIN ABONAR";
                                        }else
                                        if (($model->estado=='DA') || ($model->estado=='DA/CP')){
                                            return "En Debito Automatico";
                                        }else
                                        if (($model->estado=='PA/DA') || ($model->estado=='PA')){
                                            return "ABONADA";
                                        }
                                    },
                                ],     
                                [
                                    'label' => 'Monto',
                                    'contentOptions'=>['class'=>'columncenter'],
                                    'attribute'=>'monto',
                                    'value' => function($model) {
                                        return "$ " . $model->monto;
                                    },
                                ],
                                [
                                    'label' => 'Monto Abonado',
                                    'contentOptions'=>['class'=>'columncenter'],
                                    'attribute'=>'monto',
                                    'value' => function($model) {
                                        return "$ " . $model->importe_abonado;
                                    },
                                ],            
                                [
                                    'label' => 'Factura',
                                    'contentOptions'=>['class'=>'columncenter'],
                                    'format'=>'raw',
                                    'value' => function($model) {
                                        if(!empty($model->id_tiket)){
                                            return 
                                                \yii\bootstrap\Html::button("<img class='img-responsive' src='".Yii::getAlias("@web")."/images/pdf.png' alt='cp_dollar' />", ['class' => 'btn btn-xs', 'id'=>'btn-pdf-servicios',
                                                'onclick'=>'js:{downFactura("'. yii\helpers\Url::to(['caja/pdf-factura','idFact'=>$model->miIdFactura]) .'");}']);
                                        }else
                                        { return "";}
                                    },
                                ],                         
                            ],
                        ]); ?>
                    <?php Pjax::end(); ?>                    
                </div>
            </div>
            </div>
        </div>
        
        <div class="row">          
            <div class="col-sm-8 col-sm-offset-2 col-xs-12">
            <div class="box box-warning">                 
                <div class="box-header with-border">
                    <h3 class="box-title">Detalle Servicios</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>                
                    </div>
                </div>
                <div class="box-body">
                
                    <?php
                    if(!empty($misServicios->getModels())){
                        Pjax::begin();    
                        echo
                        GridView::widget([
                            'dataProvider' => $misServicios,                        
                            'columns' => [
                                ['class' => 'yii\grid\SerialColumn'],                            
                                [
                                        'label' => 'Detalle',
                                        'attribute'=>'id_servicio',
                                        'value' => function($model) {
                                            return $model->idServicio->datosMiServicio;
                                        },
                                    ],  
                                [
                                        'label' => 'Alumno',                                       
                                        'value' => function($model) {
                                            return $model->idServicio->datosMiAlumno;
                                        },
                                    ],                  

                            ],
                        ]);
                        Pjax::end(); 
                    }else{
                      echo $model->descripcion;   
                    } ?>
                </div>
            </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">  
function downPdfConvenio(xhref){
    
    $("body").loading({message: 'ESPERE... procesando'});
    $.ajax({
         url    : xhref,
         type   : "post",            
         dataType: "json",
         success: function (response){             
             $("body").loading('stop');  
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
    }); 
}   

function enviarPdfConvenio(xhref){
    
    $("body").loading({message: 'ESPERE... procesando'});
    $.ajax({
         url    : xhref,
         type   : "post",            
         dataType: "json",
         success: function (response){             
             $("body").loading('stop');  
             if(response.result_error==='0'){
                new PNotify({
                    title: 'Correcto',
                    text: 'Se envio de forma correcta en correo.',
                    icon: 'glyphicon glyphicon-envelope',
                    type: 'success'
                }); 
             }else{
                new PNotify({
                    title: 'Error',
                    text: response.message,
                    icon: 'glyphicon glyphicon-envelope',
                    type: 'error'
                });
             }
        },         
    }); 
}   
</script>