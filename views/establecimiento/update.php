<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Establecimiento */

$this->title = 'Actualizar Establecimiento: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Establecimientos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="box box-solid box-colegio establecimiento-update">
    <div class="box-header with-border">
        <i class="fa fa-university"></i> <h3 class="box-title"> Actualizacion Establecimiento </h3>
    </div>
    <div class="box-body">    

        <?= $this->render('_form', [
            'model' => $model,
        ]) ?>

    </div>
</div>
