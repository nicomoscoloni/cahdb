<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;

use app\assets\AlumnoAssets;
AlumnoAssets::register($this);

/* @var $this yii\web\View */
/* @var $searchModel backend\models\AlumnoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Alumnos';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="box  box-colegio alumno-index">
    <div class="box-header with-border">
        <i class="fa fa-users"></i> <h3 class="box-title"> Administración de Alumnos </h3>    
    </div>
    <div class="box-body">
        <div class="pull-right"> 
            <?php
            if(Yii::$app->user->can('exportarAlumnos')){?>
                <p> 
                    <?= Html::button('<i class="glyphicon glyphicon-download"> </i> EXCEL', ['class' => 'btn btn-success', 'id'=>'btn-excel',
                            'onclick'=>'js:{downListado("'.Url::to(['alumno/exportar-excel']) .'");}']) ?>
                </p>
            <?php } ?>
        </div>
        <div class="row">   
    
          <div class="col-sm-12">  
            <?php Pjax::begin([
                'id'=>'pjax-alumnos',
                'enablePushState' => false,
                'timeout'=>false,
                ]); ?>    
              
            <?= GridView::widget([
                'id'=>'grid-alumnos',
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'rowOptions' => function ($model, $index, $widget, $grid){
                        if($model->activo == '0'){
                          return ['class' => 'alumnoinactivo'];
                        }
                      },
                'columns' => [ 
                    'nro_legajo',
                    [
                        'label' => 'Documento',
                        'attribute'=>'documento',
                        'filter'=> dmstr\helpers\Html::activeInput('text', $modelPersona,'nro_documento',['class'=>'form-control']),
                        'value' => function($model) {
                            return $model->miPersona->nro_documento;
                        },
                    ],
                    [
                        'label' => 'Apellido',
                        'attribute'=>'apellido',
                        'filter'=> dmstr\helpers\Html::activeInput('text', $modelPersona,'apellido',['class'=>'form-control']),
                        'value' => function($model) {
                            return $model->miPersona->apellido;
                        },
                    ],
                    [
                        'label' => 'Nombre',
                        'attribute'=>'nombre',
                        'filter'=> dmstr\helpers\Html::activeInput('text', $modelPersona,'nombre',['class'=>'form-control']),
                        'value' => function($model) {
                            return $model->miPersona->nombre;
                        },
                    ],
                    [
                        'label' => 'Familia',
                        'attribute'=>'familia',                         
                        'value' => function($model) {
                            return $model->miGrupofamiliar->apellidos;
                        },
                    ],  
                    [
                        'label' => 'Establecimiento',
                        'attribute'=>'establecimiento',    
                        'filter'=> dmstr\helpers\Html::activeDropDownList($searchModel, 'establecimiento', \app\models\Establecimiento::getEstablecimientos(),
                                ['prompt'=>'','class'=>'form-control'
                                ]),
                        'value' => function($model) {
                            return $model->divisionescolar->establecimiento->nombre;
                        },
                    ],              
                    [
                        'label' => 'Division',
                        'attribute'=>'id_divisionescolar',    
                        'filter'=> dmstr\helpers\Html::activeDropDownList($searchModel, 'id_divisionescolar', \app\models\DivisionEscolar::getDivisionesEscolares(),['prompt'=>'','class'=>'form-control']),
                        'value' => function($model) {
                            return $model->divisionescolar->nombre;
                        },
                    ],
                    [
                        'label' => 'H.P',
                        'attribute'=>'hijo_profesor',    
                        'filter'=> dmstr\helpers\Html::activeDropDownList($searchModel, 'hijo_profesor', ['0'=>'NO','1'=>'SI'],['prompt'=>'','class'=>'form-control']),
                        'value' => function($model) {
                            if($model->hijo_profesor=='0')
                                return "No";
                            else
                                return "Si";
                        },
                    ],
                    ['class' => 'yii\grid\ActionColumn',
                            'template'=>'{activar} {inactivar}',
                            'visibleButtons' => [                                   
                                    'activar' => Yii::$app->user->can('inactivarAlumno'),
                                    'inactivar' =>Yii::$app->user->can('activarAlumno'),
                                ],
                            'buttons' => 
                               [                            
                                'activar' => function ($url, $model) { 
                                            if($model->activo=='0'){
                                               return Html::a('<i class="glyphicon glyphicon-arrow-up"></i>',['alumno/activar', 'id'=>$model['id']],
                                                              ['class'=>'btn btn-xs btn-danger',                                                          
                                                               'onclick'=>'js:{activarAlumno("'.Url::to(['alumno/activar', 'id'=>$model['id']]) .'"); return false;}']
                                                               );
                                            }
                                        },
                                'inactivar' => function ($url, $model) {
                                            if($model->activo=='1'){
                                               return Html::a( '<i class="glyphicon glyphicon-arrow-down"></i>',
                                                                       ['alumno/inactivar', 'id'=>$model['id']],
                                                                       ['class'=>'btn btn-xs btn-waring',                                                                    
                                                                        'onclick'=>'js:{inactivarAlumno("'.Url::to(['alumno/inactivar', 'id'=>$model['id']]) .'"); return false;}']
                                                               );
                                            }
                                        },                

                                ],
                                'visible'=>Yii::$app->user->can('inactivarAlumno')||Yii::$app->user->can('activarAlumno'),
                        ],      
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'headerOptions' => ['width' => '50'],
                        'template'=>'{update} {view}',
                        'visibleButtons' => [                                   
                                    'update' => Yii::$app->user->can('cargarAlumno'),
                                    'view' =>Yii::$app->user->can('visualizarAlumno'),
                                ],
                        'buttons' => 
                        [
                        'update' => function ($url, $model) {                                
                                        return Html::a( '<i class="glyphicon glyphicon-pencil"></i>',
                                                               ['alumno/empadronamiento', 'id'=>$model['id']],
                                                               ['class'=>'']
                                                       );
                               }, 
                        ],
                                        'visible'=>Yii::$app->user->can('cargarAlumno')||Yii::$app->user->can('visualizarAlumno'),
                    ],
                                                
                    ],
                ]); ?>
            <?php Pjax::end(); ?>
          </div>
        </div>
    </div>
</div>

<style type="text/css">
    .alumnoinactivo{background-color: #F0D595 !important;}
</style>