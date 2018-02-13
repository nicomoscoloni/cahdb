<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\FondoFijo */

$this->title = 'Create Fondo Fijo';
$this->params['breadcrumbs'][] = ['label' => 'Fondo Fijos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box box-solid box-colegio fondofijo-create">
    <div class="box-header with-border">
        <i class="fa fa-university"></i> <h3 class="box-title"> Alta Fondo Fijo </h3>
    </div>
    <div class="box-body">    

        <?= $this->render('_form', [
            'model' => $model,
        ]) ?>

    </div>
</div>
