<?php
use dmstr\helpers\Html;
?>
<div class="row form-group groupcuota" id="divcuota-<?= $ordn; ?>">
    <div class="col-sm-5">        
        <div class="input-group">
            <span class="input-group-addon"> Fecha Pago </span>
                <?php
                    echo \kartik\date\DatePicker::widget([
                        'model' => $model,
                        'attribute' => "[$ordn]xfecha_establecida",
                      
                        'pluginOptions' => [
                            'autoclose'=>true,
                            'format' => 'dd-mm-yyyy'
                        ],
                        'language' => 'es',

                    ]);
                ?>
        </div>
        <?= Html::error($model, "[$ordn]fecha_establecida",['class'=>'text-error text-red']); ?>
    </div>
    <div class="col-sm-3">        
        <div class="input-group">
            <span class="input-group-addon"> $ </span>
            <?=  Html::activeInput('text', $model, "[$ordn]monto",['class'=>'form-control']); ?>
        </div>
        <?= Html::error($model, "[$ordn]monto",['class'=>'text-error text-red']); ?>
    </div>
    <div class="col-sm-1">
        <button type="button" class="btn btn-xs btn-danger" onclick="eliminarcuota(<?= $ordn; ?>);" ><i class="fa fa-remove"></i></button>
    </div>
    <div class="col-sm-12">
    <hr />    
    </div>
    
</div>

