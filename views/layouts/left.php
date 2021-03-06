<aside class="main-sidebar">

    <section class="sidebar">
       
        <?= dmstr\widgets\Menu::widget(
            [
                'options' => ['class' => 'sidebar-menu tree', 'data-widget'=> 'tree'],
                'items' => [
                    [
                        'label' => 'Alumnos',
                        'icon' => 'users',
                        'url' => '#',
                        'items' => [
                            ['label' => 'Listado', 'icon' => 'arrow-right', 'url' => ['/alumno/listado'], 'visible' => Yii::$app->user->can('listarAlumnos')],
                            ['label' => 'Carga Alumno', 'icon' => 'arrow-right', 'url' => ['/alumno/empadronamiento'], 'visible' => Yii::$app->user->can('cargarAlumno')],
                            ['label' => 'Familias', 'icon' => 'arrow-right', 'url' => ['/grupo-familiar/listado'], 'visible' => Yii::$app->user->can('listarFamilias')],                            
                        ],
                        'visible' => (Yii::$app->user->can('cargarAlumno') || Yii::$app->user->can('listarFamilias') || Yii::$app->user->can('listarAlumnos')),
                    ],
                    [   'label' => 'Establecimientos', 
                        'icon' => 'university', 
                        'url' => ['/establecimiento/admin'],
                        'visible' => Yii::$app->user->can('listarEstablecimientos')
                    ],
                    [
                        'label' => 'Caja',
                        'icon' => 'dollar',
                        'url' => '#',
                        'items' => [
                            ['label' => 'Cobrar Servicios', 'icon' => 'arrow-right', 'url' => ['/caja/cobrar','oper'=>'1'],],
                            ['label' => 'Cobrar Ingreso', 'icon' => 'arrow-right', 'url' => ['/caja/cobrar','oper'=>'2'],],
                            
                        ],
                        'visible' => Yii::$app->user->can('cobrarServicios')
                    ],
                    [
                        'label' => 'Cuentas',
                        'icon' => 'share',
                        'url' => '#',
                        'items' => [
                            ['label' => 'Cuentas', 'icon' => 'arrow-right', 'url' => ['/cuentas/listado'],],
                        ],
                        'visible' => Yii::$app->user->can('visualizarCuentas')
                    ],/*
                    [   'label' => 'Fondo Fijo', 
                        'icon' => 'handshake-o', 
                        'url' => ['/fondo-fijo/listado'],
                        //'visible' => Yii::$app->user->can('listarFondosFijos')
                    ],*/
                    [   'label' => 'Convenio Pago', 
                        'icon' => 'handshake-o', 
                        'url' => ['/convenio-pago/administrar'],
                        'visible' => Yii::$app->user->can('gestionarConvenioPago')
                    ],
                    [   'label' => 'Débito Automático', 
                        'icon' => 'credit-card', 
                        'url' => ['/debito-automatico/administrar'],
                        'visible' => Yii::$app->user->can('gestionarDebitoAutomatico')
                    ],
                    [
                        'label' => 'Reportes',
                        'icon' => 'bar-chart',
                        'url' => '#',
                        'items' => [
                            ['label' => 'Servicios Brindados', 'icon' => 'fa fa-arrow-right', 'url' => ['/servicio-alumno/reporte']],        
                            ['label' => 'Alumnos con Bonificación', 'icon' => 'fa fa-arrow-right', 'url' => ['/reporte/bonificaciones-alumno']],         
                        ],
                        'visible' => Yii::$app->user->can('visualizarReportes')
                    ],
                    [
                        'label' => 'Servicios', 
                        'icon' => 'briefcase', 
                        'url' => ['/servicio-ofrecido/admin'],
                        'visible'=>Yii::$app->user->can('gestionarServicios'),  
                    ],
                    [
                        'label' => 'Configuraciones',
                        'icon' => 'cogs',
                        'url' => '',
                        'items' => [
                            ['label' => 'Tipo Documentos', 'icon' => 'arrow-right', 'url' => ['/tipo-documento/index'],'visible'=>(Yii::$app->user->can('gestionarDocumentos'))],
                            ['label' => 'Tipo Sexos', 'icon' => 'arrow-right', 'url' => ['/tipo-sexo/index'],'visible'=>(Yii::$app->user->can('gestionarSexos'))],
                            ['label' => 'Forma Pago', 'icon' => 'arrow-right', 'url' => ['/forma-pago/index'],'visible'=>(Yii::$app->user->can('gestionarFormaPago'))],
                            ['label' => 'Tipo Responsables', 'icon' => 'arrow-right', 'url' => ['/tipo-responsable/index'],'visible'=>(Yii::$app->user->can('gestionarTipoResponsable'))],
                            ['label' => 'Bonificaciones', 'icon' => 'arrow-right', 'url' => ['/categoria-bonificacion/index'],'visible'=>(Yii::$app->user->can('gestionarCategoriaDescuentos'))],
                            ['label' => 'T.Servicios Cobro', 'icon' => 'arrow-right', 'url' => ['/categoria-servicio-ofrecido/index'],'visible'=>(Yii::$app->user->can('gestionarCategoriaServicios'))],
                            //['label' => 'Egresos Fondo Fijo', 'icon' => 'arrow-right', 'url' => ['/clasificacion-egresos/index'],'visible'=>(Yii::$app->user->can('gestionarClasificacionEgresosFondoFijo'))],
                        ],
                        'visible'=>(Yii::$app->user->can('gestionarDocumentos') || Yii::$app->user->can('gestionarSexos') || Yii::$app->user->can('gestionarFormaPago') || Yii::$app->user->can('gestionarTipoResponsable') || Yii::$app->user->can('gestionarCategoriaServicios') ||  Yii::$app->user->can('gestionarCategoriaServicios') ||  Yii::$app->user->can('gestionarClasificacionEgresosFondoFijo')),                         
                        
                    ],
                    [
                        'label' => 'Usuarios',
                        'icon' => 'users',
                        'url' => '#',
                        'items' => [
                            ['label' => 'Nuevo Usuario', 'icon' => 'arrow-right', 'url' => ['user/registration/register'],],
                            ['label' => 'Index', 'icon' => 'arrow-right', 'url' => ['/user/admin/index'],],
                        ],
                        'visible' => Yii::$app->user->can('adminUsuarios')
                    ],       
                    
                ],
            ]
        ) ?>

    </section>

</aside>
