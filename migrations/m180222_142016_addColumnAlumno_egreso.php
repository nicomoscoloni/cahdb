<?php

use yii\db\Migration;

/**
 * Class m180222_142016_addColumnAlumno_egreso
 */
class m180222_142016_addColumnAlumno_egreso extends Migration
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
        
        $this->addColumn('alumno', 'egresado', $this->binary());
        $this->addColumn('alumno', 'fecha_egreso', $this->date());
        
        $this->execute('ALTER TABLE alumno MODIFY egresado bit(1)');

    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        echo "m180222_142016_addColumnAlumno_egreso cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180222_142016_addColumnAlumno_egreso cannot be reverted.\n";

        return false;
    }
    */
}
