<?php

use yii\db\Migration;

class m171020_115050_columnInicialesDivisin extends Migration
{
    public function safeUp()
    {
        echo "Aplicando migracion Fondo Fijos.\n";

        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {            
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        
            $this->addColumn('division_escolar', 'iniciales', $this->string(25));
        
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
