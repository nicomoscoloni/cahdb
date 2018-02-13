<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\EgresoFondoFijo */

$this->title = 'Create Egreso Fondo Fijo';
$this->params['breadcrumbs'][] = ['label' => 'Egreso Fondo Fijos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="egreso-fondo-fijo-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
