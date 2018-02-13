<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\search\FondoFijoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Fondo Fijos';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box box-fondo-fijo">
    <div class="box-header with-border">
        <i class="fa fa-university"></i> <h3 class="box-title"> Establecimientos </h3>       
    </div>
    <div class="box-body">         
        
          <p class="pull-right">
            <?php
            if(!Yii::$app->user->can('cargarFondoFijo'))
                echo Html::a('<i class="glyphicon glyphicon-plus"></i>', ['alta'], ['class' => 'btn btn-xs btn-success']) ?>
          </p>
        
                <?php Pjax::begin([
                    'id'=>'pjax-fondosfijos',
                    'class'=>'pjax-loading',
                    'enablePushState' => false,
                    'timeout'=>false,                    
                    ]); ?>
                
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'columns' => [
                        'id',
                        'id_establecimiento',
                        'monto_actual',
                        'alerta_tope_maximo',
                        'tope_compra',
                        ['class' => 'yii\grid\ActionColumn', 
                         'template'=>'{view} {update}',
                          //'visibleButtons' => [                                   
                                  //  'view' => Yii::$app->user->can('visualizarFondoFijo'),
                                  //  'update' =>Yii::$app->user->can('cargarFondoFijo'),
                               // ],  
                         'headerOptions' => ['width' => '50'],   
                        ],
                    ],
                ]); ?>
                <?php Pjax::end(); ?>   
            
    </div>    
</div>