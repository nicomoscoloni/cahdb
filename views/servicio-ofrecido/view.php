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
<div class="box box-solid box-colegio">
    <div class="box-header with-border">
        <i class="fa fa-cogs"></i> <h3 class="box-title"> Detalle Servicio </h3> 
    </div>
    <div class="box-body">   

    <p>
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
        <div class="row"> <!-- row dettales delconvenio -->
            <div class="col-sm-8 col-sm-offset-2 col-xs-12">
                <table>
                    <tr>
                        <td width="25%"> 
                            <img class="img-responsive"  src="<?php echo Yii::getAlias('@web') . "/images/servicios1.png"; ?>" alt="cp_dollar" />  
                        </td>
                        <td width="60%">
                            <h3 class="text-light-blue bold"> <?php echo $model->nombre."  ".$model->descripcion; ?> </h3>                            
                            <span class="text-light-blue bold">  Categoria: </span> <?php echo $model->tiposervicio->descripcion; ?> <br /> 
                            <span class="text-light-blue bold">  Importes:  </span> <?php echo $model->importe . "  -  (H.Profesores: ".$model->importe_hijoprofesor.")";?> <br />
                            <span class="text-light-blue bold">  Periodo: </span> <?php echo $model->detallePeriodo; ?> <br />
                            <span class="text-light-blue bold">  Vencimiento Pago: </span> <?php echo Yii::$app->formatter->asDate($model->fecha_vencimiento); ?> <br />
                                             
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