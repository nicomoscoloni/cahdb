<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;

use app\assets\CRUDAjaxAsset;
CRUDAjaxAsset::register($this);

/* @var $this yii\web\View */
/* @var $searchModel backend\models\TipoDocumentoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->title = 'Gestión Documentos';
$this->params['breadcrumbs'][] = $this->title;

?>
<?= \app\widgets\modalcrud\ModalCrud::widget(['titulo'=>'Alta/Actualización Documentos']); ?>

<div id="tipo-documento-index">

    <div class="box box-success">
        <div class="box-header with-border">
            <i class="glyphicon glyphicon-wrench"></i> <h3 class="box-title"> Tipo Documentos </h3> 
        </div>
        <div class="box-body">
            <p class="pull-right">
            <?=  Html::a('<i class="glyphicon glyphicon-plus"></i>', ['create'], ['class' => 'btn btn-xs btn-success btn-alta','data-title'=>'Carga T.Documento',
                'onclick'=>'js:{cargaAjax("'.Url::to(['tipo-documento/create']) .'"); return false;}']); ?>
            </p>
            <?php Pjax::begin([
                    'id'=>'pjax-grid',
                    'enablePushState' => false,
                    ]); ?>    
            <?=   GridView::widget([
                    'id'=>'grid-tsexos',
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],
                        'nombre',
                        'siglas',
                        ['class' => 'yii\grid\ActionColumn',
                            'template'=>'{update} {delete}',
                            'buttons' => 
                               [
                               'update' => function ($url, $model) {                                
                                                return Html::a( '<i class="glyphicon glyphicon-pencil"></i>',
                                                                       ['create', 'id'=>$model['id']],
                                                                       ['class'=>'btn btn-xs btn-warning editAjax',
                                                                        'onclick'=>'js:{editAjax("'.Url::to(['create', 'id'=>$model['id']]) .'"); return false;}']
                                                               );
                                       },
                               'delete' => function ($url, $model) {                                
                                               return Html::a( '<i class="glyphicon glyphicon-remove"></i>',
                                                                       ['delete', 'id'=>$model['id']],
                                                                       ['class'=>'btn btn-xs btn-danger deleteAjax',
                                                                        'onclick'=>'js:{deleteAjax("'.Url::to(['delete', 'id'=>$model['id']]) .'"); return false;}']
                                                               );
                                       },                

                               ]   
                        ],
                    ],
                ]); ?>
            <?php Pjax::end(); ?>
        </div>
    </div>
</div>
<script type="text/javascript">
function ayuda(){         
    var intro = introJs();
      intro.setOptions({
        nextLabel: 'Siguiente',
        prevLabel: 'Anterior',
        skipLabel:'Terminar',
        doneLabel:'Cerrar',
        steps: [      
            { 
                intro: "Administración de Documentos."
            },  
            {
                element: document.querySelector('.grid-view .filters'),
                intro: "Filtros para realizar busquedas específicas, puede completar más de un dato."
            },            
            {
                element: document.querySelector('.grid-view tbody'),
                intro: "El resultado de la busqueda será desplegado en esta sección."
            },
            {
                element: document.querySelector('.btn-alta'),
                intro: "Si deséa realizar una nueva alta, presione sobre este botón."
            },
        ]
      });
      intro.start();
}      
</script>