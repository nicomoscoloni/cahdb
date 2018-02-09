<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\DebitoAutomaticoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Debitos Automaticos';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box box-colegio" id="debito-automatico-index">
    <div class="box-header with-border">
        <i class="fa  fa-user-plus"></i><h3 class="box-title"> Debitos Automáticos </h3>
    </div>
    <div class="box-body">
        <div class="pull-right">
        <p>
            <?= Html::a('<i class=\'fa fa-plus-square\'></i> Nuevo', ['generar'], ['class' => 'btn btn-success']) ?>
        </p>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class='table-responsive'>
                <?php Pjax::begin(
                        [
                                'id'=>'pjax-debitosautomaticos',                       
                                'enablePushState' => false,
                                'timeout'=>false,
                               ]
                        ); ?>    
                <?= GridView::widget([
                    'id'=>'grid-debitosautomaticos',
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'columns' => [
                        'id',
                        'tipo_archivo',
                        'inicio_periodo:date',
                        'fin_periodo:date',                
                        'registros_enviados',
                        'registros_correctos',                        
                        [
                            'label' => 'Procesado',
                            'value' => function($model) {
                                return $model->saldo_enviado;
                                
                            }
                        ],
                        'saldo_entrante',
                        [
                            'label' => 'Procesado',
                            'attribute'=>'procesado',
                            'filter'=> dmstr\helpers\Html::activeDropDownList($searchModel,'procesado',['0'=>'NO','1'=>'SI'],['prompt'=>'TODOS','class'=>'form-control']),
                            'value' => function($model) {
                                if($model->procesado=='0')
                                    return "NO";
                                else
                                    return "SI";
                            },
                        ],                
                        ['class' => 'yii\grid\ActionColumn',
                         'template'=>'{view}',   
                        ],
                    ],
                ]); ?>
                <?php Pjax::end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>