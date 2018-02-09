<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;

use app\assets\EstablecimientoAssets;
EstablecimientoAssets::register($this);

?>
<div class="row">
    <div class="col-xs-8 col-xs-offset-2"> 
        <div class="box box-warning divisiones-index">
            <div class="box-body">  
                    <div class="pull-right">
                        <p>
                            <?=  Html::button('<i class="fa fa-plus-square"></i>',  
                                    ['class' => 'btn btn-primary btn-xs',
                                    'onclick'=>'js:divisionescolar.cargarDivision("'.Url::to(['establecimiento/cargar-division','est'=>$modelEstablecimiento->id]) .'");']) ?>
                        </p>
                    </div>
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
                        'columns' => [                   
                            'nombre',     
                            ['class' => 'yii\grid\ActionColumn',
                                'headerOptions' => ['width' => '30'],
                                'template'=>'{view}',    
                                'visibleButtons' => [                                   
                                    'view' => Yii::$app->user->can('visualizarDivisionEscolar'),
                                ],
                                'buttons' => 
                                   [
                                   'view'  => function ($url, $model) {                                
                                                    return Html::a( '<i class="glyphicon glyphicon-eye-open"></i>',
                                                                           ['division-escolar/view', 'id'=>$model['id']],
                                                                           ['class'=>'']
                                                                   );
                                           },  
                                   ],
                                                   //'visible'=>Yii::$app->user->can('visualizarDivisionEscolar'),
                            ],
                            ['class' => 'yii\grid\ActionColumn',
                                'headerOptions' => ['width' => '80'],
                                'template'=>'{update} {delete}',
                                'visibleButtons' => [                                   
                                    'update' => Yii::$app->user->can('gestionarDivisionesEscolares'),
                                    'delete' =>Yii::$app->user->can('gestionarDivisionesEscolares'),
                                ],
                                'buttons' => 
                                   [
                                   'view'  => function ($url, $model) {                                
                                                    return Html::a( '<i class="glyphicon glyphicon-eye-open text-red"></i>',
                                                                           ['division-escolar/view', 'id'=>$model['id']],
                                                                           ['class'=>'btn btn-xs btn-primary']
                                                                   );
                                           },  
                                   'update' => function ($url, $model) {                                
                                                    return Html::button( '<i class="glyphicon glyphicon-pencil"></i>',                                                                           
                                                                           ['class'=>'btn btn-xs btn-primary',
                                                                            'onclick'=>'js:divisionescolar.cargarDivision("'.Url::to(['establecimiento/actualizar-division', 'id'=>$model['id']]) .'");']
                                                                   );
                                           },
                                    'delete' => function ($url, $model) {                                
                                                   return Html::button( '<i class="glyphicon glyphicon-remove"></i>',
                                                                           ['class'=>'btn btn-xs btn-danger',
                                                                            'onclick'=>'js:divisionescolar.eliminarDivision("'.Url::to(['establecimiento/eliminar-division', 'id'=>$model['id']]) .'");']
                                                                   );
                                            },                

                                    ],
                                                    
                                    'visible'=>Yii::$app->user->can('gestionarDivisionesEscolares'),
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
<input type="hidden" name="urlreloadpajaxdivisiones" id="urlreloadpajaxdivisiones" value="<?= Url::to(['establecimiento/mis-divisiones-escolares','establecimiento'=>$modelEstablecimiento->id]); ?>" />
<?php
    yii\bootstrap\Modal::begin([        
        'id'=>'modal-divisiones',
        'class' =>'modal-scrollbar',
        'size' => 'modal-lg',
        'footer' => '<button type="submit" class="btn btn-default btn-submit-form">ACEPTAR</button>',
    ]);
        echo "<div id='modalContent'></div>";
    yii\bootstrap\Modal::end();
?>