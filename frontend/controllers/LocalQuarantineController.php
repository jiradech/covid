<?php

namespace frontend\controllers;

use Yii;
use app\models\LocalQuarantine;
use app\models\LocalQuarantineSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\Json;
use yii\helpers\ArrayHelper;
use app\models\Province;
use app\models\Ampur;
use app\models\Tambon;
use app\models\Village;

/**
 * LocalQuarantineController implements the CRUD actions for LocalQuarantine model.
 */
class LocalQuarantineController extends Controller
{

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'error','create', 'get-tambon', 'get-village','get-amphur'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['view', 'update', 'delete', 'create'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }


    /**
     * Lists all LocalQuarantine models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new LocalQuarantineSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single LocalQuarantine model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $connection = Yii::$app->db_covid;

    

        $person_data = $connection->cache(function ($connection) use ($model) {
            return $connection->createCommand("
            SELECT
            person.*,
            v.villagecodefull,
                                    IF(v.`villagename` IS NULL,'รหัสผิดพลาด',CONCAT(v.`villagename`, ' ต.', tambon.tambonname)) AS vill_name,
            a.ampurname,
            p.changwatname,
            am.ampurname AS mampurname,
            t.tambonname,
            person.date_in AS d1,
            IF(DATE(NOW()) > person.date_in, '1', '0') d1_ended,
            IF(person.temp >= 37.5,'1','0') d1_fever,
            person.q_sick_sign d1_sick,
            person.temp d1_temp,
            person.sick_sign d1_sign,
            person.status d1_remark,
            DATE_ADD(person.date_in,INTERVAL 1 day) d2,
            IF(DATE(NOW()) > person.date_in, '1', '0') d2_ended,
            f2.q_fever d2_fever,
            f2.q_sick_sign d2_sick,
            f2.temp d2_temp,
            f2.sick_sign d2_sign,
            f2.remark d2_remark,
            DATE_ADD(person.date_in,INTERVAL 2 day) d3,
            IF(DATE(NOW()) > DATE_ADD(person.date_in,INTERVAL 1 day), '1', '0') d3_ended,
            f3.q_fever d3_fever,
            f3.q_sick_sign d3_sick,
            f3.temp d3_temp,
            f3.sick_sign d3_sign,
            f3.remark d3_remark,
            DATE_ADD(person.date_in,INTERVAL 3 day) d4,
            IF(DATE(NOW()) > DATE_ADD(person.date_in,INTERVAL 2 day), '1', '0') d4_ended,
            f4.q_fever d4_fever,
            f4.q_sick_sign d4_sick,
            f4.temp d4_temp,
            f4.sick_sign d4_sign,
            f4.remark d4_remark,
            DATE_ADD(person.date_in,INTERVAL 4 day) d5,
            IF(DATE(NOW()) > DATE_ADD(person.date_in,INTERVAL 3 day), '1', '0') d5_ended,
            f5.q_fever d5_fever,
            f5.q_sick_sign d5_sick,
            f5.temp d5_temp,
            f5.sick_sign d5_sign,
            f5.remark d5_remark,
            DATE_ADD(person.date_in,INTERVAL 5 day) d6,
            IF(DATE(NOW()) > DATE_ADD(person.date_in,INTERVAL 4 day), '1', '0') d6_ended,
            f6.q_fever d6_fever,
            f6.q_sick_sign d6_sick,
            f6.temp d6_temp,
            f6.sick_sign d6_sign,
            f6.remark d6_remark,
            DATE_ADD(person.date_in,INTERVAL 6 day) d7,
            IF(DATE(NOW()) > DATE_ADD(person.date_in,INTERVAL 5 day), '1', '0') d7_ended,
            f7.q_fever d7_fever,
            f7.q_sick_sign d7_sick,
            f7.temp d7_temp,
            f7.sick_sign d7_sign,
            f7.remark d7_remark,
            DATE_ADD(person.date_in,INTERVAL 7 day) d8,
            IF(DATE(NOW()) > DATE_ADD(person.date_in,INTERVAL 6 day), '1', '0') d8_ended,
            f8.q_fever d8_fever,
            f8.q_sick_sign d8_sick,
            f8.temp d8_temp,
            f8.sick_sign d8_sign,
            f8.remark d8_remark,
            DATE_ADD(person.date_in,INTERVAL 8 day) d9,
            IF(DATE(NOW()) > DATE_ADD(person.date_in,INTERVAL 7 day), '1', '0') d9_ended,
            f9.q_fever d9_fever,
            f9.q_sick_sign d9_sick,
            f9.temp d9_temp,
            f9.sick_sign d9_sign,
            f9.remark d9_remark,
            DATE_ADD(person.date_in,INTERVAL 9 day) d10,
            IF(DATE(NOW()) > DATE_ADD(person.date_in,INTERVAL 8 day), '1', '0') d10_ended,
            f10.q_fever d10_fever,
            f10.q_sick_sign d10_sick,
            f10.temp d10_temp,
            f10.sick_sign d10_sign,
            f10.remark d10_remark,
            DATE_ADD(person.date_in,INTERVAL 10 day) d11,
            IF(DATE(NOW()) > DATE_ADD(person.date_in,INTERVAL 9 day), '1', '0') d11_ended,
            f11.q_fever d11_fever,
            f11.q_sick_sign d11_sick,
            f11.temp d11_temp,
            f11.sick_sign d11_sign,
            f11.remark d11_remark,
            DATE_ADD(person.date_in,INTERVAL 11 day) d12,
            IF(DATE(NOW()) > DATE_ADD(person.date_in,INTERVAL 10 day), '1', '0') d12_ended,
            f12.q_fever d12_fever,
            f12.q_sick_sign d12_sick,
            f12.temp d12_temp,
            f12.sick_sign d12_sign,
            f12.remark d12_remark,
            DATE_ADD(person.date_in,INTERVAL 12 day) d13,
            IF(DATE(NOW()) > DATE_ADD(person.date_in,INTERVAL 11 day), '1', '0') d13_ended,
            f13.q_fever d13_fever,
            f13.q_sick_sign d13_sick,
            f13.temp d13_temp,
            f13.sick_sign d13_sign,
            f13.remark d13_remark,
            DATE_ADD(person.date_in,INTERVAL 13 day) d14,
            IF(DATE(NOW()) > DATE_ADD(person.date_in,INTERVAL 12 day), '1', '0') d14_ended,
            f14.q_fever d14_fever,
            f14.q_sick_sign d14_sick,
            f14.temp d14_temp,
            f14.sick_sign d14_sign,
            f14.remark d14_remark,
            IF(DATE(NOW()) > DATE_ADD(person.date_in,INTERVAL 13 day),'1','0') AS pass14days,
                        l_follow.report_date lf_report_date,
            l_follow.q_fever lf_q_fever,
            l_follow.q_sick_sign lf_q_sick_sign,
            l_follow.temp lf_temp,
            l_follow.sick_sign lf_sick_sign,
            pui.id AS pui_id,
            pui.pcr_date,
            pui.discharge_result,
            pui.pcr_result
            FROM
            person
                        LEFT JOIN geo_village
                        ON CONCAT(person.addr_tambon, person.addr_vill_no) = geo_village.villagecodefull
                        LEFT JOIN campur a ON person.addr_ampur = a.ampurcodefull
                        LEFT JOIN tambon  ON person.addr_tambon = tambon.tamboncodefull
                        LEFT JOIN village v ON CONCAT(person.addr_tambon, person.addr_vill_no) = v.villagecodefull
                        LEFT JOIN province p ON person.move_province = p.changwatcode
                LEFT JOIN ampur am ON person.move_ampur = am.ampurcodefull
                LEFT JOIN tambon  t ON person.move_tambon = t.tamboncodefull
                LEFT JOIN person_pui pui ON person.cid = pui.cid
            LEFT JOIN followup f2 ON f2.cid = person.cid AND f2.report_date = DATE_ADD(person.date_in,INTERVAL 1 day)
            LEFT JOIN followup f3 ON f3.cid = person.cid AND f3.report_date = DATE_ADD(person.date_in,INTERVAL 2 day)
            LEFT JOIN followup f4 ON f4.cid = person.cid AND f4.report_date = DATE_ADD(person.date_in,INTERVAL 3 day)
            LEFT JOIN followup f5 ON f5.cid = person.cid AND f5.report_date = DATE_ADD(person.date_in,INTERVAL 4 day)
            LEFT JOIN followup f6 ON f6.cid = person.cid AND f6.report_date = DATE_ADD(person.date_in,INTERVAL 5 day)
            LEFT JOIN followup f7 ON f7.cid = person.cid AND f7.report_date = DATE_ADD(person.date_in,INTERVAL 6 day)
            LEFT JOIN followup f8 ON f8.cid = person.cid AND f8.report_date = DATE_ADD(person.date_in,INTERVAL 7 day)
            LEFT JOIN followup f9 ON f9.cid = person.cid AND f9.report_date = DATE_ADD(person.date_in,INTERVAL 8 day)
            LEFT JOIN followup f10 ON f10.cid = person.cid AND f10.report_date = DATE_ADD(person.date_in,INTERVAL 9 day)
            LEFT JOIN followup f11 ON f11.cid = person.cid AND f11.report_date = DATE_ADD(person.date_in,INTERVAL 10 day)
            LEFT JOIN followup f12 ON f12.cid = person.cid AND f12.report_date = DATE_ADD(person.date_in,INTERVAL 11 day)
            LEFT JOIN followup f13 ON f13.cid = person.cid AND f13.report_date = DATE_ADD(person.date_in,INTERVAL 12 day)
            LEFT JOIN followup f14 ON f14.cid = person.cid AND f14.report_date = DATE_ADD(person.date_in,INTERVAL 13 day)
                        LEFT JOIN (
                        SELECT f.* FROM (SELECT 
                                followup.cid,
                                MAX(followup.report_date) AS report_date
                                FROM 
                                followup
                                GROUP BY
                                followup.cid) a INNER JOIN followup f ON a.cid = f.cid AND a.report_date = f.report_date
                                GROUP BY f.cid
                        ) l_follow ON l_follow.cid = person.cid
            WHERE person.remark = '".$model->local_name."'
            GROUP BY person.cid

            ")->queryAll();
        }, 120, null);
       




        return $this->render('view', [
            'model' => $model,
            'person_data' => $person_data,
        ]);
    }

    /**
     * Creates a new LocalQuarantine model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new LocalQuarantine();

        if ($model->load(Yii::$app->request->post())){
         if ($model->save()) {
            return $this->redirect(['view', 'id' => $model->villagecodefull]);
        }
            $amphur       = ArrayHelper::map($this->getAmphur($model->changwatcode), 'id', 'name');
            $tambon       = ArrayHelper::map($this->getTambon($model->ampurcode), 'id', 'name');
            $village      = ArrayHelper::map($this->getVillage($model->tamboncode), 'id', 'name');
         } else {
            $amphur = [];
            $tambon = [];
            $village= [];
        }

        return $this->render('create', [
            'model' => $model,
            'amphur' => $amphur,
            'tambon' => $tambon,
            'village' => $village,
        ]);
    }

    /**
     * Updates an existing LocalQuarantine model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing LocalQuarantine model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the LocalQuarantine model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return LocalQuarantine the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = LocalQuarantine::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionGetAmphur() {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if ($parents != null) {
                $province_id = $parents[0];
                $out = $this->getAmphur($province_id);
                echo Json::encode(['output'=>$out, 'selected'=>'']);
                return;
            }
        }
        echo Json::encode(['output'=>'', 'selected'=>'']);
    }

    public function actionGetTambon()
    {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $ids = $_POST['depdrop_parents'];
            $province_id = empty($ids[0]) ? null : $ids[0];
            $amphur_id = empty($ids[1]) ? null : $ids[1];
            if ($province_id != null) {
               $data = $this->getTambon($amphur_id);      
               echo Json::encode(['output'=>$data, 'selected'=>'']);
               return;
            }
        }
        echo Json::encode(['output'=>'', 'selected'=>'']);
    }



    public function actionGetVillage()
     {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $ids = $_POST['depdrop_parents'];
            $province_id = empty($ids[0]) ? null : $ids[0];
            $amphur_id = empty($ids[1]) ? null : $ids[1];
            $tambon_id = empty($ids[2]) ? null : $ids[2];

            if ($province_id != null) {
               $data = $this->getVillage($tambon_id);      
               echo Json::encode(['output'=>$data, 'selected'=>'']);
               return;
            }
        }
        echo Json::encode(['output'=>'', 'selected'=>'']);
    }

protected function getAmphur($id)
    {
        $datas = Ampur::find()
            ->where(['changwatcode' => $id])
            ->andwhere(['NOT LIKE', 'ampurname', '*'])
            ->all();
        return $this->MapData($datas, 'ampurcodefull', 'ampurname');
    }
    protected function getTambon($id)
    {
        $datas = Tambon::find()
            ->where(['ampurcode' => $id])
            ->andwhere(['NOT LIKE', 'tambonname', '*'])
            ->andwhere(['flag_status' => 0])
            ->all();
        return $this->MapData($datas, 'tamboncodefull', 'tambonname');
    }

    protected function getVillage($id)
    {
        $datas = Village::find()
            ->select(['villagecode', 'concat(villagecode," ",villagename) as  villagename'])
            ->where(['tamboncode' => $id])
            ->andwhere('villagecode <> 00')
            ->all();
        return $this->MapData($datas, 'villagecode', 'villagename');
    }

   

    protected function MapData($datas, $fieldId, $fieldName)
    {
        $obj = [];
        foreach ($datas as $key => $value) {
            array_push($obj, ['id' => $value->{$fieldId}, 'name' => $value->{$fieldName}]);
        }
        return $obj;
    } 
}
