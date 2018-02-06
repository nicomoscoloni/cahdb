<aside class="main-sidebar">

    <section class="sidebar">
       
        <?= dmstr\widgets\Menu::widget(
            [
                'options' => ['class' => 'sidebar-menu tree', 'data-widget'=> 'tree'],
                'items' => [
                   /* [
                        'label' => 'Alumnos',
                        'icon' => 'users',
                        'url' => '#',
                        'items' => [
                            ['label' => 'Listado', 'icon' => 'arrow-right', 'url' => ['alumno/listado'], 'visible' => Yii::$app->user->can('listarAlumnos')],
                            ['label' => 'Carga Alumno', 'icon' => 'arrow-right', 'url' => ['alumno/empadronamiento'], 'visible' => Yii::$app->user->can('cargarAlumno')],
                            ['label' => 'Familias', 'icon' => 'arrow-right', 'url' => ['grupo-familiar/listado'], 'visible' => Yii::$app->user->can('listarFamilias')],                            
                        ],
                        'visible' => (Yii::$app->user->can('cargarAlumno') || Yii::$app->user->can('listarFamilias') || Yii::$app->user->can('listarAlumnos')),
                    ],
                    [   'label' => 'Establecimientos', 
                        'icon' => 'university', 
                        'url' => ['establecimiento/admin'],
                        'visible' => Yii::$app->user->can('gestionarEstablecimientos')
                    ],
                    [   'label' => 'Convenio Pago', 
                        'icon' => 'university', 
                        'url' => ['convenio-pago/administrar'],
                      //  'visible' => Yii::$app->user->can('gestionarEstablecimientos')
                    ],
                    ['label' => 'Debito Automatico', 'icon' => 'briefcase', 'url' => ['debito-automatico/admin']],
                    */
                    /*[
                        'label' => 'Servicios', 
                        'icon' => 'briefcase', 
                        'url' => ['servicio-ofrecido/admin'],
                        'visible'=>Yii::$app->user->can('abmlServicioOfrecido'),  
                    ],*/
                    [
                        'label' => 'Configuraciones',
                        'icon' => 'cogs',
                        'url' => '',
                        'items' => [
                            ['label' => 'Tipo Documentos', 'icon' => 'arrow-right', 'url' => ['/tipo-documento/index'],'visible'=>(Yii::$app->user->can('gestionarDocumentos'))],
                            ['label' => 'Tipo Sexos', 'icon' => 'arrow-right', 'url' => ['/tipo-sexo/index'],'visible'=>(Yii::$app->user->can('gestionarSexos'))],
                            ['label' => 'Forma Pago', 'icon' => 'arrow-right', 'url' => ['/forma-pago/index'],'visible'=>(Yii::$app->user->can('gestionarFormaPago'))],
                            ['label' => 'Tipo Responsables', 'icon' => 'arrow-right', 'url' => ['/tipo-responsable/index'],'visible'=>(Yii::$app->user->can('gestionarTipoResponsable'))],
                            ['label' => 'T.Servicios Cobro', 'icon' => 'arrow-right', 'url' => ['/categoria-servicio-ofrecido/index'],'visible'=>(Yii::$app->user->can('gestionarCategoriaServicios'))],
                           
                        ],
                        'visible'=>(Yii::$app->user->can('gestionarDocumentos') || Yii::$app->user->can('gestionarSexos') || Yii::$app->user->can('gestionarFormaPago') || Yii::$app->user->can('gestionarTipoResponsable') || Yii::$app->user->can('gestionarCategoriaServicios')),                         
                        
                    ],
                    [
                        'label' => 'Usuarios',
                        'icon' => 'cogs',
                        'url' => '#',
                        'items' => [
                            ['label' => 'Nuevo Usuario', 'icon' => 'arrow-right', 'url' => ['user/registration/register'],],
                            ['label' => 'Index', 'icon' => 'arrow-right', 'url' => ['/user/admin/index'],],
                        ],
                        //'visible' => Yii::$app->user->can('adminSistema')
                    ],       
                    
                ],
            ]
        ) ?>

    </section>

</aside>
