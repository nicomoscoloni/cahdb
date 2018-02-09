<?php

use yii\base\view;
use kartik\widgets\ActiveForm;
use dmstr\helpers\Html;
use yii\helpers\Url;

$this->title = 'Cobro de Servicios';
?>

<?php
if ($oper==1){
   $urlCobro = 'cobro-servicios'; 
}else
if ($oper==2){
    $urlCobro = 'cobro-ingreso'; 
}
?>

<div class="box box-solid box-colegio">
    <div class="box-header with-border">
        <i class="fa fa-money"></i><h3 class="box-title"> Cobro Servicios </h3>
    </div>    
    <div class="box-body">
        
        <div class="row">
           <?php echo $this->render('_searchFamilia',['searchModel'=>$searchGrupoFamiliar]); ?>
        </div><!-- search-form -->
        
        <div class="row">
            <div class="col-sm-12">
                <?php yii\widgets\Pjax::begin(['id'=>'pjax-familias']); ?>
                     <?=  \yii\grid\GridView::widget([
                     'id'=>'grid-familias',
                    'dataProvider' => $dataFamilias,                                       
                    'columns' => [                        
                        'apellidos',
                        'descripcion',
                        'folio',
                        'miResponsableCabecera',
                        'cantidadHijos',
                        ['class' => 'yii\grid\ActionColumn',
                        'template'=>'{cobrar}',
                        'headerOptions' => ['width' => '30'],
                        'buttons' => 
                           [
                           'cobrar' => function ($url, $model) use ($urlCobro){                                
                                            return Html::a( '<i class="glyphicon glyphicon-usd"></i>',
                                                                   [$urlCobro, 'cliente'=>$model['id']],
                                                                   ['class'=>'btn btn-xs btn-warning']
                                                           );
                                   },
                            ],
                        ],
                    ],
                ]); ?>
                <?php yii\widgets\Pjax::end(); ?>    
            </div>
        </div>    
        
    </div>
</div>