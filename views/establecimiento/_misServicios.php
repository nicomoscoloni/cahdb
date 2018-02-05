<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;


?>
<br />
<div class="row">
    <div class="col-xs-10 col-xs-offset-1"> 
<div class="box box-warning establecimiento-index">
    <div class="box-header with-border">
    </div>
    <div class="box-body">
            <div class="pull-right">
                <p>
                <?=  Html::a('<i class="fa fa-plus-square"></i> Cargar Servicio', ['establecimiento/nuevo-servicio','est'=>$modelEstablecimiento->id], 
                        ['class' => 'btn btn-primary btn-alta']); ?>
                </p>
            </div>
            <div>
                <?php Pjax::begin([
                    'id'=>'pjax-servicios',
                    'enablePushState' => false, 
                    'timeout'=>false
                    ]); 
                ?>    
                <?= GridView::widget([
                    'id'=>'grilla-servicios',
                    'dataProvider' => $dataProviderSerEst,
                    'filterModel' => $searchModelSerEst,
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],
                        [
                            'label' => 'Servicio',
                            'attribute'=>'id_servicio',
                            'filter'=> dmstr\helpers\Html::activeDropDownList($searchModelSerEst, 'id_servicio', \app\models\ServicioEstablecimiento::getServiciosxEstablecimiento($modelEstablecimiento->id), ['prompt'=>'','class'=>'form-control']),                  
                            'value' => function($model) {
                                return $model->servicio->detalleServicio  ;
                            },
                        ],
                        [
                            'label' => 'División Escolar',
                            'filter'=> dmstr\helpers\Html::activeDropDownList($searchModelSerEst, 'id_divisionescolar', \app\models\DivisionEscolar::getDivisionesEstablecimiento($modelEstablecimiento->id), ['prompt'=>'','class'=>'form-control']),                  
                            'attribute'=>'id_divisionescolar',
                            'value' => function($model) {
                                return $model->miDivisionescolar->nombre;
                            },
                        ]
                    ],
                ]); ?>
                <?php Pjax::end(); ?>    
            </div>
    </div>
</div>
                </div>
</div>