<?php
// This class was automatically generated by a giiant build task
// You should not change it manually as it will be overwritten on next build

namespace app\models\base;

use Yii;

/**
 * This is the base-model class for table "categoria_servicio_ofrecido".
 *
 * @property integer $id
 * @property string $descripcion
 *
 * @property \app\models\ServicioOfrecido[] $servicioOfrecidos
 * @property string $aliasModel
 */
abstract class CategoriaServicioOfrecido extends \yii\db\ActiveRecord
{



    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'categoria_servicio_ofrecido';
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['descripcion'], 'required'],
            [['descripcion'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'descripcion' => 'Descripcion',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getServicioOfrecidos()
    {
        return $this->hasMany(\app\models\ServicioOfrecido::className(), ['id_tiposervicio' => 'id']);
    }




}
