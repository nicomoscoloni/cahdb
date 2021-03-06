<?php
// This class was automatically generated by a giiant build task
// You should not change it manually as it will be overwritten on next build

namespace app\models\base;

use Yii;

/**
 * This is the base-model class for table "servicio_ofrecido".
 *
 * @property integer $id
 * @property integer $id_tiposervicio
 * @property string $nombre
 * @property string $descripcion
 * @property string $importe
 * @property string $fecha_inicio
 * @property string $fecha_fin
 * @property string $fecha_vencimiento
 * @property boolean $devengamiento_automatico
 * @property string $importe_hijoprofesor
 *
 * @property \app\models\ServicioAlumno[] $servicioAlumnos
 * @property \app\models\ServicioEstablecimiento[] $servicioEstablecimientos
 * @property \app\models\CategoriaServicioOfrecido $tiposervicio
 * @property string $aliasModel
 */
abstract class ServicioOfrecido extends \yii\db\ActiveRecord
{



    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'servicio_ofrecido';
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_tiposervicio', 'nombre', 'importe', 'importe_hijoprofesor'], 'required'],
            [['id_tiposervicio'], 'integer'],
            [['importe', 'importe_hijoprofesor'], 'number'],
            [['fecha_inicio', 'fecha_fin', 'fecha_vencimiento'], 'safe'],
            [['devengamiento_automatico'], 'boolean'],
            [['nombre'], 'string', 'max' => 100],
            [['descripcion'], 'string', 'max' => 255],
            [['id_tiposervicio'], 'exist', 'skipOnError' => true, 'targetClass' => \app\models\CategoriaServicioOfrecido::className(), 'targetAttribute' => ['id_tiposervicio' => 'id']]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_tiposervicio' => 'Id Tiposervicio',
            'nombre' => 'Nombre',
            'descripcion' => 'Descripcion',
            'importe' => 'Importe',
            'fecha_inicio' => 'Fecha Inicio',
            'fecha_fin' => 'Fecha Fin',
            'fecha_vencimiento' => 'Fecha Vencimiento',
            'devengamiento_automatico' => 'Devengamiento Automatico',
            'importe_hijoprofesor' => 'Importe Hijoprofesor',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getServicioAlumnos()
    {
        return $this->hasMany(\app\models\ServicioAlumno::className(), ['id_servicio' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getServicioEstablecimientos()
    {
        return $this->hasMany(\app\models\ServicioEstablecimiento::className(), ['id_servicio' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTiposervicio()
    {
        return $this->hasOne(\app\models\CategoriaServicioOfrecido::className(), ['id' => 'id_tiposervicio']);
    }




}
