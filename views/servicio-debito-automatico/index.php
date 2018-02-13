<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\search\ServicioDebitoAutomaticoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Servicio Debito Automaticos';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="servicio-debito-automatico-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Servicio Debito Automatico', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'id_debitoautomatico',
            'id_servicio',
            'tiposervicio',
            'resultado_procesamiento',
            // 'linea',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
