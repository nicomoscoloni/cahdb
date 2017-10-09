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
    <strong> Asociaciòn Exalumnos Don Bosco.</strong>
    
</footer>

<!-- Control Sidebar -->
<aside class="control-sidebar control-sidebar-dark">
    
    <section class="sidebar">
       
        <?= dmstr\widgets\Menu::widget(
            [
                'options' => ['class' => 'sidebar-menu'],
                'items' => [ 
                    [
                        'label' => 'Configuraciones',
                        'icon' => 'cogs',
                        'url' => '#',
                        'items' => [
                            ['label' => 'Tipo Documentos', 'icon' => 'arrow-right', 'url' => ['tipo-documento/index'],'visible'=>Yii::$app->user->can('gestionarDocumentos')],
                            ['label' => 'Tipo Sexos', 'icon' => 'arrow-right', 'url' => ['tipo-sexo/index'],'visible'=>Yii::$app->user->can('gestionarSexos')],
                            ['label' => 'Tipo Responsables', 'icon' => 'arrow-right', 'url' => ['tipo-responsable/index'],'visible'=>Yii::$app->user->can('gestionarResponsables')],
                            ['label' => 'Bonificaciones', 'icon' => 'arrow-right', 'url' => ['categoria-bonificacion/index'],'visible'=>Yii::$app->user->can('gestionarCategoriaDescuentos')],
                            ['label' => 'Forma Pago', 'icon' => 'arrow-right', 'url' => ['forma-pago/index'],'visible'=>Yii::$app->user->can('gestionarFormasPago')],
                            ['label' => 'Servicios Cobro', 'icon' => 'arrow-right', 'url' => ['tipo-servicio/index'],'visible'=>Yii::$app->user->can('gestionarCatServOfrecidos')],
                            ['label' => 'Servicios Pago ', 'icon' => 'arrow-right', 'url' => ['servicio-habilitado-pago/index'],'visible'=>Yii::$app->user->can('gestionarCatServPago')],

                           
                        ],
                    ], 
                    [
                        'label' => 'Usuarios',
                        'icon' => 'cogs',
                        'url' => '#',
                        'items' => [
                            ['label' => 'Nuevo Usuario', 'icon' => 'arrow-right', 'url' => ['user/registration/register'],],
                            ['label' => 'Index', 'icon' => 'arrow-right', 'url' => ['/user/admin/index'],],
                        ],
                        'visible' => Yii::$app->user->can('adminSistema')
                    ],                     
                ],
            ]
        ) ?>

    </section>
</aside><!-- /.control-sidebar -->
<!-- Add the sidebar's background. This div must be placed
     immediately after the control sidebar -->
<div class='control-sidebar-bg'></div>