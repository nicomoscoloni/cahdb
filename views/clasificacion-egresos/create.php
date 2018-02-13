<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\ClasificacionEgresos */

$this->title = 'Create Clasificacion Egresos';
$this->params['breadcrumbs'][] = ['label' => 'Clasificacion Egresos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="clasificacion-egresos-create">

 

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
