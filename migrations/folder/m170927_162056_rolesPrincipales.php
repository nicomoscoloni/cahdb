<?php

use yii\db\Migration;

class m170927_162056_rolesPrincipales extends Migration
{
    public function safeUp()
    {
        $auth = Yii::$app->authManager;
        
        $rolDirector = $auth->createRole('director');
        $auth->add($rolDirector);
        
        $rolProfesor = $auth->createRole('profesor');
        $auth->add($rolProfesor);
        
        $rolPreceptor = $auth->createRole('preceptor');
        $auth->add($rolPreceptor);
        $auth->addChild($rolPreceptor, $rolProfesor);
        
        $rolContador = $auth->createRole('contador');
        $auth->add($rolContador);
        
        $rolAdminEst = $auth->createRole('adminEstablecimiento');
        $auth->add($rolAdminEst);
        $auth->addChild($rolAdminEst, $rolDirector);
        $auth->addChild($rolAdminEst, $rolPreceptor);
        $auth->addChild($rolAdminEst, $rolContador);
        
        
        

    }

    public function safeDown()
    {
        echo "m170927_162056_rolesPrincipales cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170927_162056_rolesPrincipales cannot be reverted.\n";

        return false;
    }
    */
}
