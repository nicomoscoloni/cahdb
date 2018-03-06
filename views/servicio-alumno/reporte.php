<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\ServicioAlumnoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Servicio Alumnos';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box box-colegio">
    <div class="box-header with-border">
        <i class="fa fa-university"></i> <h3 class="box-title"> Reporte se Servicios Brindados </h3>       
    </div>
    <div class="box-body">         
        <?php echo app\widgets\buscadorServiciosAlumno\BuscadorServiciosAlumno::widget(['searchModel' => $searchModel,'dataProvider'=>$dataProvider]); ?>
    </div>
</div>
<?php
$this->registerJs("      
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
                intro: 'Reporte de Servicios brindados a Alumnos.'
            },
            { 
                element: document.querySelector('#seachSA'),
                intro: 'Filtros para realizar busquedas especificas, puede especificar más de un dato.'
            },
            
            {
                element: document.querySelector('#reporte-servicios-alumno'),
                intro: 'Resultado de la busqueda.'
            },
        ]
      });
      intro.start();
} 
", \yii\web\View::POS_END,'ayuda');
?>