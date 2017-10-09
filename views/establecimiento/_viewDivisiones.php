<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;

use app\assets\CRUDAjaxAsset;
CRUDAjaxAsset::register($this);

?>
<br />
<div class="row">
    <div class="col-xs-8 col-xs-offset-2"> 
        <div class="box box-warning divisiones-index">
            <div class="box-body">  
                <?php
                if (Yii::$app->user->can('gestionarDivisionEscolar')) { ?>
                    <div class="pull-right">
                        <p>
                            <?=  Html::a('<i class="fa fa-plus-square"></i> ALTA NUEVA', ['establecimiento/cargar-division','est'=>$establecimiento], 
                                    ['class' => 'btn btn-primary btn-alta',
                                    'onclick'=>'js:{cargaAjax("'.Url::to(['establecimiento/cargar-division','est'=>$establecimiento]) .'","#pjax-divisiones"); return false;}']) ?>
                        </p>
                    </div>
                <?php } ?>
                <div>
                    <div class="col-sm-12">
                    <?php Pjax::begin([
                            'id'=>'pjax-divisiones',
                            'enablePushState' => false,  
                            'timeout'=>false,
                            ]); 
                    ?>    
                    <?= GridView::widget([
                        'dataProvider' => $dataProviderDivisiones,
                        'filterModel' => $searchModelDivisiones,
                        'columns' => [                   
                            'nombre',     
                            ['class' => 'yii\grid\ActionColumn',
                                'headerOptions' => ['width' => '30'],
                                'template'=>'{view}',                                
                                'buttons' => 
                                   [
                                   'view'  => function ($url, $model) {                                
                                                    return Html::a( '<i class="glyphicon glyphicon-eye-open"></i>',
                                                                           ['division-escolar/view', 'id'=>$model['id']],
                                                                           ['class'=>'']
                                                                   );
                                           },  
                                   ]   
                            ],
                            ['class' => 'yii\grid\ActionColumn',
                                'headerOptions' => ['width' => '80'],
                                'template'=>'{update} {delete}',
                                'visibleButtons' => [                                   
                                    'update' => Yii::$app->user->can('gestionarDivisionEscolar'),
                                    'delete' =>Yii::$app->user->can('gestionarDivisionEscolar'),
                                ],
                                'buttons' => 
                                   [
                                   'view'  => function ($url, $model) {                                
                                                    return Html::a( '<i class="glyphicon glyphicon-eye-open"></i>',
                                                                           ['division-escolar/view', 'id'=>$model['id']],
                                                                           ['class'=>'']
                                                                   );
                                           },  
                                   'update' => function ($url, $model) {                                
                                                    return Html::a( '<i class="glyphicon glyphicon-pencil"></i>',
                                                                           ['establecimiento/actualizar-division', 'id'=>$model['id']],
                                                                           ['class'=>'editAjax',
                                                                            'onclick'=>'js:{editAjax("'.Url::to(['establecimiento/actualizar-division', 'id'=>$model['id']]) .'","#pjax-divisiones"); return false;}']
                                                                   );
                                           },
                                    'delete' => function ($url, $model) {                                
                                                   return Html::a( '<i class="glyphicon glyphicon-remove"></i>',
                                                                           ['establecimiento/eliminar-division', 'id'=>$model['id']],
                                                                           ['class'=>'deleteAjax',
                                                                            'onclick'=>'js:{deleteAjax("'.Url::to(['establecimiento/eliminar-division', 'id'=>$model['id']]) .'","#pjax-divisiones"); return false;}']
                                                                   );
                                            },                

                                    ],
                                                    
                                    'visible'=>Yii::$app->user->can('gestionarDivisionEscolar'),
                            ],
                        ],
                    ]); ?>
                    <?php Pjax::end(); ?>
                    </div>
                </div>
            </div>
        </div>
        
        </div>
</div>