<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\ConvenioPago;

/**
 * ConvenioPagoSearch represents the model behind the search form about `app\models\ConvenioPago`.
 */
class ConvenioPagoSearch extends ConvenioPago
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'id_familia'], 'integer'],
            [['nombre', 'fecha_alta', 'deb_automatico', 'descripcion', 'con_servicios'], 'safe'],
            [['saldo_pagar'], 'number'],
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
        $query = ConvenioPago::find();

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
            'fecha_alta' => $this->fecha_alta,
            'id_familia' => $this->id_familia,
            'saldo_pagar' => $this->saldo_pagar,
        ]);

        $query->andFilterWhere(['like', 'nombre', $this->nombre])
            ->andFilterWhere(['like', 'deb_automatico', $this->deb_automatico])
            ->andFilterWhere(['like', 'descripcion', $this->descripcion])
            ->andFilterWhere(['like', 'con_servicios', $this->con_servicios]);

        return $dataProvider;
    }
}
