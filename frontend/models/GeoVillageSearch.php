<?php

namespace frontend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\GeoVillage;

/**
 * GeoVillageSearch represents the model behind the search form of `frontend\models\GeoVillage`.
 */
class GeoVillageSearch extends GeoVillage
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['villagecodefull', 'villagename', 'tambonname', 'tamboncode', 'ampurcode', 'name', 'description', 'subdistrict', 'district', 'province', 'display', 'visibility', 'changwatcode', 'coordinates'], 'safe'],
            [['id'], 'integer'],
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
        $query = GeoVillage::find();

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
        ]);

        $query->andFilterWhere(['like', 'villagecodefull', $this->villagecodefull])
            ->andFilterWhere(['like', 'villagename', $this->villagename])
            ->andFilterWhere(['like', 'tambonname', $this->tambonname])
            ->andFilterWhere(['like', 'tamboncode', $this->tamboncode])
            ->andFilterWhere(['like', 'ampurcode', $this->ampurcode])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'subdistrict', $this->subdistrict])
            ->andFilterWhere(['like', 'district', $this->district])
            ->andFilterWhere(['like', 'province', $this->province])
            ->andFilterWhere(['like', 'display', $this->display])
            ->andFilterWhere(['like', 'visibility', $this->visibility])
            ->andFilterWhere(['like', 'changwatcode', $this->changwatcode])
            ->andFilterWhere(['like', 'coordinates', $this->coordinates]);

        return $dataProvider;
    }
}
