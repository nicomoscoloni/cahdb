<?php

namespace app\models;

use Yii;
use \app\models\base\Alumno as BaseAlumno;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "alumno".
 */
class Alumno extends BaseAlumno
{
    public $establecimiento;

    public function behaviors()
    {
        return ArrayHelper::merge(
            parent::behaviors(),
            [
                'bedezign\yii2\audit\AuditTrailBehavior'
            ]
        );
    }

    public function rules()
    {
        return ArrayHelper::merge(
             parent::rules(),
             [
                ['establecimiento','safe'],
                ['hijo_profesor','required'],
                ['fecha_ingreso', 'date', 'format' => 'php:Y-m-d'],
                ['xfecha_ingreso', 'date', 'format' => 'php:d-m-Y', 'message'=>'Ingrese una fecha valida'],
                ['fecha_ingreso', 'rulesIngresoValido'],                
             ]
        );
    }
    
    public function attributeLabels()
    {
        return ArrayHelper::merge(
             parent::attributeLabels(),
             [
                'id_divisionescolar' => 'Division Escolar',
                'xfecha_ingreso' => 'Fec. Ingreso',
                 'nro_legajo' => 'Legajo',
             ]
        );
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMiGrupofamiliar()
    {
        return $this->hasOne(\app\models\GrupoFamiliar::className(), ['id' => 'id_grupofamiliar']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMiPersona()
    {
        return $this->hasOne(\app\models\Persona::className(), ['id' => 'id_persona']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMiDivisionescolar()
    {
        return $this->hasOne(\app\models\DivisionEscolar::className(), ['id' => 'id_divisionescolar']);
    }
    
    public function getSoyActivo(){
        if($this->activo=='0')
            return "NO";
        else
            return "SI";
    }   
    
    static public function getDatosUnAlumno($idAlumno){
        $alumno = Alumno::findOne($idAlumno);
        if(!empty($alumno)){
            return $alumno->miPersona->apellido . " ". $alumno->miPersona->nombre;
        }else
            return "";
    
    }


    /***********************************************************/
    /***********************************************************/
    public function getXfecha_ingreso()
    {
        if (!empty($this->fecha_ingreso) && $valor = Fecha::convertirFecha($this->fecha_ingreso,"Y-m-d","d-m-Y"))
        {
            
            return $valor;
        } else
        {
            return $this->fecha_ingreso;
        }
    }

    public function setXfecha_ingreso($value)
    {
        if (!empty($value) && $valor = Fecha::convertirFecha($value,"d-m-Y","Y-m-d"))
        {
            
            $this->fecha_ingreso = $valor;
        } else
        {
            $this->fecha_ingreso = $value;
        }
    }
    
    public function rulesIngresoValido() {
        if(!empty($this->fecha_ingreso)){
            if(Fecha::esFechaMayor(date('Y-m-d'), $this->fecha_ingreso)){
                $this->addError('xfecha_ingreso', 'La fecha de Nacimiento debe ser menor a la fecha Actual.');
            }    
        }
    }    
    
}