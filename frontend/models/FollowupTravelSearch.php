<?php

namespace frontend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\FollowupTravel;

/**
 * FollowupSearch represents the model behind the search form of `app\models\Followup`.
 */
class FollowupTravelSearch extends FollowupTravel
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['cid', 'report_date', 'q_fever', 'q_sick_sign', 'note', 'reporter_name', 'reporter_phone', 'last_update'], 'safe'],
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
        $query = FollowupTravel::find();

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
            'report_date' => $this->report_date,
            'last_update' => $this->last_update,
        ]);

        $query->andFilterWhere(['like', 'cid', $this->cid])
            ->andFilterWhere(['like', 'q_fever', $this->q_fever])
            ->andFilterWhere(['like', 'q_sick_sign', $this->q_sick_sign])
            ->andFilterWhere(['like', 'note', $this->note])
            ->andFilterWhere(['like', 'reporter_name', $this->reporter_name])
            ->andFilterWhere(['like', 'reporter_phone', $this->reporter_phone]);

        return $dataProvider;
    }
}
