<?php

namespace frontend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Countryrisk;

/**
 * CountryriskSearch represents the model behind the search form of `app\models\Countryrisk`.
 */
class CountryriskSearch extends Countryrisk
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['countryid'], 'integer'],
            [['countryname', 'riskgroup', 'epidemicgroup'], 'safe'],
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
        $query = Countryrisk::find();

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
            'countryid' => $this->countryid,
        ]);

        $query->andFilterWhere(['like', 'countryname', $this->countryname])
            ->andFilterWhere(['like', 'riskgroup', $this->riskgroup])
            ->andFilterWhere(['like', 'epidemicgroup', $this->epidemicgroup]);

        return $dataProvider;
    }
}
