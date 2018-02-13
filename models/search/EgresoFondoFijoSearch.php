<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\EgresoFondoFijo;

/**
 * EgresoFondoFijoSearch represents the model behind the search form of `app\models\EgresoFondoFijo`.
 */
class EgresoFondoFijoSearch extends EgresoFondoFijo
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'id_fondofijo', 'id_clasificacionegreso'], 'integer'],
            [['fecha_compra', 'proovedor', 'descripcion'], 'safe'],
            [['importe', 'nro_factura', 'nro_rendicion'], 'number'],
            [['bien_uso', 'rendido'], 'boolean'],
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
        $query = EgresoFondoFijo::find();

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
            'id_fondofijo' => $this->id_fondofijo,
            'id_clasificacionegreso' => $this->id_clasificacionegreso,
            'fecha_compra' => $this->fecha_compra,
            'importe' => $this->importe,
            'nro_factura' => $this->nro_factura,
            'nro_rendicion' => $this->nro_rendicion,
            'bien_uso' => $this->bien_uso,
            'rendido' => $this->rendido,
        ]);

        $query->andFilterWhere(['like', 'proovedor', $this->proovedor])
            ->andFilterWhere(['like', 'descripcion', $this->descripcion]);

        return $dataProvider;
    }
}
