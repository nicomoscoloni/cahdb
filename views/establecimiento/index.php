<?php


use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\EstablecimientoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Establecimientos';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="box box-colegio">
    <div class="box-header with-border">
        <i class="fa fa-university"></i> <h3 class="box-title"> Establecimientos </h3>       
    </div>
    <div class="box-body">         
        <div class="pull-right">
          <p>
            <?= Html::a('<i class="glyphicon glyphicon-plus"></i> Alta', ['alta'], ['class' => 'btn btn-success']) ?>
          </p>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <?php Pjax::begin([
                    'id'=>'pjax-establecimientos',
                    'class'=>'pjax-loading',
                    'enablePushState' => false,
                    'timeout'=>false,                    
                    ]); ?>
                
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'columns' => [
                        'nombre',                        
                        'calle',
                        'telefono',                        
                        'mail',
                        'nivel_educativo',
                        ['class' => 'yii\grid\ActionColumn', 
                         'template'=>'{view} {update}',   
                         'headerOptions' => ['width' => '50'],   
                        ],
                    ],
                ]); ?>
                <?php Pjax::end(); ?>   
            </div>
        </div> 
    </div>    
</div>