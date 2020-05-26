<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Locate;

/**
 * LocateSearch represents the model behind the search form of `app\models\Locate`.
 */
class LocateSearch extends Locate
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['province', 'district', 'subdistrict', 'village'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
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
        $query = Locate::find();

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
        $query->andFilterWhere(['like', 'province', $this->province])
            ->andFilterWhere(['like', 'district', $this->district])
            ->andFilterWhere(['like', 'subdistrict', $this->subdistrict])
            ->andFilterWhere(['like', 'village', $this->village]);

        return $dataProvider;
    }
}
