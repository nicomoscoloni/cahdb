<?php

use yii\db\Migration;

/**
 * Class m180222_145042_addDivisionEgreso
 */
class m180222_145042_addDivisionEgreso extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {            
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        
        $this->addColumn('division_escolar', 'id_divisionegreso', $this->integer());
        $this->addForeignKey('fk_division_divisionegreso', 'division_escolar', 'id_divisionegreso', 'division_escolar', 'id');
        

    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        echo "m180222_145042_addDivisionEgreso cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180222_145042_addDivisionEgreso cannot be reverted.\n";

        return false;
    }
    */
}
