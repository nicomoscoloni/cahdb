<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\ServicioTiket */

$this->title = 'Create Servicio Tiket';
$this->params['breadcrumbs'][] = ['label' => 'Servicio Tikets', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="servicio-tiket-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
