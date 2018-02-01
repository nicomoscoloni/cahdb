<?php

use yii\db\Migration;

class m171009_135050_tiket extends Migration
{
    public function safeUp()
    {
        echo "Aplicando migracion Convenios Pago.\n";

        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {            
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        
        
        //create table Establecimiento
        $this->createTable('{{%tiket}}', [
            'id' => $this->primaryKey(),
            'fecha_tiket' => $this->date(),
            'id_formapago'=>$this->integer()->notNull(),
            'importe'=>$this->decimal(10,2)->notNull(),
            'detalles'=>$this->string(),
            'id_cliente'=>$this->integer()->notNull(),
        ], $tableOptions);
        
          //ForeignKey Tabla Personas        
        $this->addForeignKey('fk_tiket_formapago', 'tiket', 'id_formapago', 'forma_pago', 'id');
        $this->addForeignKey('fk_tiket_cliente', 'tiket', 'id_cliente', 'grupo_familiar', 'id');  
        
        $this->createTable('{{%servicio_tiket}}', [
            'id' => $this->primaryKey(),
            'id_tiket' => $this->integer()->notNull(),
            'id_servicio'=>$this->integer()->notNull(),
            'tipo_servicio'=>$this->string()->notNull(),
            'importe'=>$this->decimal(10,2)->notNull(),
        ], $tableOptions);
        
        //ForeignKey Tabla Personas        
        $this->addForeignKey('fk_servicioTiket_Tiket', 'servicio_tiket', 'id_tiket', 'tiket', 'id');
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
