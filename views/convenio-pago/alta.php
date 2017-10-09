<?php

use kartik\form\ActiveForm;
use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\grid\GridView;
?>
<?php $this->title = 'Convenios Pagos';?>
<div class="box box-solid box-colegio">
    <div class="box-header with-border">
        <i class="fa fa-handshake-o fa-2"></i> <h3 class="box-title"> ALTA CONVENIO PAGO </h3>
    </div>
    <div class="box-body">


    <div id="form-cobroservicio" class="row">
        <div class="col-sm-8 col-sm-offset-2">        
            <div class="box box-warning">
                <div class="box-header with-border">
                    <i class="glyphicon glyphicon-search"></i><h3 class="box-title"> Busqueda Grupo Familiar </h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    </div>
                </div>
                <div class="box-body">

                    <?php $form = ActiveForm::begin(['id'=>'form-conveniopago',
                        'type' => ActiveForm::TYPE_HORIZONTAL,
                        'enableClientValidation' => false,
                        'formConfig' => ['labelSpan' => 3, 'deviceSize' => ActiveForm::SIZE_SMALL]]); ?>

                    <div class="row">
                        <div class="col-sm-8">
                            <?= $form->field($modelGrupoFamiliar, 'apellidos',['addon' => ['prepend' => ['content'=>'<i class="glyphicon glyphicon-user"></i>']]]) ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-8">
                            <?= $form->field($modelGrupoFamiliar, 'folio',['addon' => ['prepend' => ['content'=>'<i class="glyphicon glyphicon-user"></i>']]]) ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-8">
                            <?= $form->field($modelGrupoFamiliar, 'responsable',['addon' => ['prepend' => ['content'=>'<i class="glyphicon glyphicon-user"></i>']]]) ?>
                        </div>
                    </div>
                    <div>
                            <?= Html::submitButton('<i class=\'fa fa-search\'></i> Buscar', 
                            ['class' => 'btn btn-primary', 'id'=>'btn-envio']) ?>
                    </div>  

                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>    


    <?php
    Pjax::begin(['id'=>'pjax-clientes-convenio']); 
    echo GridView::widget([
        'id'=>'grid-clientes-convenio',
        'dataProvider' => $dataClientes,                
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'label' => 'Apellidos',
                'attribute'=>'apellidos',
                'value' => function($model) {
                    return $model->apellidos;
                },
            ],
            [
                'label' => 'Folio',
                'attribute'=>'folio',
                'value' => function($model) {
                    return $model->folio;
                },
            ],
            [
                'label' => 'Responsbale',
                'attribute'=>'responsable',
                'value' => function($model) {
                    return $model->miResponsableCabecera;
                },
            ],         
            ['class' => 'yii\grid\ActionColumn',
                'template'=>'{alta-cp}',
                'buttons' => 
                [
                'alta-cp' => function ($url, $model) {                                
                    return Html::a( '<i class="glyphicon glyphicon-ok-circle"></i>',
                                           ['convenio-pago/alta-servicios', 'familia'=>$model->id],
                                           []
                                );
                    },  
                ]   
            ],
        ],
    ]); 
    Pjax::end();
    ?>
        
    </div>
</div>

<style type="text/css">
    .table>tbody>tr:hover>td{
        background-color: rgba(155, 155, 169, 0.2) !important;
        cursor: pointer;
    }
</style>

