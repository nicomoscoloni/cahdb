<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\FondoFijo;

/**
 * FondoFijoSearch represents the model behind the search form of `app\models\FondoFijo`.
 */
class FondoFijoSearch extends FondoFijo
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'id_establecimiento'], 'integer'],
            [['monto_actual', 'alerta_tope_maximo', 'tope_compra'], 'number'],
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
        $query = FondoFijo::find();

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
            'id_establecimiento' => $this->id_establecimiento,
            'monto_actual' => $this->monto_actual,
            'alerta_tope_maximo' => $this->alerta_tope_maximo,
            'tope_compra' => $this->tope_compra,
        ]);

        return $dataProvider;
    }
}
