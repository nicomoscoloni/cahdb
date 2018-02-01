<?php
// This class was automatically generated by a giiant build task
// You should not change it manually as it will be overwritten on next build

namespace app\models\base;

use Yii;

/**
 * This is the base-model class for table "servicio_tiket".
 *
 * @property integer $id
 * @property integer $id_tiket
 * @property integer $id_servicio
 * @property string $tipo_servicio
 * @property string $importe
 *
 * @property \app\models\Tiket $tiket
 * @property string $aliasModel
 */
abstract class ServicioTiket extends \yii\db\ActiveRecord
{



    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'servicio_tiket';
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_tiket', 'id_servicio', 'tipo_servicio', 'importe'], 'required'],
            [['id_tiket', 'id_servicio'], 'integer'],
            [['importe'], 'number'],
            [['tipo_servicio'], 'string', 'max' => 255],
            [['id_tiket'], 'exist', 'skipOnError' => true, 'targetClass' => \app\models\Tiket::className(), 'targetAttribute' => ['id_tiket' => 'id']]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_tiket' => 'Id Tiket',
            'id_servicio' => 'Id Servicio',
            'tipo_servicio' => 'Tipo Servicio',
            'importe' => 'Importe',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTiket()
    {
        return $this->hasOne(\app\models\Tiket::className(), ['id' => 'id_tiket']);
    }




}
