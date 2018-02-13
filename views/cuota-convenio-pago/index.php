<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\search\CuotaConvenioPagoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Cuota Convenio Pagos';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cuota-convenio-pago-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Cuota Convenio Pago', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'id_conveniopago',
            'fecha_establecida',
            'nro_cuota',
            'monto',
            // 'estado',
            // 'id_tiket',
            // 'importe_abonado',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
