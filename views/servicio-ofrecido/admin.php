<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\ServicioOfrecidoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Servicio Ofrecidos';
$this->params['breadcrumbs'][] = $this->title;
?>

<div id="tipo-documento-index" class="box box-colegio">
    <div class="box-header with-border">
        <i class="fa fa-cogs"></i> <h3 class="box-title"> Administración Servicios </h3> 
    </div>
    <div class="box-body">
        <div class="pull-right">
            <p> <?=  Html::a('<i class="glyphicon glyphicon-plus"></i>', ['alta'], ['class' => 'btn btn-xs btn-success btn-alta']); ?> </p>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <?php Pjax::begin([
                    'id'=>'pjax-servicioofrecido',
                    'enablePushState' => false,
                    'timeout'=>false,
                ]); ?>    
                
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],
                        [
                            'label' => 'Servicio',
                            'attribute'=>'id_tiposervicio',
                            'filter'=> dmstr\helpers\Html::activeDropDownList($searchModel, 'id_tiposervicio', app\models\CategoriaServicioOfrecido::getTipoServicios(), ['prompt'=>'TODOS', 'class'=>'form-control']),
                            'value' => function($model, $key, $index, $column) {
                                    return $model->miTiposervicio->descripcion;
                            },
                        ],
                        'nombre',                    
                        [
                            'label' => 'Importe',
                            'value' => function($model, $key, $index, $column) {
                                    return "$ " . $model->importe;
                                },
                        ],
                        [
                            'label' => 'Importe H.Profesor',
                            'value' => function($model, $key, $index, $column) {
                                    return "$ " . $model->importe_hijoprofesor;
                                },
                        ],                
                        [
                            'label' => 'Vencimiento Pago',
                            'value' => 'fecha_vencimiento',
                            'format'=>'date'
                        ],                
                        [
                            'label' => 'Perido',
                            'value' => function($model, $key, $index, $column) {
                                    return $model->detallePeriodo;
                                },
                        ],
                        [
                            'label' => 'Devenga',
                            'attribute'=>'devengamiento_automatico',
                            'filter'=> dmstr\helpers\Html::activeDropDownList($searchModel, 'devengamiento_automatico',['0'=>'NO','1'=>'SI'] ,['prompt'=>'TODOS','class'=>'form-control']),
                            'value' => function($model, $key, $index, $column) {
                                if($model->devengamiento_automatico=='0')
                                    return "NO";
                                else
                                    return "SI";                            
                            },
                        ],


                        ['class' => 'yii\grid\ActionColumn'
                          ],
                    ],
                ]); 
                ?>
            <?php Pjax::end(); ?>   
            </div>
        </div>
        
    </div>
</div>