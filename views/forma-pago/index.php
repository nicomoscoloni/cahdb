<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;

use app\assets\CRUDAjaxAsset;
CRUDAjaxAsset::register($this);

/* @var $this yii\web\View */
/* @var $searchModel backend\models\TipoDocumentoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
?>
<div id="tipo-documento-index">

    <div class="box box-success">
        <div class="box-header with-border">
            <i class="fa fa-support"></i> <h3 class="box-title"> Pagos Habilitados </h3>      
        </div>
        <div class="box-body">
            
                <p class="pull-right">
                    <?=  Html::a('<i class="glyphicon glyphicon-plus"></i>', ['create'], ['class' => 'btn btn-xs btn-success',
                        'onclick'=>'js:{cargaAjax("'.Url::to(['forma-pago/create']) .'"); return false;}']) ?>
                </p>
            <div class="row">
                <div class="col-sm-12">
                    <?php Pjax::begin(['id'=>'pjax-grid','enablePushState' => false]); ?>    
                    <?=   GridView::widget([
                        'dataProvider' => $dataProvider,
                        'filterModel' => $searchModel,
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],
                            'nombre',
                            'siglas',
                            ['class' => 'yii\grid\ActionColumn',
                                'template'=>'{update} {delete}',
                                'buttons' => 
                                   [
                                   'update' => function ($url, $model) {                                
                                                    return Html::a( '<i class="glyphicon glyphicon-pencil"></i>',
                                                                           ['create', 'id'=>$model['id']],
                                                                           ['class'=>'btn btn-xs btn-warning editAjax',
                                                                            'onclick'=>'js:{editAjax("'.Url::to(['create', 'id'=>$model['id']]) .'"); return false;}']
                                                                   );
                                           },
                                   'delete' => function ($url, $model) {                                
                                                   return Html::a( '<i class="glyphicon glyphicon-remove"></i>',
                                                                           ['delete', 'id'=>$model['id']],
                                                                           ['class'=>'btn btn-xs btn-danger deleteAjax',
                                                                            'onclick'=>'js:{deleteAjax("'.Url::to(['delete', 'id'=>$model['id']]) .'"); return false;}']
                                                                   );
                                           },                

                                   ]   
                            ],
                        ],
                    ]); ?>
                    <?php Pjax::end(); ?>    
                </div>
            </div>
            
        </div>
    </div>
</div>