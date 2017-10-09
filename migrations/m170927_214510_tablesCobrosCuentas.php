<?php

use yii\db\Migration;

class m170927_214510_tablesCobrosCuentas extends Migration
{
    public function safeUp()
    {
        echo "Tabla Cuentas Pagadoras.\n";

        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {            
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        
        //create table Establecimiento
        $this->createTable('{{%cuentas}}', [
            'id' => $this->primaryKey(),
            'nombre' => $this->string()->notNull(),
            'fecha_apertura'=>$this->date()->notNull(),
            'saldo_inicial'=>$this->decimal(10,2)->notNull(),
            'saldo_actual'=>$this->decimal(10,2)->notNull(),           
        ], $tableOptions);
        
        //create table Establecimiento
        $this->createTable('{{%movimiento_cuenta}}', [
            'id' => $this->primaryKey(),
            'id_cuenta' => $this->integer()->notNull(),
            'tipo_movimiento'=>$this->string()->notNull(),
            'detalle_movimiento'=>$this->string()->notNull(),
            'importe'=>$this->decimal(10,2)->notNull(),
            'fecha_realizacion'=>$this->date()->notNull(),
            'comentario'=> $this->string(),
            'id_tipopago'=> $this->integer(), 
            'id_hijo'=> $this->integer()
        ], $tableOptions);
        
        //ForeignKey Tabla Personas        
        $this->addForeignKey('fk_movimientosCuentas_cuenta', 'movimiento_cuenta', 'id_cuenta', 'cuentas', 'id');
        $this->addForeignKey('fk_movimientosCuentas_formaPago', 'movimiento_cuenta', 'id_tipopago', 'forma_pago', 'id'); 
        
        
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
        echo "m170927_214510_tablesCobrosCuentas cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170927_214510_tablesCobrosCuentas cannot be reverted.\n";

        return false;
    }
    */
}
