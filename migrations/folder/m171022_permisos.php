<?php

use yii\db\Migration;

class m171022_permisos extends Migration
{
    public function safeUp()
    {
        $auth = Yii::$app->authManager;
        
        /*******************************************************/
        //gestiones de combos
        $permisoTDocumentos = $auth->createPermission('gestionarDocumentos');
        $permisoTSexos = $auth->createPermission('gestionarSexos');
        $permisoFormaPago = $auth->createPermission('gestionarFormaPago');
        $permisoTResponable = $auth->createPermission('gestionarTipoResponsable');
        $permisoTCategoriaServicio = $auth->createPermission('gestionarCategoriaServicios');
        $permisoClasificacionEgresos = $auth->createPermission('gestionarClasificacionEgresosFondoFijo');   
        $permisoTCategoriaBonificacion = $auth->createPermission('gestionarCategoriaDescuentos');
        
        $auth->add($permisoTDocumentos);
        $auth->add($permisoTSexos);
        $auth->add($permisoFormaPago);
        $auth->add($permisoTResponable);
        $auth->add($permisoTCategoriaServicio);
        $auth->add($permisoTCategoriaBonificacion);
        $auth->add($permisoClasificacionEgresos);
        
        
        /*******************************************************/
        //servicio ofrecido
        $permisoAbmlSO = $auth->createPermission('abmlServicioOfrecido');
        $permisoDevengamientoSO = $auth->createPermission('devengarServicioOfrecido');
        $permisoGestionarServicioOfrecido = $auth->createPermission('gestionarServicios');        
        $auth->add($permisoAbmlSO);
        $auth->add($permisoDevengamientoSO);
        $auth->add($permisoGestionarServicioOfrecido);
        $auth->addChild($permisoGestionarServicioOfrecido, $permisoDevengamientoSO);
        $auth->addChild($permisoGestionarServicioOfrecido, $permisoAbmlSO);
        
        $permisoRemoverServicio = $auth->createPermission('removerServicioAlumno');        
        $auth->add($permisoRemoverServicio);
        
        
         /************************************************************/
        //familias
        $perListarFamilia = $auth->createPermission('listarFamilias');
        $perVisualizarFamilia = $auth->createPermission('visualizarFamilia');
        $perCargarFamilia = $auth->createPermission('cargarFamilia');
        $perEliminarFamilia = $auth->createPermission('eliminarFamilia');
        $perExportarFamilia = $auth->createPermission('exportarFamilia');
        $perGestionResponsable = $auth->createPermission('gestionarResponable');
        
        $auth->add($perListarFamilia);
        $auth->add($perVisualizarFamilia);
        $auth->add($perCargarFamilia);
        $auth->add($perEliminarFamilia);
        $auth->add($perExportarFamilia);
        $auth->add($perGestionResponsable);
        
        /*********************************************************/
        //alumnos
        $perListarAlumno = $auth->createPermission('listarAlumnos');
        $perEliminarAlumno = $auth->createPermission('eliminarAlumno');
        $perCargarAlumno = $auth->createPermission('cargarAlumno');
        $perVisualizarAlumno = $auth->createPermission('visualizarAlumno');
        $perActivarAlumno = $auth->createPermission('activarAlumno');
        $perInactivarAlumno = $auth->createPermission('inactivarAlumno');
        $perExportarAlumno = $auth->createPermission('exportarAlumno');
        $perGestionarBonificacion = $auth->createPermission('gestionarBonificacionAlumno');
        
        $auth->add($perListarAlumno);
        $auth->add($perEliminarAlumno);
        $auth->add($perCargarAlumno);
        $auth->add($perVisualizarAlumno);
        $auth->add($perActivarAlumno);
        $auth->add($perInactivarAlumno);
        $auth->add($perExportarAlumno);
        $auth->add($perGestionarBonificacion);
        
        /************************************************************/
        //establecimiento
        
        $perListarEstablecimientos = $auth->createPermission('listarEstablecimientos');        
        $perCargarEstablecimiento = $auth->createPermission('cargarEstablecimiento');
        $perEliminarEstablecimmiento = $auth->createPermission('eliminarEstablecimiento');        
        $perVisualizarEstablecimiento = $auth->createPermission('visualizarEstablecimiento');
        $perGestionServiciosEstablecimiento = $auth->createPermission('gestionarServiciosEstablecimiento');
        $perGestionarDivisionesEscolares = $auth->createPermission('gestionarDivisionesEscolares');
        
        $auth->add($perListarEstablecimientos);
        $auth->add($perCargarEstablecimiento);
        $auth->add($perEliminarEstablecimmiento);        
        $auth->add($perVisualizarEstablecimiento);        
        $auth->add($perGestionServiciosEstablecimiento);        
        $auth->add($perGestionarDivisionesEscolares);
        
        
        
        
        
        /*******************************************************/
        //Convenio Pago
        $permisoGestionCP = $auth->createPermission('gestionarConvenioPago');
        $auth->add($permisoGestionCP);
        
        //Convenio Pago
        $permisoGestionDA = $auth->createPermission('gestionarDebitoAutomatico');
        $auth->add($permisoGestionDA);
        
        
        
       
        
        
        
        
        /***********************/
        $perDebitosAutomaticos = $auth->createPermission('gestionarDebitoAutomatico');
        $auth->add($perDebitosAutomaticos);
                
    }

    public function safeDown()
    {
        echo "m171009_135048_convenioPagos cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m171009_135048_convenioPagos cannot be reverted.\n";

        return false;
    }
    */
}
