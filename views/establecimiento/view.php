<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;
use yii\web\View;
use yii\widgets\Pjax;

use app\assets\EstablecimientoAssets;
EstablecimientoAssets::register($this);

/* @var $this yii\web\View */
/* @var $model common\models\Establecimiento */

$this->title = "Establecimiento " . $model->nombre;
$this->params['breadcrumbs'][] = ['label' => 'Establecimientos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box box-solid box-colegio" id="establecimiento-view">
    <div class="box-header with-border">
           <i class="fa fa-university"></i> <h3 class="box-title"> Establecimiento:  <?= $model-> nombre; ?></h3> 
    </div>
    <div class="box-body">        
        <p>
            <?php
            if(Yii::$app->user->can('gestionarDivisionEscolar')){
                echo Html::a('<i class="fa fa-pencil"></i>   Actualizar', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']);
                echo Html::a('<i class="fa fa-trash-o"></i>   Eliminar', ['delete', 'id' => $model->id], [
                        'class' => 'btn btn-danger',
                        'data' => [
                            'confirm' => 'Está seguro que desea eliminar el Establecimiento?',
                            'method' => 'post',
                        ],
                    ]); 
                    } ?>
            
            <?php
            if(Yii::$app->user->can('gestionarDivisionEscolar'))
                echo Html::button('<i class="fa fa-pencil"></i>   Divisiones', ['id' => $model->id, 'class' => 'btn btn-primary',
                   'onclick'=>'js:establecimiento.misDivisionesEscolares("'.Url::to(['establecimiento/mis-divisiones-escolares','establecimiento'=>$model->id]) .'");']) ?>
            <?php
            
                echo Html::button('<i class="fa fa-pencil"></i>   Alumnos', ['id' => $model->id, 'class' => 'btn btn-primary',
                   'onclick'=>'js:establecimiento.misAlumnos("'.Url::to(['establecimiento/mis-alumnos','establecimiento'=>$model->id]) .'");']) ?>
            
                   
            <?php
            if(Yii::$app->user->can('perServiciosEstablecimientos'))
                echo Html::button('<i class="fa fa-pencil"></i>   Servicios', ['id' => $model->id, 'class' => 'btn btn-primary',
                   'onclick'=>'js:establecimiento.misServicios("'.Url::to(['establecimiento/mis-servicios-ofrecidos','establecimiento'=>$model->id]) .'");']) ?>
        </p>
        <div class="row"> <!-- row dettales delconvenio -->
            <div class="col-sm-8 col-sm-offset-2 col-xs-12">
                <table>
                    <tr>
                        <td width="25%"> 
                            <img class="img-responsive" src="<?php echo Yii::getAlias('@web') . "/images/escuela.png"; ?>" alt="cp_dollar" />  
                        </td>
                        <td width="60%">
                            <h3 class="text-light-blue bold"> <?php echo $model->nombre; ?> </h3>
                            <span class="text-light-blue bold">  Fecha Apertura: </span> <?php echo \Yii::$app->formatter->asDate($model->fecha_apertura); ?><br /> 
                            <span class="text-light-blue bold">  Dirección: </span> <?php echo $model->calle; ?> <br />
                            <span class="text-light-blue bold">  Teléfono: </span> <?php echo $model->telefono; ?> <br />
                            <span class="text-light-blue bold">  Nivel Educativo: </span> <?php echo $model->nivel_educativo; ?> <br />
                        </td>
                    </tr>                  
                </table>          
            </div>
        </div> <!-- fin row dettales del establecimiento -->
        
        <div id="dataestablecimiento">
        </div>
        
    </div>
</div>
