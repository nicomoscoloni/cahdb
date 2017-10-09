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
        
        <?php if($buscador) echo $this->render('_search', ['model' => $searchModel]); ?>
        
        <?= Html::button('<i class="fa fa-file-excel-o"> </i> EXCEL', ['class' => 'btn btn-success', 'id'=>'btn-excel',
                        'onclick'=>'js:{downListado("'.Url::to(['servicio-alumno/exportar-excel']) .'");}']) ?>
                        
        <div class="row table-responsive">
            <div class="col-sm-12">
            <?php   
                Pjax::begin(
                        [
                        'id'=>'pjax-serviciosalumnos',
                        'enablePushState' => false,
                        'timeout'=>false,
                        ]
                ); ?>    
                <?= GridView::widget([
                        'id'=>'reporte-servicios-alumno',
                        'dataProvider' => $dataProvider,        
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],
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
                            'importe_servicio',
                            'importe_descuento',
                            'importe_abonado',
                            'importeAbonar',
                            'detalleEstado',
                        ],
                    ]); ?>
                <?php Pjax::end(); ?>
            </div>            
        </div>    
           
        
    </div>
</div>
    
