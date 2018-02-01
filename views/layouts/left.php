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
                            ['label' => 'Listado', 'icon' => 'arrow-right', 'url' => ['alumno/listado']],
                            ['label' => 'Carga Alumno', 'icon' => 'arrow-right', 'url' => ['alumno/empadronamiento']],
                            ['label' => 'Familias', 'icon' => 'arrow-right', 'url' => ['grupo-familiar/admin']],                            
                        ],
                       // 'visible' => (Yii::$app->user->can('gestionarAlumnos') || Yii::$app->user->can('visualizarFamilias')),
                    ],
                    [   'label' => 'Establecimientos', 
                        'icon' => 'university', 
                        'url' => ['establecimiento/index'],
                      //  'visible' => Yii::$app->user->can('gestionarEstablecimientos')
                    ],
                    [   'label' => 'Convenio Pago', 
                        'icon' => 'university', 
                        'url' => ['convenio-pago/administrar'],
                      //  'visible' => Yii::$app->user->can('gestionarEstablecimientos')
                    ],
                    ['label' => 'Debito Automatico', 'icon' => 'briefcase', 'url' => ['debito-automatico/admin']],
                    
                    ['label' => 'Servicios', 'icon' => 'briefcase', 'url' => ['servicio-ofrecido/admin']],
                    [
                        'label' => 'Configuraciones',
                        'icon' => 'cogs',
                        'url' => '',
                        'items' => [
                            ['label' => 'Tipo Documentos', 'icon' => 'arrow-right', 'url' => ['tipo-documento/index']],
                            ['label' => 'Tipo Sexos', 'icon' => 'arrow-right', 'url' => ['tipo-sexo/index']],
                            ['label' => 'Forma Pago', 'icon' => 'arrow-right', 'url' => ['forma-pago/index']],
                            ['label' => 'Tipo Responsables', 'icon' => 'arrow-right', 'url' => ['tipo-responsable/index']],
                            ['label' => 'T.Servicios Pago', 'icon' => 'arrow-right', 'url' => ['categoria-pago-servicio/index'],],
                            ['label' => 'T.Servicios Cobro', 'icon' => 'arrow-right', 'url' => ['categoria-servicio-ofrecido/index'],],
                           
                        ],
                       // 'visible'=>(Yii::$app->user->can('director') || Yii::$app->user->can('administrador') || Yii::$app->user->can('secretario')),                         
                        
                    ],
                    ['label' => 'Menu Yii2', 'options' => ['class' => 'header']],
                    ['label' => 'Gii', 'icon' => 'file-code-o', 'url' => ['/gii']],
                    ['label' => 'Debug', 'icon' => 'dashboard', 'url' => ['/debug']],
                    ['label' => 'Login', 'url' => ['site/login'], 'visible' => Yii::$app->user->isGuest],
                    [
                        'label' => 'Some tools',
                        'icon' => 'share',
                        'url' => '#',
                        'items' => [
                            ['label' => 'Gii', 'icon' => 'file-code-o', 'url' => ['/gii'],],
                            ['label' => 'Debug', 'icon' => 'dashboard', 'url' => ['/debug'],],
                            [
                                'label' => 'Level One',
                                'icon' => 'circle-o',
                                'url' => '#',
                                'items' => [
                                    ['label' => 'Level Two', 'icon' => 'circle-o', 'url' => '#',],
                                    [
                                        'label' => 'Level Two',
                                        'icon' => 'circle-o',
                                        'url' => '#',
                                        'items' => [
                                            ['label' => 'Level Three', 'icon' => 'circle-o', 'url' => '#',],
                                            ['label' => 'Level Three', 'icon' => 'circle-o', 'url' => '#',],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ]
        ) ?>

    </section>

</aside>
