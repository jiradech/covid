<?php

namespace frontend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\PersonTravel;

/**
 * PersonSearch represents the model behind the search form of `app\models\PersonSqc`.
 */
class PersonTravelSearch extends PersonTravel
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'age', 'addr_number', 'addr_vill_no', 'c_family'], 'integer'],
            [['cid', 'prename', 'fname', 'lname', 'sex', 'occupation', 'phone_number', 'date_in','date_out', 'addr_tambon', 'addr_ampur', 'addr_province', 'nation', 'house_type', 'q_from_risk_country', 'q_close_to_case', 'risk_from_risk_country', 'risk_korea_worker', 'risk_cambodia_border', 'risk_from_bangkok', 'q_family_from_risk_country', 'q_close_to_foreigner', 'q_healthcare_staff', 'q_close_to_group_fever', 'risk_place', 'risk_group_place', 'risk_case_place', 'note', 'reporter_name', 'reporter_phone', 'date_stamp','move_province','move_ampur','move_tambon','move_vill_no','remark'], 'safe'],
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
        $query = PersonTravel::find();

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
            'date_in' => $this->date_in,
            'date_out'=> $this->date_out,
            'addr_number' => $this->addr_number,
            'addr_vill_no' => $this->addr_vill_no,
            'c_family' => $this->c_family,
            'date_stamp' => $this->date_stamp,
        ]);

        $query->andFilterWhere(['like', 'cid', $this->cid])
            ->andFilterWhere(['like', 'prename', $this->prename])
            ->andFilterWhere(['like', 'fname', $this->fname])
            ->andFilterWhere(['like', 'lname', $this->lname])
            ->andFilterWhere(['like', 'sex', $this->sex])
            ->andFilterWhere(['like', 'occupation', $this->occupation])
            ->andFilterWhere(['like', 'phone_number', $this->phone_number])
            ->andFilterWhere(['like', 'addr_tambon', $this->addr_tambon])
            ->andFilterWhere(['like', 'addr_ampur', $this->addr_ampur])
            ->andFilterWhere(['like', 'addr_province', $this->addr_province])
            ->andFilterWhere(['like', 'nation', $this->nation])
            ->andFilterWhere(['like', 'house_type', $this->house_type])
            ->andFilterWhere(['like', 'q_from_risk_country', $this->q_from_risk_country])
            ->andFilterWhere(['like', 'q_close_to_case', $this->q_close_to_case])
            ->andFilterWhere(['like', 'risk_from_risk_country', $this->risk_from_risk_country])
            ->andFilterWhere(['like', 'risk_korea_worker', $this->risk_korea_worker])
            ->andFilterWhere(['like', 'risk_cambodia_border', $this->risk_cambodia_border])
            ->andFilterWhere(['like', 'risk_from_bangkok', $this->risk_from_bangkok])
            ->andFilterWhere(['like', 'q_family_from_risk_country', $this->q_family_from_risk_country])
            ->andFilterWhere(['like', 'q_close_to_foreigner', $this->q_close_to_foreigner])
            ->andFilterWhere(['like', 'q_healthcare_staff', $this->q_healthcare_staff])
            ->andFilterWhere(['like', 'q_close_to_group_fever', $this->q_close_to_group_fever])
            ->andFilterWhere(['like', 'risk_place', $this->risk_place])
            ->andFilterWhere(['like', 'risk_group_place', $this->risk_group_place])
            ->andFilterWhere(['like', 'risk_case_place', $this->risk_case_place])
            ->andFilterWhere(['like', 'note', $this->note])
            ->andFilterWhere(['like', 'reporter_name', $this->reporter_name])
            ->andFilterWhere(['like', 'reporter_phone', $this->reporter_phone])
            ->andFilterWhere(['like', 'move_province', $this->move_province])
            ->andFilterWhere(['like', 'move_ampur', $this->move_ampur])
            ->andFilterWhere(['like', 'move_tambon', $this->move_tambon])
            ->andFilterWhere(['like', 'move_vill_no', $this->move_vill_no])
            ->andFilterWhere(['like', 'remark', $this->remark]);

        return $dataProvider;
    }
}


