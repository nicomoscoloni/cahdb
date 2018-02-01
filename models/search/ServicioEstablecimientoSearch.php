<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\ServicioEstablecimiento;

/**
 * ServicioEstablecimientoSearch represents the model behind the search form about `app\models\ServicioEstablecimiento`.
 */
class ServicioEstablecimientoSearch extends ServicioEstablecimiento
{
    public $establecimiento;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'id_servicio', 'id_divisionescolar'], 'integer'],
            [['establecimiento'], 'safe'],
            
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
        $query = ServicioEstablecimiento::find();
        $query->joinWith(['miDivisionescolar d']);

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
            'id_servicio' => $this->id_servicio,
            'id_divisionescolar' => $this->id_divisionescolar,
            'd.id_establecimiento' => $this->establecimiento,
        ]);

        return $dataProvider;
    }
}
