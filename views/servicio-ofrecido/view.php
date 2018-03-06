<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use yii\widgets\Pjax;

use app\widgets\buscadorServiciosAlumno;

/* @var $this yii\web\View */
/* @var $model app\models\ServicioOfrecido */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Servicio Ofrecidos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box box-solid box-colegio ">
    <div class="box-header with-border">
        <i class="fa fa-cogs"></i> <h3 class="box-title"> Detalle Servicio </h3> 
    </div>
    <div class="box-body">   

        <p class="actionsservicio">
        <?= Html::a('<i class="fa fa-pencil"></i> Actualizar', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('<i class="fa fa-trash-o"></i> Eliminar', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Está seguro que desea eliminar el servicio?',
                'method' => 'post',
            ],
        ]) ?> 
        <?php
        if(Yii::$app->user->can('devengarServicioOfrecido')){
            echo Html::button('<span class="glyphicon glyphicon-piggy-bank"> </span> Devengar Servicios', ['value'=> yii\helpers\Url::to(['devengar-servicio', 'id' => $model->id]), 
                                 'class'=>'btn btn-success', 'id'=>'btn-devenarServicio']); 
            echo " ";
            echo Html::button('<span class="glyphicon glyphicon-remove-circle"> </span> Quitar Devengamiento', ['value'=> yii\helpers\Url::to(['eliminar-devengamiento', 'id' => $model->id]), 
                                 'class'=>'btn btn-success', 'id'=>'btn-eliminardevengamiento']);
        }
                             
        ?>

    </p>
        <div class="row" id="dataservicio"> <!-- row dettales delconvenio -->
            <div class="col-sm-8 col-sm-offset-2 col-xs-12">
                <table>
                    <tr>
                        <td width="25%">                      
                        </td>
                        <td width="60%">
                            <h3 class="text-light-blue text-bold"> <?php echo $model->nombre."  ".$model->descripcion; ?> </h3>                            
                            <span class="text-light-blue text-bold">  Categoria: </span> <?php echo $model->tiposervicio->descripcion; ?> <br /> 
                            <span class="text-light-blue text-bold">  Importes:  </span> <?php echo $model->importe . "  -  (H.Profesores: ".$model->importe_hijoprofesor.")";?> <br />
                            <span class="text-light-blue text-bold">  Periodo: </span> <?php echo $model->detallePeriodo; ?> <br />
                            <span class="text-light-blue text-bold">  Vencimiento Pago: </span> <?php echo Yii::$app->formatter->asDate($model->fecha_vencimiento); ?> <br />
                            <span class="text-light-blue text-bold">  Divisiones Asociadas: </span> <?= $model->cantDivisionesAsociadas; ?> <br />
                            <input type="hidden" name="cantdivisionesasociadas" id="cantdivisionesasociadas" value="<?= $model->cantDivisionesAsociadas; ?>" />
                        </td>
                    </tr>                  
                </table>          
            </div>
        </div> <!-- fin row dettales del establecimiento -->
        <br /> 
        <?php echo app\widgets\buscadorServiciosAlumno\BuscadorServiciosAlumno::widget(['searchModel' => $searchModelSerAlumnos,'dataProvider'=>$dataProviderSerAlumnos]); ?>

    
    </div>
</div>
<?php 
    $this->registerJsFile('@web/js/devengamiento-servicio-ofrecido.js', ['depends'=>[app\assets\AppAsset::className()]]);
?>
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
                intro: 'Infomación y gestión del servicio.'
            },
            {
                element: document.querySelector('#dataservicio'),
                intro: 'Información basica.'
            },
            {
                element: document.querySelector('.actionsservicio'),
                intro: 'Botonera para su gestión.'
            },
            {
                element: document.querySelector('#btn-devenarServicio'),
                intro: 'Botón para iniciar el devengamiento.'
            },
            {
                element: document.querySelector('#btn-eliminardevengamiento'),
                intro: 'Botón para quitar el devengamiento.'
            },          
            
            {
                element: document.querySelector('#seachSA'),
                intro: 'Listan los servicios de los alumos a cuales se devengo el servicio actual.'
            }               
            
        ]
      });
      intro.start();
} 
", \yii\web\View::POS_END,'ayuda');
?>