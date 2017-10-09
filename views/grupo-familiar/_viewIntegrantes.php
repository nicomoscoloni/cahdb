<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\web\View;
use yii\widgets\Pjax;

use app\assets\CRUDAjaxAsset;
CRUDAjaxAsset::register($this);
?>


<div class="row">
    <div class="col-xs-12">

        <?php
       
        //if(Yii::$app->user->can('abmlResponsables')){
            echo "<span class='title-h3'> Responsables </span>" . 
                Html::button('<i class="fa fa-share-square-o"></i> Asignar Responsable', 
                        ['value' => Url::to(['grupo-familiar/asignar-responsable', 'familia' => $familia]), 
                         'class' => 'btn btn-success btn-xs', 'id' => 'btn-asignar-responsable']);
       // }
        
        
        
        Pjax::begin(['id' => 'pjax-responsable',
            'enablePushState' => false,
            'timeout' => false]);

        echo GridView::widget([
            'id' => 'grid-responsables',
            'dataProvider' => $dataProviderResponsables,
            'columns' => [
                    [
                    'label' => 'T.Responsable',
                    'value' => function($model) {
                        return $model->tipoResponsable->nombre;
                    },
                ],
                    [
                    'label' => 'Documento',
                    'value' => function($model) {
                        return $model->miPersona->nro_documento;
                    },
                ],
                    [
                    'label' => 'Apellido',
                    'value' => function($model) {
                        return $model->miPersona->apellido;
                    },
                ],
                    [
                    'label' => 'Nombre',
                    'value' => function($model) {
                        return $model->miPersona->nombre;
                    },
                ],
                    [
                    'label' => 'Domicilio',
                    'value' => function($model) {
                        return $model->miPersona->miDomicilio;
                    },
                ],
                    [
                    'label' => 'F.Nac',
                    'value' => function($model) {
                        return Yii::$app->formatter->asDate($model->miPersona->fecha_nacimiento);
                    },
                ],
                    [
                    'label' => 'Telefonos',
                    'value' => function($model) {
                        return $model->miPersona->miTelContacto;
                    },
                ],
                    ['class' => 'yii\grid\ActionColumn',
                    'template' => '{update} {delete}',
                    'headerOptions' => ['width' => '70'],
                    'buttons' =>
                        [
                        'update' => function ($url, $model) {
                            return Html::a('<i class="glyphicon glyphicon-pencil"></i>', ['grupo-familiar/actualizar-responsable', 'id' => $model['id']], ['class' => 'btn btn-xs btn-warning editAjax',
                                        'onclick' => 'js:{actualizarResponsable("' . Url::to(['grupo-familiar/actualizar-responsable', 'id' => $model['id']]) . '","#pjax-responsable"); return false;}']
                            );
                        },
                        'delete' => function ($url, $model) {
                            return Html::a('<i class="glyphicon glyphicon-remove"></i>', ['grupo-familiar/quitar-responsable', 'id' => $model['id']], ['class' => 'btn btn-xs btn-danger',
                                        'onclick' => 'js:{quitarResponsable("' . Url::to(['grupo-familiar/quitar-responsable', 'id' => $model['id']]) . '"); return false;}']
                            );
                        },
                    ],
                   // 'visible' => Yii::$app->user->can('abmlResponsables')            
                ],
            ],
        ]);
        Pjax::end();
        ?>
        <input type="hidden" name="familia" id="familia" value="<?= $familia; ?>" />

        <?php
            echo "<h3> Alumnos</h3>";
        Pjax::begin(['id' => 'pjax-alumnos',
            'enablePushState' => false,
            'timeout' => false]);
        echo GridView::widget([
            'id' => 'grid-alumnos',
            'summary' => '',
            'dataProvider' => $dataProviderAlumnos,
            'columns' => [
                    [
                    'label' => 'Documento',
                    'value' => function($model) {
                        return $model->miPersona->nro_documento;
                    },
                ],
                [
                    'label' => 'Apellido',
                    'value' => function($model) {
                        return $model->miPersona->apellido;
                    },
                ],
                    [
                    'label' => 'Nombre',
                    'value' => function($model) {
                        return $model->miPersona->nombre;
                    },
                ],
                    [
                    'label' => 'Domicilio',
                    'value' => function($model) {
                        return $model->miPersona->miDomicilio;
                    },
                ],
                    [
                    'label' => 'Nacimiento',
                    'value' => function($model) {
                        return Yii::$app->formatter->asDate($model->miPersona->fecha_nacimiento);
                    },
                ],
                    [
                    'label' => 'Division',
                    'value' => function($model) {
                        return $model->miDivisionescolar->nombre . " - " . $model->miDivisionescolar->idEstablecimiento->nombre;
                    },
                ],
                    ['class' => 'yii\grid\ActionColumn',
                    'template' => '{view}',
                    'buttons' =>
                        [
                        'view' => function ($url, $model) {
                            return Html::a('<i class="glyphicon glyphicon-eye-open"></i>', ['alumno/view', 'id' => $model['id']], []
                            );
                        },
                    ],
                                'visible' => Yii::$app->user->can('abmlAlumnos')
                ],
            ],
        ]);
        ?>
        <?php Pjax::end(); ?>
    </div>
</div>