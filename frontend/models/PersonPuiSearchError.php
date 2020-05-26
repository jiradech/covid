<?php

namespace frontend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\PersonPui;

/**
 * PersonpuiSearch represents the model behind the search form of `app\models\PersonPui`.
 */
class PersonpuiSearchError extends PersonPui
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'age'], 'integer'],
            [['pui_code', 'referal_no', 'pui_case', 'pui', 'pui_contact', 'full_name', 'cid', 'sex', 'nation', 'occupation', 'addr_no', 'addr_villno', 'addr_villname', 'addr_tambon', 'addr_amphur', 'addr_province', 'villcode', 'tamboncode', 'amphurcode', 'provincecode', 'sick_date', 'detect_date', 'report_date', 'report_time', 'reporter_name', 'reporter_phone', 'receiver_name', 'receiver_phone', 'admit_hosp', 'sample_place', 'sample_type','pcr_send_date', 'pcr_result', 'pcr_date', 'pcr_time', 'discharge_result', 'final_dx', 'discharge_date', 'follow_status', 'tracking_status'], 'safe'],
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
        $query = PersonPui::find();

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
            'age' => $this->age,
            'sick_date' => $this->sick_date,
            'detect_date' => $this->detect_date,
            'report_date' => $this->report_date,
            'report_time' => $this->report_time,
        ]);

        $query->andFilterWhere(['like', 'pui_code', $this->pui_code])
            ->andFilterWhere(['like', 'referal_no', $this->referal_no])
            ->andFilterWhere(['like', 'pui_case', $this->pui_case])
            ->andFilterWhere(['like', 'pui', $this->pui])
            ->andFilterWhere(['like', 'pui_contact', $this->pui_contact])
            ->andFilterWhere(['like', 'full_name', $this->full_name])
            ->andFilterWhere(['like', 'cid', $this->cid])
            ->andFilterWhere(['like', 'sex', $this->sex])
            ->andFilterWhere(['like', 'nation', $this->nation])
            ->andFilterWhere(['like', 'occupation', $this->occupation])
            ->andFilterWhere(['like', 'addr_no', $this->addr_no])
            ->andFilterWhere(['like', 'addr_villno', $this->addr_villno])
            ->andFilterWhere(['like', 'addr_villname', $this->addr_villname])
            ->andFilterWhere(['like', 'addr_tambon', $this->addr_tambon])
            ->andFilterWhere(['like', 'addr_amphur', $this->addr_amphur])
            ->andFilterWhere(['like', 'addr_province', $this->addr_province])
            ->andFilterWhere(['like', 'villcode', $this->villcode])
            ->andFilterWhere(['like', 'tamboncode', $this->tamboncode])
            ->andFilterWhere(['like', 'amphurcode', $this->amphurcode])
            ->andFilterWhere(['like', 'provincecode', $this->provincecode])
            ->andFilterWhere(['like', 'reporter_name', $this->reporter_name])
            ->andFilterWhere(['like', 'reporter_phone', $this->reporter_phone])
            ->andFilterWhere(['like', 'receiver_name', $this->receiver_name])
            ->andFilterWhere(['like', 'receiver_phone', $this->receiver_phone])
            ->andFilterWhere(['like', 'admit_hosp', $this->admit_hosp])

            ->andFilterWhere(['like', 'sample_place', $this->sample_place])
            ->andFilterWhere(['like', 'sample_type', $this->sample_type])
            ->andFilterWhere(['like', 'pcr_send_date', $this->pcr_send_date])
            ->andFilterWhere(['like', 'pcr_result', $this->pcr_result])
            ->andFilterWhere(['like', 'pcr_date', $this->pcr_date])
            ->andFilterWhere(['like', 'pcr_time', $this->pcr_time])
            ->andFilterWhere(['like', 'discharge_result', $this->discharge_result])
            ->andFilterWhere(['like', 'final_dx', $this->final_dx])
            ->andFilterWhere(['like', 'discharge_date', $this->discharge_date])
            ->andFilterWhere(['like', 'follow_status', $this->follow_status])
            ->andFilterWhere(['like', 'tracking_status', $this->tracking_status])
            
            ->orderBy(['report_date' => SORT_ASC,]);

        return $dataProvider;
    }
}
