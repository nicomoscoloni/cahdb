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
        
                <div class='table-responsive'>
                    <p class="pull-right">
                        <?= Html::a('<i class=\'fa fa-plus-square\'></i> Nuevo', ['generar'], ['class' => 'btn btn-success btn-xs','id'=>'btn-alta']) ?>
                    </p>
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
                        [
                            'label' => 'Tipo',
                            'attribute'=>'tipo_archivo',
                            'filter'=> dmstr\helpers\Html::activeDropDownList($searchModel,'tipo_archivo',['CBU'=>'CBU','TC'=>'TC'],['prompt'=>'-','class'=>'form-control']),
                            'value' => function($model) {
                                return $model->tipo_archivo;
                            },
                        ],
                        'tipo_archivo',
                        'nombre',
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
                            'filter'=> dmstr\helpers\Html::activeDropDownList($searchModel,'procesado',['0'=>'No','1'=>'Si'],['prompt'=>'TODOS','class'=>'form-control']),
                            'value' => function($model) {
                                if($model->procesado=='0')
                                    return "No";
                                else
                                    return "Si";
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
<script type="text/javascript">
function ayuda(){         
    var intro = introJs();
      intro.setOptions({
        nextLabel: 'Siguiente',
        prevLabel: 'Anterior',
        skipLabel:'Terminar',
        doneLabel:'Cerrar',
        steps: [      
            { 
                element: document.querySelector('.box-header'),
                intro: "Administración de Débito Automático. "
            },  
            { 
                element: document.querySelector('#grid-debitosautomaticos'),
                intro: "Listado de débitos automáticos."
            },
            {
                element: document.querySelector('.grid-view .filters'),
                intro: "Filtros para realizar busquedas específicas."
            },            
            {
                element: document.querySelector('.grid-view tbody'),
                intro: "El resultado de la busqueda sera desplegado en esta sección."
            },
            {
                element: document.querySelector('#btn-alta'),
                intro: "Si desea realizar una nueva alta."
            },
        ]
      });
      intro.start();
}      
</script>