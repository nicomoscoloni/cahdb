<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Tiket;

/**
 * TiketSearch represents the model behind the search form about `app\models\Tiket`.
 */
class TiketSearch extends Tiket
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'id_formapago', 'id_cliente'], 'integer'],
            [['fecha_tiket', 'detalles'], 'safe'],
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
        $query = Tiket::find();

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
            'fecha_tiket' => $this->fecha_tiket,
            'id_formapago' => $this->id_formapago,
            'importe' => $this->importe,
            'id_cliente' => $this->id_cliente,
        ]);

        $query->andFilterWhere(['like', 'detalles', $this->detalles]);

        return $dataProvider;
    }
}
