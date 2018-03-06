<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\BonificacionAlumno */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Bonificacion Alumnos';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box  box-colegio bonificacion-index">
    <div class="box-header with-border">
        <i class="fa fa-users"></i> <h3 class="box-title"> Alumnos con Bonificaciones </h3>    
    </div>
    <div class="box-body">
            
        <p class="pull-right"> 
            <?= Html::button('<i class="glyphicon glyphicon-download"> </i> Excel', ['class' => 'btn btn-success btn-xs btn-excel', 'id'=>'btn-excel',
                    'onclick'=>'js:{downListado("'.Url::to(['/reporte/exportar-excel-alumnosbonificaciones']) .'");}']) ?>
        </p>
            
            
    <?php Pjax::begin(); ?>    
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [            
            [
                'label' => 'Bonificacion',
                'attribute'=>'id_bonificacion',
                'filter'=> dmstr\helpers\Html::activeDropDownList($searchModel, 'id_bonificacion', \app\models\CategoriaBonificacion::getBonificaciones(),
                                ['prompt'=>'','class'=>'form-control']),
                'value' => function($model) {
                    return $model->bonificacion->descripcion  ." ". $model->bonificacion->valor;
                },
            ],            
            [
                'label' => 'Apellido',
                'attribute'=>'apellido_alumno',
                'filter'=> dmstr\helpers\Html::activeInput('text', $modelPersona,'apellido',['class'=>'form-control']),
                'value' => function($model) {
                    return $model->alumno->persona->apellido;
                },
            ],
            [
                'label' => 'Nombre',
                'attribute'=>'nombre_alumno',
                'filter'=> dmstr\helpers\Html::activeInput('text', $modelPersona,'nombre',['class'=>'form-control']),
                'value' => function($model) {
                    return $model->alumno->persona->nombre;
                },
            ],
            [
                'label' => 'Documento',
                'attribute'=>'documento_alumno',
                'filter'=> dmstr\helpers\Html::activeInput('text', $modelPersona,'nro_documento',['class'=>'form-control']),
                'value' => function($model) {
                    return $model->alumno->persona->nro_documento;
                },
            ],
                        [
                'label' => 'Documento',
                'attribute'=>'familia_alumno',
                'format'=>'raw',
                'filter'=> dmstr\helpers\Html::activeInput('text', $modelPersona,'nro_documento',['class'=>'form-control']),
                'value' => function($model) {
                    return $model->alumno->grupofamiliar->apellidos . "<span class='text text-danger'> Folio:". $model->alumno->grupofamiliar->folio."</span>";
                },
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