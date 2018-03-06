<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\ServicioOfrecidoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Servicios Ofrecidos';
$this->params['breadcrumbs'][] = $this->title;
?>

<div id="tipo-documento-index" class="box box-colegio">
    <div class="box-header with-border">
        <i class="fa fa-cogs"></i> <h3 class="box-title"> Administración Servicios </h3> 
    </div>
    <div class="box-body">          
            <p class="pull-right"> 
                <?=  Html::a('<i class="glyphicon glyphicon-plus"></i>', 
                        ['alta'], ['class' => 'btn btn-xs btn-success btn-alta']); ?> 
            </p>
                    
            <?php Pjax::begin([
                'id'=>'pjax-servicioofrecido',
                'enablePushState' => false,
                'timeout'=>false,
            ]); ?>    
                
            <?= GridView::widget([
                'id'=>'grid-serviciosofrecidos',
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    [
                        'label' => 'Servicio',
                        'attribute'=>'id_tiposervicio',
                        'filter'=> dmstr\helpers\Html::activeDropDownList($searchModel, 'id_tiposervicio', app\models\CategoriaServicioOfrecido::getTipoServicios(), ['prompt'=>'TODOS', 'class'=>'form-control']),
                        'value' => function($model, $key, $index, $column) {
                                return $model->tiposervicio->descripcion;
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
                        'label' => 'Importe H.P',
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
                        'label' => 'Devenga',
                        'attribute'=>'devengamiento_automatico',
                        'filter'=> dmstr\helpers\Html::activeDropDownList($searchModel, 'devengamiento_automatico',['0'=>'NO','1'=>'SI'] ,['prompt'=>'-','class'=>'form-control']),
                        'value' => function($model, $key, $index, $column) {
                            if($model->devengamiento_automatico=='0')
                                return "No";
                            else
                                return "Si";                            
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
                intro: "Administración de Servicios Ofrecidos. "
            },  
            { 
                element: document.querySelector('#grid-serviciosofrecidos'),
                intro: "Listado de servicios cargados en el sistema."
            },
            {
                element: document.querySelector('.grid-view .filters'),
                intro: "Filtros para realizar busquedas especificas, puede especificar más de un dato."
            },            
            {
                element: document.querySelector('.grid-view tbody'),
                intro: "El resultado de la busqueda sera desplegado en esta sección."
            },
            {
                element: document.querySelector('.btn-alta'),
                intro: "Si desea realizar una nueva alta, presione sobre este aqui."
            },
        ]
      });
      intro.start();
}      
</script>
<?php 
    $this->registerJsFile('@web/js/servicio-ofrecido.js', ['depends'=>[app\assets\AppAsset::className()]]);
?>