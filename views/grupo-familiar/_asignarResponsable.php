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
                    'headerOptions' => ['class'=>'cuadroselecion'],
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

<script type="text/javascript">
function ayudaAsignacionResponsable(){         
    var intro = introJs();
      intro.setOptions({
        nextLabel: 'Siguiente',
        prevLabel: 'Anterior',
        skipLabel:'Terminar',
        doneLabel:'Cerrar',
        steps: [      
            {
                element: document.querySelector('.grid-view .filters'),
                intro: "Filtros para realizar busquedas especificas, puede especificar mas de un dato."
            },            
            {
                element: document.querySelector('.cuadroselecion'),
                intro: "Medio pago adoptado."
            },
            
            {
                element: document.querySelector('#drop-menu-grupofamiliar'),
                intro: "Botonera opciones gestionar grupo familiar."
            }, 
            
            {
                element: document.querySelector('.box-alumno-familia'),
                intro: "Detalle de los alumnos cargados a la familia."
            },
            
            {
                element: document.querySelector('.box-responsbale-familia'),
                intro: "Detalle de los responsables cargados a la familia."
            },
            
            {
                element: document.querySelector('#btn-asignar-responsable'),
                intro: "Presione para dar de alta/asignar un nuevo responsable."
            },
            
            
        ]
      });
      intro.start();
}      
</script>