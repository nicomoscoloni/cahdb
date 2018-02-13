<?php

use yii\db\Migration;

class m171022_135050_fondoFijos extends Migration
{
    public function safeUp()
    {
        echo "Aplicando migracion Fondo Fijos.\n";

        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {            
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        
        
        //create table Establecimiento
        $this->createTable('{{%fondo_fijo}}', [
            'id' => $this->primaryKey(),
            'id_establecimiento'=>$this->integer()->notNull(),
            'monto_actual'=>$this->decimal(10,2)->notNull(),
            'alerta_tope_maximo'=>$this->decimal(10,2)->notNull(),
            'tope_compra'=>$this->decimal(10,2)->notNull(),
        ], $tableOptions);
        
          //ForeignKey Tabla Personas        
        $this->addForeignKey('fk_fondofijo_establecimiento', 'fondo_fijo', 'id_establecimiento', 'establecimiento', 'id');
        
        $this->createTable('{{%egresos_fondofijo}}', [
            'id' => $this->primaryKey(),
            'id_fondofijo' => $this->integer()->notNull(),
            'id_clasificacionegreso'=>$this->integer()->notNull(),
            'fecha_compra'=>$this->date()->notNull(),
            'proovedor'=>$this->string()->notNull(),
            'descripcion'=>$this->string()->notNull(),
            'importe'=>$this->decimal(10,2)->notNull(),
            'nro_factura'=>$this->decimal(10,2),
            'nro_rendicion'=>$this->decimal(10,2),            
            'bien_uso'=>$this->binary(),
            'rendido'=>$this->binary(),
        ], $tableOptions);
        
        $this->execute('ALTER TABLE egresos_fondofijo MODIFY bien_uso bit(1)');
        $this->execute('ALTER TABLE egresos_fondofijo MODIFY rendido bit(1)');
        //ForeignKey Tabla Personas        
        $this->addForeignKey('fk_egresos_fondofijo_fondofijo', 'egresos_fondofijo', 'id_fondofijo', 'fondo_fijo', 'id');
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
