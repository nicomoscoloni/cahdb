<aside class="main-sidebar">

    <section class="sidebar">
       
        <?= dmstr\widgets\Menu::widget(
            [
                'options' => ['class' => 'sidebar-menu'],
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
                    [
                        'label' => 'Caja',
                        'icon' => 'share',
                        'url' => '#',
                        'items' => [
                            ['label' => 'Cobrar Servicios', 'icon' => 'arrow-right', 'url' => ['caja/cobrar','oper'=>'1'],],
                            ['label' => 'Cobrar Ingreso', 'icon' => 'arrow-right', 'url' => ['caja/cobrar','oper'=>'2'],],
                            //['label' => 'Pago Servicios', 'icon' => 'arrow-right', 'url' => ['servicio-pagado/admin'],],
                            
                        ],
                       // 'visible' => Yii::$app->user->can('cobrarServicios')
                    ],
                    [
                        'label' => 'Reportes',
                        'icon' => 'fa fa-share',
                        'url' => '#',
                        'items' => [
                            ['label' => 'Servicios Brindados', 'icon' => 'fa fa-arrow-right', 'url' => ['servicio-alumno/reporte'],],                            
                        ],
                    ],
                   [   'label' => 'Convenio Pago', 
                        'icon' => 'fa fa-handshake-o', 
                        'url' => ['convenio-pago/administrar'],
                        //'visible' => Yii::$app->user->can('gestionarConveniosPagos')
                    ],
                    [
                        'label' => 'Configuraciones',
                        'icon' => 'cogs',
                        'url' => '#',
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
                    [
                        'label' => 'Debito Auotmatico',
                        'icon' => 'fa fa-handshake-o',
                        'url' => ['debito-automatico/administrar'], 
                        'visible' => Yii::$app->user->can('gestionDebitosAutomaticos')
                    ],
                    ['label' => 'Servicios', 'icon' => 'briefcase', 'url' => ['servicio-ofrecido/admin']],
 ['label' => 'Administrar',
                     'url' => ['/user/admin/index'], 
                   //  'visible'=>(Yii::$app->user->can('administrador')),     
                    ],
                ],
            ]
        ) ?>

    </section>

</aside>
