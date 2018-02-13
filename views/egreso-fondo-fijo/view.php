<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\EgresoFondoFijo */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Egreso Fondo Fijos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="egreso-fondo-fijo-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'id_fondofijo',
            'id_clasificacionegreso',
            'fecha_compra',
            'proovedor',
            'descripcion',
            'importe',
            'nro_factura',
            'nro_rendicion',
            'bien_uso:boolean',
            'rendido:boolean',
        ],
    ]) ?>

</div>
