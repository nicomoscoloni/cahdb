<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\GrupoFamiliar;

/**
 * GrupoFamiliarSearch represents the model behind the search form about `app\models\GrupoFamiliar`.
 */
class GrupoFamiliarSearch extends GrupoFamiliar
{
    public $responsable;
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'id_pago_asociado'], 'integer'],
            [['apellidos', 'descripcion', 'folio', 'cbu_cuenta', 'nro_tarjetacredito', 'tarjeta_banco','responsable'], 'safe'],
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
        $session = Yii::$app->session;
        $session->remove('padronfamilias');   
        
        $query = GrupoFamiliar::find()->distinct();
        $query->joinWith(['responsables r','responsables.persona p']);

        $dataProviderSession = new ActiveDataProvider([
            'query' => $query,           
            'pagination' => false
        ]);
        
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
            'id_pago_asociado' => $this->id_pago_asociado,
        ]);

        $query->andFilterWhere(['like', 'apellidos', $this->apellidos])
            ->andFilterWhere(['like', 'descripcion', $this->descripcion])
            ->andFilterWhere(['like', 'folio', $this->folio])
            ->andFilterWhere(['like', 'cbu_cuenta', $this->cbu_cuenta])
            ->andFilterWhere(['like', 'nro_tarjetacredito', $this->nro_tarjetacredito])
            ->andFilterWhere(['like', 'tarjeta_banco', $this->tarjeta_banco]);
        
        
        $session->set('padronfamilias', $query->createCommand()->getRawSql());
        
        return $dataProvider;
    }
    
    public function searchBuscador($params)
    {   
        $query = GrupoFamiliar::find();
        $query->joinWith(['responsables r','responsables.persona p']);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
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
            'id_pago_asociado' => $this->id_pago_asociado,
        ]);

        $query->andFilterWhere(['like', 'apellidos', $this->apellidos])
            ->andFilterWhere(['like', 'descripcion', $this->descripcion])
            ->andFilterWhere(['like', 'folio', $this->folio])
            ->andFilterWhere(['like', 'cbu_cuenta', $this->cbu_cuenta])
            ->andFilterWhere(['like', 'nro_tarjetacredito', $this->nro_tarjetacredito])
            ->andFilterWhere(['like', 'tarjeta_banco', $this->tarjeta_banco]);
        
        $query->andFilterWhere(['like', 'p.apellido', $this->responsable]);

        
        return $dataProvider;
    }
}
