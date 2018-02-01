<?php
use yii\widgets\Breadcrumbs;
use dmstr\widgets\Alert;

?>
<div class="content-wrapper">
             <?php
    yii\bootstrap\Modal::begin([        
        'id'=>'ModalCrudAjax',
        'class' =>'modal-scrollbar',
        'size' => 'modal-lg',
        'footer' => '<button type="submit" class="btn btn-default btn-submit-form">ACEPTAR</button>',
    ]);
        echo "<div id='modalContent'></div>";
    yii\bootstrap\Modal::end();
    ?>
    <input type="hidden" id="grilla-ajax" name="grilla-ajax" value="" />
    
        <section class="content">       
            <?= $content ?>
        </section>
</div>

<footer class="main-footer">
    <div class="pull-right hidden-xs">
        <b>asdsad</b> 2.0
    </div>
    <strong> Asociación Exalumnos Don Bosco.</strong>
    
</footer>