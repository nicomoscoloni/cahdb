<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\DebitoAutomatico */

$this->title = 'Generación Debitos Autoáticos';

?>
<div class="box box-solid box-colegio" id="debito-automatico-create">
    <div class="box-header with-border">
        <i class="fa  fa-user-plus"></i><h3 class="box-title"> Debitos Automaticos </h3>
    </div>
    <div class="box-body">    
        <?= $this->render('_form', [
            'model' => $model,
        ]) ?>        
    </div>
</div>
