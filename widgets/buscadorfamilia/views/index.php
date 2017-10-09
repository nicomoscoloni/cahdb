<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;


/**
 *
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var frontend\models\LegajoSearch $searchModel
 */

    \yii\widgets\Pjax::begin(
            [
                'id'=>'pjax-familia-busqueda',
                'enablePushState' => false,
                'enableReplaceState' => false,
                'timeout'=>false, 
            ]) 
    ?>
    
    <div class="clearfix crud-navigation">
       
    <div class="table-responsive">
        <?php echo GridView::widget([
                'id'=>'buscadorFamiliaWidget',
		'dataProvider' => $dataProvider,
		'pager' => [
			'class' => yii\widgets\LinkPager::className(),
			'firstPageLabel' => 'Primera',
			'lastPageLabel' => 'Ultima',
		],
		'filterModel' => $searchModel,
		'tableOptions' => ['class' => 'table table-striped table-bordered table-hover'],
		'headerRowOptions' => ['class'=>'x'],
		'columns' => [
			[
				'class' => 'yii\grid\ActionColumn',
                                'template' => '{view}',
				'buttons' => [
					'view' => function ($url, $model, $key) {
                                                $obarr = \yii\helpers\Json::encode(yii\helpers\ArrayHelper::toArray($model));                                                
                                                $expr = new yii\web\JsExpression($obarr);
						return Html::a('Seleccionar', null, [ 'class' => 'btn btn-primary btn-xs', 'onclick' => '(function ( $event ) { jQuery("body").trigger("familia:seleccionada", ['.$expr.']); })();' ]);
					}
				],
				'contentOptions' => ['nowrap'=>'nowrap']
			],
			'apellidos',
			'folio',
                        [
                            'label' => 'Responsable',
                            'attribute'=>'responsable',
                            'value' => function($model) {
                                return $model->miResponsableCabecera;
                            },
                        ],                
                                   
		],
	]); ?>
    </div>

</div>


<?php \yii\widgets\Pjax::end() ?>