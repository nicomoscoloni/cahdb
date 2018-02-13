<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\ServicioOfrecido */

$this->title = 'Create Servicio Ofrecido';
$this->params['breadcrumbs'][] = ['label' => 'Servicio Ofrecidos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box box-solid box-colegio">
    <div class="box-header with-border">
        <i class="fa fa-cogs"></i> <h3 class="box-title"> Alta Servicios </h3> 
    </div>
    <div class="box-body">    

        <?= $this->render('_form', [
            'model' => $model,
        ]) ?>

    </div>
</div>