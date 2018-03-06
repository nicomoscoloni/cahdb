<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;

use app\assets\GrupoFamiliarAsset;
GrupoFamiliarAsset::register($this);

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\GrupoFamiliarSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Grupo Familiar';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box box-solid box-colegio grupo-familiar-index">
    <div class="box-header with-border">
        <i class="fa fa-users"></i> <h3 class="box-title"> Listado Familias </h3>    
        
    </div>
    <div class="box-body">
       
        <p class="pull-right">
            <?php
                if (Yii::$app->user->can('cargarFamilia'))
                    echo Html::a('<i class="fa fa-plus-square"></i>', ['alta'], ['class' => 'btn btn-primary btn-xs','id'=>'btn-alta']);
                echo " ";
                if (Yii::$app->user->can('exportarFamilias'))
                    echo Html::button('<i class="glyphicon glyphicon-download"> </i> EXCEL', ['class' => 'btn btn-success btn-xs', 'id'=>'btn-excel',
                        'onclick'=>'js:{downListado("'.Url::to(['grupo-familiar/exportar-excel']) .'");}']);
            ?>
            
        
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
                        'apellidos',
                        'descripcion',
                        'folio',                       
                        [
                            'label' => 'Hijos',                            
                            'value' => function($model) {
                                $detalleHijos='';
                                $i=0;
                                if(!empty($model->alumnosActivos))
                                    foreach($model->alumnosActivos as $hijo){
                                        $i+=1;
                                        $detalleHijos.= " - $i: ".$hijo->miPersona->apellido .";".$hijo->miPersona->nombre;
                                    }
                                return $detalleHijos;
                            },
                        ],
                        [
                            'label' => 'Pago Aderido',
                            'attribute'=>'id_pago_asociado',
                            'headerOptions' => ['class'=>'pagoasociado'],
                            'filter'=> dmstr\helpers\Html::activeDropDownList($searchModel, 'id_pago_asociado', app\models\FormaPago::getFormasPago(),['prompt'=>'','class'=>'form-control']),
                            'value' => function($model) {
                                return $model->pagoAsociado->nombre;
                            },
                        ],
                        ['class' => 'yii\grid\ActionColumn',
                        'visibleButtons' => [                                   
                            'update' => Yii::$app->user->can('cargarFamilia'),
                            'view' => Yii::$app->user->can('visualizarFamilia'),
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
            
        </p>
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
                intro: "Listado de Familias."
            },  
            {
                element: document.querySelector('.grid-view .filters'),
                intro: "Filtros para realizar busquedas específicas."
            },            
            {
                element: document.querySelector('.pagoasociado'),
                intro: "Medio pago adoptado."
            },
            {
                element: document.querySelector('#btn-alta'),
                intro: "Alta de Grupo Familiar."
            },
            {
                element: document.querySelector('#btn-excel'),
                intro: "Descargar listado en excel."
            },
        ]
      });
      intro.start();
}      
</script>