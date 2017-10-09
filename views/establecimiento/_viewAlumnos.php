<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;

use app\assets\CRUDAjaxAsset;
CRUDAjaxAsset::register($this);
?>
<br />
<div class="row">
    <div class="col-xs-12">
        <div class="box box-warning alumnos-establecimiento">
            <div class="box-header with-border">
            </div>
            <div class="box-body">
                <div class="pull-right">
                    <p>                   
                        <?= Html::a('<i class="fa fa-plus-square"></i> Cargar', ['alumno/empadronamiento'], ['class' => 'btn btn-primary']);
                        ?>
                        <?= Html::button('<i class="glyphicon glyphicon-download"> </i> Listado', ['class' => 'btn btn-success', 'id' => 'btn-excel',
                            'onclick' => 'js:{downListado("' . Url::to(['alumno/exportar-excel']) . '");}'])
                        ?>

                    </p>
                </div>
                <div>
                    <?php
                    Pjax::begin([
                        'id' => 'pjax-alumnos',                       
                        'enablePushState' => false,
                        'timeout' => false,
                    ]);
                    ?>    
                    <?=
                    GridView::widget([
                        'dataProvider' => $dataProviderAlumnos,
                        'filterModel' => $searchModelAlumnos,
                        'columns' => [
                            'nro_legajo',
                            [
                                'label' => 'Documento',
                                'attribute' => 'documento',
                                'filter' => dmstr\helpers\Html::activeInput('text', $modelPersona, 'nro_documento', ['class' => 'form-control']),
                                'value' => function($model) {
                                    return $model->miPersona->nro_documento;
                            },
                            ],
                            [
                                'label' => 'Apellido',
                                'attribute' => 'apellido',
                                'filter' => dmstr\helpers\Html::activeInput('text', $modelPersona, 'apellido', ['class' => 'form-control']),
                                'value' => function($model) {
                                    return $model->miPersona->apellido;
                            },
                            ],
                            [
                                'label' => 'Nombre',
                                'attribute' => 'nombre',
                                'filter' => dmstr\helpers\Html::activeInput('text', $modelPersona, 'nombre', ['class' => 'form-control']),
                                'value' => function($model) {
                                    return $model->miPersona->nombre;
                            },
                            ],
                            [
                                'label' => 'Familia',
                                'attribute' => 'familia',
                                'value' => function($model) {
                                    return $model->miGrupofamiliar->apellidos;
                            },
                            ],
                            [
                                'label' => 'División',
                                'attribute' => 'id_divisionescolar',
                                'filter' => dmstr\helpers\Html::activeDropDownList($searchModelAlumnos, 'id_divisionescolar', \app\models\DivisionEscolar::getDivisionesEstablecimiento($establecimiento), ['prompt' => '', 'class' => 'form-control']),
                                'value' => function($model) {
                                    return $model->idDivisionescolar->nombre;
                            },
                            ],
                            ['class' => 'yii\grid\ActionColumn',
                                'header' => 'Actions',
                                'headerOptions' => ['style' => 'color:#337ab7'],
                                'template' => '{view}',
                                'buttons' => [
                                    'view' => function ($url, $model) {
                                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', Url::to(['alumno/view','id'=>$model->id]), ['title' => Yii::t('app', 'DETALLES ALUMNO')]);
                                    },
                                ],
                            ],
                            ],
                                    ]);
                    ?>
                    <?php Pjax::end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>