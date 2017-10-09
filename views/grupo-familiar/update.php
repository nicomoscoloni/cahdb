<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\GrupoFamiliar */

$this->title = 'Actualización Grupo Familiar: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Grupo Familiars', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="grupo-familiar-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
