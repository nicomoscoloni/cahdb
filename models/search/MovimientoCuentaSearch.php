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
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'id_cuenta', 'id_tipopago', 'id_hijo'], 'integer'],
            [['tipo_movimiento', 'detalle_movimiento', 'fecha_realizacion', 'comentario'], 'safe'],
            [['importe'], 'number'],
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
}
