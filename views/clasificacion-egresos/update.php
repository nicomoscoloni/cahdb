<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\ClasificacionEgresos */

$this->title = 'Update Clasificacion Egresos: {nameAttribute}';
$this->params['breadcrumbs'][] = ['label' => 'Clasificacion Egresos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="clasificacion-egresos-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
