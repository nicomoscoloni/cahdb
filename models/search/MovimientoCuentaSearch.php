<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\MovimientoCuenta;

/**
 * MovimientoCuentaSearch represents the model behind the search form about `app\models\MovimientoCuenta`.
 */
class MovimientoCuentaSearch extends MovimientoCuenta
{
    public $fecha_desde, $fecha_hasta;
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'id_cuenta', 'id_tipopago', 'id_hijo'], 'integer'],
            [['tipo_movimiento', 'detalle_movimiento', 'fecha_realizacion', 'comentario'], 'safe'],
            [['importe'], 'number'],
            [['fecha_desde','fecha_hasta'],'safe'],
            [['fecha_desde','fecha_hasta'], 'date','format'=>'php:d-m-Y','message'=>'Ingrese una Fecha Valida'],
            [['fecha_desde'], 'rulesRangoValido']  
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = MovimientoCuenta::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'id_cuenta' => $this->id_cuenta,
            'importe' => $this->importe,
            'fecha_realizacion' => $this->fecha_realizacion,
            'id_tipopago' => $this->id_tipopago,
            'id_hijo' => $this->id_hijo,
        ]);

        $query->andFilterWhere(['like', 'tipo_movimiento', $this->tipo_movimiento])
            ->andFilterWhere(['like', 'detalle_movimiento', $this->detalle_movimiento])
            ->andFilterWhere(['like', 'comentario', $this->comentario]);

        return $dataProvider;
    }
    
    public function searchResumenCuenta($params)
    {
        $session = Yii::$app->session;
        $session->remove('resumencuentas');
        
        $query = MovimientoCuenta::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $dataProviderSession = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false
        ]);
        $this->load($params);
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'id_cuenta' => $this->id_cuenta,
            'importe' => $this->importe,
            'fecha_realizacion' => $this->fecha_realizacion,
        ]);
        if ( (!empty($this->fecha_desde)) && (!empty($this->fecha_hasta))){
        $query->andFilterWhere(['>=', 'fecha_realizacion', \app\models\Fecha::convertirFecha($this->fecha_desde,'d-m-Y','Y-m-d')])
              ->andFilterWhere(['<=', 'fecha_realizacion',  \app\models\Fecha::convertirFecha($this->fecha_hasta,'d-m-Y','Y-m-d')]);
                
        }
        if(!empty($this->tipo_movimiento)){
            $query->andFilterWhere(['like', 'tipo_movimiento', $this->tipo_movimiento]);
        }
        
           
        $session->set('resumencuentas', $dataProviderSession->getModels());    
           
        
        return $dataProvider;
    }
    
    
    public function rulesRangoValido(){
       if(\app\models\Fecha::esFechaMayor(\app\models\Fecha::convertirFecha($this->fecha_hasta,'d-m-Y','Y-m-d'), \app\models\Fecha::convertirFecha($this->fecha_desde,'d-m-Y','Y-m-d'))){
        $this->addError('fecha_desde', 'Compruebe que el Rango sea Valido.');
       }
       
    }
}
