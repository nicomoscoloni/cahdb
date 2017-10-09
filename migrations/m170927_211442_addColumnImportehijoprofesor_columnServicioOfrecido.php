<?php

use yii\db\Migration;

class m170927_211442_addColumnImportehijoprofesor_columnServicioOfrecido extends Migration
{
    public function safeUp()
    {
        echo "Migracion Tablas Servicios, Servicios de Establecimiento.\n";
        
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {            
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        
        $this->addColumn('servicio_ofrecido', 'importe_hijoprofesor', $this->decimal(10,2)->notNull());

    }

    public function safeDown()
    {
        echo "m170927_211442_addColumnImportehijoprofesor_columnServicioOfrecido cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170927_211442_addColumnImportehijoprofesor_columnServicioOfrecido cannot be reverted.\n";

        return false;
    }
    */
}
