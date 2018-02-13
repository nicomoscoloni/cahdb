<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\ServicioAlumnoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

?>
<div class="box box-colegio">    
    <div class="box-body">
        
        <?php if($buscador) echo $this->render('_search', ['model' => $searchModel, 'filtro_estados'=>$filtro_estados, 'filtro_establecimiento'=>$filtro_establecimiento,
            'filtro_divisiones'=>$filtro_divisiones]); ?>
        
        
                        
        <div class="row table-responsive">
            <div class="col-sm-12">
            <?php   
                    Pjax::begin(
                            [
                            'id'=>'pjax-serviciosalumnos',
                            'enablePushState' => false,
                            'timeout'=>false,
                            'clientOptions' => ['method' => 'POST']
                            ]
                    ); ?>    
                    <?= GridView::widget([
                        'id'=>'reporte-servicios-alumno',
                        'dataProvider' => $dataProvider,                        
                        'columns' => [
                            [
                                'label' => 'Alumno',
                                 'value' => function($model) {
                                    return $model->datosMiAlumno;
                                },
                            ],
                            [
                                'label' => 'Servicio',
                                 'value' => function($model) {
                                    return $model->datosMiServicio;
                                },
                            ],
                            [
                                'label' => '$.Servicio',
                                 'value' => function($model) {
                                    return $model->importe_servicio;
                                },
                            ],
                            [
                                'label' => '$.Descuento',
                                 'value' => function($model) {
                                    return $model->importe_descuento;
                                },
                            ],            
                            [
                                'label' => '$.Abonado',
                                 'value' => function($model) {
                                    return $model->importe_servicio;
                                },
                            ],
                            [
                                'label' => '$.Abonar',
                                 'value' => function($model) {
                                    return $model->importe_servicio;
                                },
                            ], 
                            [
                                'label' => 'Estado',
                                'format'=>'raw',
                                'value' => function($model) {
                                    return $model->detalleEstado;
                                },
                            ],                             
                        ],
                    ]); ?>
                <?php Pjax::end(); ?>
            </div>            
        </div>    
           
        
    </div>
</div>