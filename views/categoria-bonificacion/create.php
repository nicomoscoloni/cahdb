<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\CategoriaBonificacion */

$this->title = 'Create Categoria Bonificacion';
$this->params['breadcrumbs'][] = ['label' => 'Categoria Bonificacions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="categoria-bonificacion-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
