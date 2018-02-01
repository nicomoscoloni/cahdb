<?php

namespace app\models;

use Yii;
use \app\models\base\GrupoFamiliar as BaseGrupoFamiliar;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "grupo_familiar".
 */
class GrupoFamiliar extends BaseGrupoFamiliar
{
    public $responsable;

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
               ['folio','integer'],
               ['folio','unique','message'=>'El Nº de Folio esta asignada a otra familia'],                
               [['id_pago_asociado'] , 'rulesControlTipoPago'], 
               ['responsable','safe']
            ]
        );
    }
    
    public function attributeLabels()
    {
        return ArrayHelper::merge(
             parent::attributeLabels(),
             [
                'id_pago_asociado' => 'Pago Asociado',
                'nro_tarjetacredito' => 'Nº Tarjeta Credito',
             ]
        );
    }  
    public function fields()
    {
        $fields = parent::fields();
        
        $fields["responsbalePrincipal"] = function($model){
            return $model->miResponsableCabecera;
        };

        return $fields;
        
    }
    
    /*
    static public function getDatosUnaFamilia($idFamilia){
        $familia = GrupoFamiliar::findOne($idFamilia);
        if(!empty($familia)){
            return $familia->apellidos;
        }else
            return "";
    
    }*/
    
    /************************************************************************/
    /************************************************************************/    
    public function rulesControlTipoPago($attribute, $params) {        
        if (($this->id_pago_asociado == '4') || ($this->id_pago_asociado == 4)) {
            $patron = "/^[[:digit:]]{22}$/";
            if (!preg_match($patron, $this->cbu_cuenta)) {                
                $this->addError('cbu_cuenta', 'INGRESE UN CBU VALIDO - CONFORMADO POR 22 DIGITOS!!!');
            }
        }

        if (($this->id_pago_asociado == '5') || ($this->id_pago_asociado == 5)) {
            $patron = "/^[[:digit:]]{16}$/";
            if (!preg_match($patron, $this->nro_tarjetacredito)) {                
                $this->addError('nro_tarjetacredito', 'Nro de Tarjeta INVALIDO!!!');
            }
        }
    }
    
    /************************************************************************/
    /************************************************************************/
    public function getMiResponsableCabecera(){
        if(!empty($this->id)){
            $query = Responsable::find();
            $query->joinWith(['idPersona p']);
        
            $query->andFilterWhere([            
                'id_grupofamiliar' => $this->id,            
            ]);
            $query->andFilterWhere(['like', 'p.apellido', $this->responsable]);
            $query->andFilterWhere(['like', 'p.nombre', $this->responsable]);        
            $query->andFilterWhere(['like', 'p.nro_documento', $this->responsable]);

            $responsable = $query->one();
            
            if($responsable !== null)
                return $responsable->idPersona->apellido . " " . $responsable->idPersona->nombre;
               
            else
                return "";
        }else
            return "";        
    }
    
    /*
    public function getResponsableD(){
        if(!empty($this->id)){
            $query = Responsable::find();
            $query->joinWith(['idPersona p']);
        
            $query->andFilterWhere([            
                'id_grupofamiliar' => $this->id,            
            ]);
            $query->andFilterWhere(['like', 'p.apellido', $this->responsable]);
            $query->andFilterWhere(['like', 'p.nombre', $this->responsable]);        
            $query->andFilterWhere(['like', 'p.nro_documento', $this->responsable]);

            $responsable = $query->one();

           
            return $responsable;
        }else
            return "";
        
    }*/
    
    
    public function getCantidadHijos(){
        return count(Alumno::find()->where('activo=1 and id_grupofamiliar='.$this->id)->all());
    }

    public function getDatosMisHijos(){
        $detalle='';
        $misHijos = $this->alumnosActivos;
        
        if(!empty($misHijos)){
            $i=0;
            foreach($misHijos as $hijo){
                $i+=1;
                $detalle.= " - $i: ".$hijo->miPersona->apellido .";".$hijo->miPersona->nombre;
            }
                
        }
        return $detalle;
    }
    
    public function getDetalleNombreMisHijos(){
        $detalle='';
        $misHijos = $this->alumnosActivos;
        
        if(!empty($misHijos)){
            $i=0;
            foreach($misHijos as $hijo){
                $i+=1;
                $detalle.= "$i: ". $hijo->miPersona->nombre."; \n";
            }
                
        }
        return $detalle;
    }
}