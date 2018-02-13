<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\EgresoFondoFijo */

$this->title = 'Update Egreso Fondo Fijo: {nameAttribute}';
$this->params['breadcrumbs'][] = ['label' => 'Egreso Fondo Fijos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="egreso-fondo-fijo-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
