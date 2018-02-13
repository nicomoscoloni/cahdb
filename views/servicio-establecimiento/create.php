<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\ServicioEstablecimiento */

$this->title = 'Create Servicio Establecimiento';
$this->params['breadcrumbs'][] = ['label' => 'Servicio Establecimientos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="servicio-establecimiento-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
