<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\GrupoFamiliarSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Grupo Familiar';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box box-solid box-colegio grupo-familiar-index">
    <div class="box-header with-border">
        <i class="fa fa-users"></i> <h3 class="box-title"> FAMILIAS </h3>    
        
    </div>
    <div class="box-body">
       
        <div class="pull-right">
            <p> <?= Html::a('<i class="fa fa-plus-square"></i>', ['alta'], ['class' => 'btn btn-primary']) ?> 
          <?= Html::button('<i class="glyphicon glyphicon-download"> </i> EXCEL', ['class' => 'btn btn-success', 'id'=>'btn-excel',
                        'onclick'=>'js:{downListado("'.Url::to(['grupo-familiar/exportar-excel']) .'");}']) ?>
            </p>
        </div>
        
        <div class="row">
            <div class="col-sm-12">
                <?php Pjax::begin([
                    'id'=>'pjax-familias',
                    'enablePushState' => false,  
                    'timeout'=>false
                    ]); 
                ?>  
                <?=
                GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],
                        'apellidos',
                        'descripcion',
                        'folio',
                        'detalleNombreMisHijos',
                        [
                            'label' => 'PAGO ADERIDO',
                            'attribute'=>'id_pago_asociado',    
                            'filter'=> dmstr\helpers\Html::activeDropDownList($searchModel, 'id_pago_asociado', app\models\FormaPago::getFormasPago(),['prompt'=>'','class'=>'form-control']),
                            'value' => function($model) {
                                return $model->idPagoAsociado->nombre;
                            },
                        ],
                        ['class' => 'yii\grid\ActionColumn',
                        'visibleButtons' => [                                   
                                'update' => Yii::$app->user->can('gestionarGruposFamiliares'),                                
                            ],
                        'template' => '{view}{update}',
                            'buttons' => [
                                'update' => function ($url, $model) {
                                    $url = yii\helpers\Url::to(['grupo-familiar/actualizar', 'id' => $model->id]);
                                    return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url);
                                },
                            ],
                        ],
                    ],
                ]);
                ?>   
                <?php Pjax::end(); ?>
            </div>
        </div>
        
       </div>
</div>