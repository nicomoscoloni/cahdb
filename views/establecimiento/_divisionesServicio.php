<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;





/* @var $this yii\web\View */
/* @var $searchModel app\models\search\DivisionEscolar */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Division Escolars';
$this->params['breadcrumbs'][] = $this->title;
?>

<?php Pjax::begin([
                    'id'=>'pjax-divisionesservicios',
                    'class'=>'pjax-loading',
                    'enablePushState' => false,
                    'timeout'=>false,                    
                    ]); ?>    
    <?= GridView::widget([
        'id'=>'grid-divisiones-con-servicio',
        'dataProvider' => $divisionesConServicio,       
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'id',
            'nombre',
        ],
    ]); ?>


    <?= GridView::widget([
        'id'=>'grid-divisiones-sin-servicio',
        'dataProvider' => $divisionesSinServicio,      
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'id',
            'nombre',         
            ['class' => 'yii\grid\ActionColumn',
                            'template'=>'{quitar}',
                            'headerOptions' => ['class'=>'btnquitar'],
                            'buttons' => 
                            [
                              'quitar' => function ($url, $model) use ($servicio){                                        
                                               return Html::a( '<i class="glyphicon glyphicon-trash"></i>',
                                                                      ['establecimiento/asignar-servicio-division', 'division'=>$model->id, 'servicio'=>$servicio],
                                                                      ['class'=>'btn btn-xs btn-danger',
                                                                       'onclick'=>'js:{asignarServicioDivision("'.Url::to(['establecimiento/asignar-servicio-division', 'division'=>$model->id,'servicio'=>$servicio]) .'"); return false;}'   ]
                                                              );
                                           },


                            ],
                           
                                                   
                           ],
        ],
    ]); ?>
<?php Pjax::end(); ?>
<?php 
$this->registerJsFile('@web/js/servicios-establecimiento.js', ['depends'=>[app\assets\AppAsset::className()]]);
?>