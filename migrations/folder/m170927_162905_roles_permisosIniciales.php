<?php

use yii\db\Migration;

class m170927_162905_roles_permisosIniciales extends Migration
{
    public function safeUp()
    {
        $auth = Yii::$app->authManager;
        
        $permisionDocumentos = $auth->createPermission('admlDocumentos');
        $auth->add($permisionDocumentos);
        
        $permisionGeneros = $auth->createPermission('admlGeneros');
        $auth->add($permisionGeneros);
        
        $permisionTipoResponsables = $auth->createPermission('admlTipoResponsables');
        $auth->add($permisionTipoResponsables);
        
        $permisionFormaPago = $auth->createPermission('admlFormaPago');
        $auth->add($permisionFormaPago);
        
        $rolConfigGral = $auth->createRol('configGral');
        $auth->add(rolConfigGral);
        
        $auth->addChild($rolConfigGral, $permisionDocumentos);
        $auth->addChild($rolConfigGral, $permisionGeneros);
        $auth->addChild($rolConfigGral, $permisionTipoResponsables);
        $auth->addChild($rolConfigGral, $permisionFormaPago);
        
        /*
        $rolGestionarEstablecimientos = $auth->createRole('gestionarEstablecimientos');
        $auth->addChild($rolGestionarEstablecimientos);
        
        admlDocumentos
        
        $rolGestionarEstablecimientos = $auth->createRole('gestionarEstablecimientos');
        $auth->addChild($rolGestionarEstablecimientos);
        
        $rolGestionarDivisionEscolar = $auth->createRole('gestionarDivisionEscolar');
        $auth->addChild($rolGestionarDivisionEscolar);
        
        $rolFiltrarDivisiones = $auth->createPermission('filtrarDivisionesxEstablecimiento');
        $auth->addChild($rolFiltrarDivisiones);
        
        $rolAbmlAlumnos = $auth->createRole('abmlAlumnos');
        $auth->addChild($rolAbmlAlumnos);
        $rolActivarAlumno = $auth->createPermission('activarAlumno');
        $rolInactivarAlumno = $auth->createPermission('inactivarAlumno');
        $auth->addChild($rolActivarAlumno);
        $auth->addChild($rolInactivarAlumno);
        
        $rolGestionBonificacionAlumno = $auth->createRole('gestionarBonificacionAlumno');
        $auth->addChild($rolGestionBonificacionAlumno);*/

    }

    public function safeDown()
    {
        echo "m170927_162905_roles_permisosIniciales cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170927_162905_roles_permisosIniciales cannot be reverted.\n";

        return false;
    }
    */
}
