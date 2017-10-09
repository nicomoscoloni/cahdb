<?php
// This class was automatically generated by a giiant build task
// You should not change it manually as it will be overwritten on next build

namespace app\models\base;

use Yii;

/**
 * This is the base-model class for table "responsable".
 *
 * @property integer $id
 * @property integer $id_grupofamiliar
 * @property integer $id_persona
 * @property integer $tipo_responsable
 *
 * @property \app\models\GrupoFamiliar $idGrupofamiliar
 * @property \app\models\Persona $idPersona
 * @property \app\models\TipoResponsable $tipoResponsable
 * @property string $aliasModel
 */
abstract class Responsable extends \yii\db\ActiveRecord
{



    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'responsable';
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_grupofamiliar', 'id_persona', 'tipo_responsable'], 'required'],
            [['id_grupofamiliar', 'id_persona', 'tipo_responsable'], 'integer'],
            [['id_grupofamiliar'], 'exist', 'skipOnError' => true, 'targetClass' => \app\models\GrupoFamiliar::className(), 'targetAttribute' => ['id_grupofamiliar' => 'id']],
            [['id_persona'], 'exist', 'skipOnError' => true, 'targetClass' => \app\models\Persona::className(), 'targetAttribute' => ['id_persona' => 'id']],
            [['tipo_responsable'], 'exist', 'skipOnError' => true, 'targetClass' => \app\models\TipoResponsable::className(), 'targetAttribute' => ['tipo_responsable' => 'id']]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_grupofamiliar' => 'Id Grupofamiliar',
            'id_persona' => 'Id Persona',
            'tipo_responsable' => 'Tipo Responsable',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdGrupofamiliar()
    {
        return $this->hasOne(\app\models\GrupoFamiliar::className(), ['id' => 'id_grupofamiliar']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdPersona()
    {
        return $this->hasOne(\app\models\Persona::className(), ['id' => 'id_persona']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTipoResponsable()
    {
        return $this->hasOne(\app\models\TipoResponsable::className(), ['id' => 'tipo_responsable']);
    }




}
