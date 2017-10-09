<?php

use yii\db\Migration;

class m170927_205121_serviciosOfrecidos extends Migration
{
    public function safeUp()
    {
        echo "Migracion Tablas Servicios, Servicios de Establecimiento.\n";
        
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {            
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
       
        $this->createTable('{{%categoria_servicio_ofrecido}}', [
            'id' =>     $this->primaryKey(),
            'descripcion' => $this->string(50)->notNull()                        
        ], $tableOptions);        
       
        $this->createTable('{{%servicio_ofrecido}}', [
            'id' =>     $this->primaryKey(),
            'id_tiposervicio' => $this->integer()->notNull(),
            'nombre'=>  $this->string(100)->notNull(),
            'descripcion'=>  $this->string(),
            'importe'=>$this->decimal(8,2)->notNull(),
            'fecha_inicio' => $this->date(),
            'fecha_fin' => $this->date(),
            'fecha_vencimiento' => $this->date(),
            'devengamiento_automatico'=>$this->binary(1)->notNull(),             
        ], $tableOptions);
        
        $this->execute('ALTER TABLE servicio_ofrecido MODIFY devengamiento_automatico bit(1)');        
        $this->addForeignKey('fk_serviciofrecido_categoriaservicio', 'servicio_ofrecido', 'id_tiposervicio', 'categoria_servicio_ofrecido', 'id');
               
        $this->createTable('{{%servicio_establecimiento}}', [
            'id' =>         $this->primaryKey(),
            'id_servicio'=> $this->integer()->notNull(),
            'id_divisionescolar' => $this->integer()->notNull(),          
        ], $tableOptions);
        
        $this->addForeignKey('fk_servicioEstablecimiento_servio', 'servicio_establecimiento', 'id_servicio', 'servicio_ofrecido', 'id');
        $this->addForeignKey('fk_servicioEstablecimiento_divisionescolar', 'servicio_establecimiento', 'id_divisionescolar', 'division_escolar', 'id');

    }

    public function safeDown()
    {
        echo "m170927_205121_serviciosOfrecidos cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170927_205121_serviciosOfrecidos cannot be reverted.\n";

        return false;
    }
    */
}
