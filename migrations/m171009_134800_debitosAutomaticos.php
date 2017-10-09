<?php

use yii\db\Migration;

class m171009_134800_debitosAutomaticos extends Migration
{
    public function safeUp()
    {
        echo "Aplicando migracion Debitos Automaticos.\n";

        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {            
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        
        //create table Establecimiento
        $this->createTable('{{%debito_automatico}}', [
            'id' => $this->primaryKey(),
            'nombre' => $this->string(100)->notNull(),
            'banco' => $this->string(100)->notNull(),
            'tipo_archivo'=>$this->string(50)->notNull(),            
            'fecha_creacion'=>$this->date()->notNull(),
            'fecha_procesamiento'=>$this->date(),
            'inicio_periodo'=>$this->date()->notNull(),
            'fin_periodo'=>$this->date()->notNull(),
            'fecha_debito'=>$this->date()->notNull(),
            'procesado'=>$this->binary(),
            'registros_enviados'=>$this->integer(),
            'registros_correctos'=>$this->integer(),
            'saldo_enviado' => $this->decimal(10,2)->notNull(),
            'saldo_entrante' => $this->decimal(10,2),
            
        ], $tableOptions);
        
        
        //create table Establecimiento
        $this->createTable('{{%servicio_debito_automatico}}', [
            'id' => $this->primaryKey(),
            'id_debitoautomatico'=>$this->integer()->notNull(),
            'id_servicio'=>$this->integer()->notNull(),
            'tiposervicio'=>$this->string(),
            'resultado_procesamiento'=>$this->string(),
            'linea'=>$this->string(),
        ], $tableOptions);
        
        $this->execute('ALTER TABLE debito_automatico MODIFY procesado bit(1)');
        
        $this->addForeignKey('fk_servicioDA_debitoAutomatico', 'servicio_debito_automatico', 'id_debitoautomatico', 'debito_automatico', 'id');
            

    }

    public function safeDown()
    {
        echo "m171009_134800_debitosAutomaticos cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m171009_134800_debitosAutomaticos cannot be reverted.\n";

        return false;
    }
    */
}
