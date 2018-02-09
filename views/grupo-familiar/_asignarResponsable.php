<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;


\yii\widgets\Pjax::begin(
 [
    'id'=>'pjax-persona-busqueda',
    'enablePushState' => false,
    'enableReplaceState' => false,
    'timeout'=>false,
]); 
?>


<input type='hidden' name='familia' id="familia" value="<?= $familia; ?>" />
        
<div class="row">
    <div class="col-xs-12">
        <?php   
               
                echo Html::button(' <i class="fa fa-plus-square"></i> ALTA', 
                        ['value'=> Url::to(['grupo-familiar/carga-responsable', 'idFamilia'=>$familia]),
                         'class' => 'btn btn-primary',
                         'onclick'=>'js:{cargarResponsable(this);}'    
                        ]); 
                    ?> 
       
    </div>
</div>
    
<div class="table-responsive">
    <?php echo GridView::widget([
        'id'=>'buscadorPersonaWidget',
        'dataProvider' => $dataProvider,        
        'filterModel' => $searchModel,
        'tableOptions' => ['class' => 'table table-striped table-bordered table-hover'],
        'headerRowOptions' => ['class'=>'x'],
        'columns' => [                
                
                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{select}',
                    'header'=>\dmstr\helpers\Html::dropDownList('tipores','',app\models\TipoResponsable::getTipoResponsables(),['class'=>'','id'=>'tipores']) ,
                    'buttons' => [                                    
                        'select' => function ($url, $model, $key) { 
                            return Html::button('Asignar', 
                                ['value'=> Url::to(['grupo-familiar/asignar-responsable','idresponsable'=>$model['id']]),
                                 'class' => 'btn btn-primary btn-xs',
                                 'onclick'=>'js:{asignarResponsable(this);}'    
                                ]);

                        }
                    ],                            
                ],    
                
                'nro_documento',                
                'apellido',
                'nombre',
        ],
    ]); ?>
</div>

</div>
<?php \yii\widgets\Pjax::end() ?>