<?php

namespace frontend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\PuiLab;

/**
 * PuiLabSearch represents the model behind the search form of `app\models\PuiLab`.
 */
class PuiLabSearch extends PuiLab
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['pui_code', 'referal_no', 'sample_place', 'sample_type', 'pcr_send_date', 'pcr_result', 'pcr_date', 'pcr_time', 'note'], 'safe'],
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
        $query = PuiLab::find();

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
            'pcr_send_date' => $this->pcr_send_date,
            'pcr_date' => $this->pcr_date,
            'pcr_time' => $this->pcr_time,
        ]);

        $query->andFilterWhere(['like', 'pui_code', $this->pui_code])
            ->andFilterWhere(['like', 'referal_no', $this->referal_no])
            ->andFilterWhere(['like', 'sample_place', $this->sample_place])
            ->andFilterWhere(['like', 'sample_type', $this->sample_type])
            ->andFilterWhere(['like', 'pcr_result', $this->pcr_result])
            ->andFilterWhere(['like', 'pcr_send_date', $this->pcr_send_date])
            ->andFilterWhere(['like', 'note', $this->note])
            ->orderBy(['pcr_send_date' => SORT_ASC,]);

        return $dataProvider;
    }
}
