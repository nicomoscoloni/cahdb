<?php
// This class was automatically generated by a giiant build task
// You should not change it manually as it will be overwritten on next build

namespace app\models\base;

use Yii;

/**
 * This is the base-model class for table "servicio_debito_automatico".
 *
 * @property integer $id
 * @property integer $id_debitoautomatico
 * @property integer $id_servicio
 * @property string $tiposervicio
 * @property string $resultado_procesamiento
 * @property string $linea
 *
 * @property \app\models\DebitoAutomatico $idDebitoautomatico
 * @property \app\models\ServicioAlumno $idServicio
 * @property string $aliasModel
 */
abstract class ServicioDebitoAutomatico extends \yii\db\ActiveRecord
{



    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'servicio_debito_automatico';
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_debitoautomatico', 'id_servicio'], 'required'],
            [['id_debitoautomatico', 'id_servicio'], 'integer'],
            [['tiposervicio', 'resultado_procesamiento', 'linea'], 'string', 'max' => 255],
            [['id_debitoautomatico'], 'exist', 'skipOnError' => true, 'targetClass' => \app\models\DebitoAutomatico::className(), 'targetAttribute' => ['id_debitoautomatico' => 'id']],
          
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_debitoautomatico' => 'Id Debitoautomatico',
            'id_servicio' => 'Id Servicio',
            'tiposervicio' => 'Tiposervicio',
            'resultado_procesamiento' => 'Resultado Procesamiento',
            'linea' => 'Linea',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdDebitoautomatico()
    {
        return $this->hasOne(\app\models\DebitoAutomatico::className(), ['id' => 'id_debitoautomatico']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdServicio()
    {
        return $this->hasOne(\app\models\ServicioAlumno::className(), ['id' => 'id_servicio']);
    }




}