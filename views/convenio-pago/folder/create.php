<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\ConvenioPago */

$this->title = 'Create Convenio Pago';
$this->params['breadcrumbs'][] = ['label' => 'Convenio Pagos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="convenio-pago-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
