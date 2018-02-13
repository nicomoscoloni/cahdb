<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\ConvenioPagoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Convenios Pagos';
?>
<div class="box box-solid box-colegio" id="convenio-pago-index">
    <div class="box-header with-border">        
        <i class="fa fa-handshake-o fa-2"></i> <h3 class="box-title"> CONVENIOS PAGO </h3> 
    </div>    
    <div class="box-body">
        
            <p class="pull-right">
                <?= Html::a('<i class=\'fa fa-plus-square\'></i> Nuevo', ['alta'], ['class' => 'btn btn-success']) ?>
            </p>
      
                <?php Pjax::begin([
                        'id'=>'pjax-convenios',                       
                        'enablePushState' => false,
                        'timeout'=>false,
                       ]); ?>    
                <?= GridView::widget([
                    'id'=>'grid-convenios',
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'columns' => [    
                        'id',            
                        'descripcion',
                        'fecha_alta:date',
                        [
                            'label' => 'Familia',
                            'attribute'=>'id_familia',
                            'value' => function($model) {
                                return $model->miFamilia->apellidos ." - Folio". 
                                    $model->miFamilia->folio;
                            },
                        ],
                        'saldo_pagar',
                        [
                            'label' => 'Deb.Aut',
                            'attribute'=>'deb_automatico',
                            'filter'=> dmstr\helpers\Html::activeDropDownList($searchModel,'deb_automatico',['0'=>'NO','1'=>'SI'],['prompt'=>'TODOS','class'=>'form-control']),
                            'value' => function($model) {
                                if($model->deb_automatico== '0')
                                    return "NO";
                                else return "SI";
                            },
                        ],    
                        [
                            'label' => 'Cuotas',  
                            'format'=>'raw',
                            'contentOptions'=>['class'=>'columncenter'],
                            'value' => function($model) {
                                return $model->cantCuotas;                
                            },
                        ],    
                        [
                            'label' => 'Estado.Ctas',  
                            'format'=>'raw',
                            'contentOptions'=>['class'=>'columncenter'],
                            'value' => function($model) {
                                return $model->cuotasPendientes;                
                            },
                        ],             

                        ['class' => 'yii\grid\ActionColumn',
                         'template'=>'{view} {delete}',
                         'headerOptions' => ['width' => '50'],  ],
                    ],
                ]); ?>
                <?php Pjax::end(); ?>
                
          
        
    
    </div>
</div>