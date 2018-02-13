<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\FondoFijo */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Fondo Fijos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div id="fondofijo-view" class="box box-solid box-colegio">
    <div class="box-header with-border">
        <i class="fa fa-user-plus"></i> <h3 class="box-title"> Fondo Fijo </h3> 
    </div>
    <div class="box-body">
        <h3 class="text-light-blue bold">    Fondo Fijo Nº: <?php echo $model->id . " Establecimiento:". $model->establecimiento->nombre; ?> </h3>
        <span class="text-light-blue bold">  Monto: </span> <?php echo $model->monto_actual;?><br />
        <span class="text-light-blue bold">  Tope Compra: </span> <?php echo $model->tope_compra; ?> <br />
              
        <?php
                     echo Html::a('<i class="fa fa-pencil"></i>   CARGAR EGRESO', ['cargar-egreso', 'id_fondofijo' => $model->id], ['class' => '']);
           ?>
        <?=        \yii\grid\GridView::widget([
        'dataProvider' => $dataProviderEgresos,
        'filterModel' => $searchEgresos,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id_clasificacionegreso',
            'fecha_compra:date',
            'proovedor',
            //'descripcion',
            'importe',
            //'nro_factura',
            //'nro_rendicion',
            'bien_uso:boolean',
            'rendido:boolean',

            ['class' => 'yii\grid\ActionColumn',
             
                        'buttons' => 
                        [
                        'delete' => function ($url, $model) {                                
                                        return Html::a( '<i class="glyphicon glyphicon-pencil"></i>',
                                                               ['/fondo-fijo/eliminar-egreso', 'id_egreso'=>$model['id']],
                                                               ['class'=>'']
                                                       );
                               }, 
                        
                                       'update' => function ($url, $model) {                                
                                        return Html::a( '<i class="glyphicon glyphicon-pencil"></i>',
                                                               ['/fondo-fijo/actualizar-egreso', 'id_egreso'=>$model['id']],
                                                               ['class'=>'']
                                                       );
                               }, 
                        ],
                                        //'visible'=>Yii::$app->user->can('cargarAlumno')||Yii::$app->user->can('visualizarAlumno'),
                    ],   
                
        ],
    ]); ?>
        
    </div>
</div>
