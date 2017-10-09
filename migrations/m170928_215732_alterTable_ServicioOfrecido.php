<?php

use yii\db\Migration;

class m170928_215732_alterTable_ServicioOfrecido extends Migration
{
    public function safeUp()
    {
        $this->dropForeignKey('fk_servicioAlumno_servio', 'servicio_alumno');
        $this->addForeignKey('fk_servicioAlumno_servio', 'servicio_alumno', 'id_servicio', 'servicio_establecimiento', 'id');  

    }

    public function safeDown()
    {
        echo "m170928_215732_alterTable_ServicioOfrecido cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170928_215732_alterTable_ServicioOfrecido cannot be reverted.\n";

        return false;
    }
    */
}
