<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;
use yii\widgets\Pjax;
use yii\grid\GridView;

use app\assets\AlumnoAssets;
AlumnoAssets::register($this); 

/* @var $this yii\web\View */
/* @var $model common\models\Alumno */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Alumnos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?php    
    yii\bootstrap\Modal::begin([        
        'id'=>'modalBonificaciones',
        'header'=>'Asignación de Bonificación',
        'class' =>'modal-scrollbar', 
        'size' => 'modal-lg',
        ]);
        echo "<div id='modalContent'></div>";
    yii\bootstrap\Modal::end();
?>

<div id="alumno-view" class="box box-solid box-colegio">
    <div class="box-header with-border">
        <i class="fa fa-user-plus"></i> <h3 class="box-title"> Información Alumno </h3> 
    </div>
    <div class="box-body">
        
        <div class="row">
            <div class="col-sm-10 col-sm-offset-1">
                <table>
                    <tr>
                        <td width="30%"> 
                            <img class="img-responsive" src="<?php echo Yii::getAlias('@web') . "/images/family.png"; ?>" alt="familia" />  
                        </td>
                        <td width="60%">
                            <h3 class="text-light-blue bold">    Legajo: <?php echo $model->nro_legajo . "  ". $model->hijo_profesor; ?> </h3>
                            <span class="text-light-blue bold">  Apellido / Nombre: </span> <?php echo $model->persona->apellido ."; ".$model->persona->nombre; ?> <br />
                            <span class="text-light-blue bold">  Documento: </span> <?php echo $model->persona->nro_documento; ?> <br />
                            <span class="text-light-blue bold">  Establecimiento / División: </span> <?php echo $model->divisionescolar->establecimiento->nombre . "  - " . $model->divisionescolar->nombre; ?> <br />
                            <span class="text-light-blue bold">  Activo: </span> <?php echo $model->soyActivo; ?> <br />
                            <span class="text-light-blue bold">  Familia / Folio: </span> <?php echo Html::a("<i class='glyphicon glyphicon-eye-open'></i> ". $model->grupofamiliar->apellidos . " / " . $model->grupofamiliar->folio, ['grupo-familiar/view', 'id' => $model->id_grupofamiliar], ['class' => 'btn btn-info']); ?> <br />
                           
                            <br />
                            <?php
                            if(Yii::$app->user->can('cargarAlumno'))
                                echo Html::a('<i class="fa fa-pencil"></i>', ['empadronamiento', 'id' => $model->id], ['class' => 'btn btn-primary']); ?>
                            <?php 
                            if(Yii::$app->user->can('eliminarAlumno'))
                                echo Html::a('<i class="fa fa-trash-o"></i>', ['delete', 'id' => $model->id], [
                                    'class' => 'btn btn-danger',
                                    'data' => [
                                        'confirm' => 'Está seguro que desea eliminar al Alumno?',
                                        'method' => 'post',
                                    ],
                                ]); ?>
                            <?php
                            if(Yii::$app->user->can('gestionarBonificacionAlumno')){
                                echo Html::button('<i class="fa fa-share-square-o"></i>',
                                        ['value'=> Url::to(['alumno/asignar-bonificacion', 'alumno' => $model->id]), 
                                            'class' => 'btn btn-warning','id'=>'btn-asignar-bonificacion']);    
                            }
                            ?>
                        </td>
                    </tr>
                
                    <tr>
                        <td colspan="2">
                        <h3> Bonificaciones </h3>
                    
                        <?php Pjax::begin(
                                [
                                'id'=>'pjax-bonificaciones',
                                'enablePushState' => false,
                                'timeout'=>false,                                    
                                ]); ?>    
                        <?= GridView::widget([
                            'id'=>'grid-bonificaciones',
                            'summary'=>'',
                            'dataProvider' => $misBonificaciones,            
                            'columns' => [  
                                [
                                    'label' => 'Bonificacion',
                                    'value' => function($model) {
                                        return $model->bonificacion->descripcion;
                                    },
                                ],
                                [
                                    'label' => 'Valor',                                   
                                    'value' => function($model) {
                                        return $model->bonificacion->valor;
                                    },
                                ],                            
                                [
                                    'class' => 'yii\grid\ActionColumn',
                                    'template'=>'{quitar}',
                                    'buttons' => 
                                    [
                                    'quitar' => function ($url, $model) {                                
                                                    return Html::a( '<i class="glyphicon glyphicon-trash"></i>',
                                                                           ['alumno/quitar-bonificacion', 'id'=>$model['id']],
                                                                           ['class'=>'btn btn-xs btn-warning',
                                                                            'onclick'=>'js:{quitarBonificacion("'.Url::to(['alumno/quitar-bonificacion', 'id'=>$model['id']]) .'","#pjax-bonificaciones"); return false;}']
                                                                   );
                                            },
                                    ],
                                    'visible'=>Yii::$app->user->can('gestionarBonificacionAlumno'),
                                ],
                            ],
                        ]); ?>
                        <?php Pjax::end(); ?>  
                        </td>
                    </tr>
                    
                    
                    <tr>
                        <td colspan="2">
                        <h3> Servicios </h3>
                        <?= app\widgets\buscadorServiciosAlumno\BuscadorServiciosAlumno::widget(['searchModel' => $searchMisServicios,'dataProvider'=>$misServicios,'buscador'=>false]);
                        ?>
                        
                    </td>
                    </tr>
                    
                </table>              
            </div>
        </div>
        
    </div>
</div>