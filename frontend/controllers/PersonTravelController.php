<?php

namespace frontend\controllers;

use Yii;
use app\models\PersonTravel;
use app\models\FollowupTravel;
use frontend\models\PersonTravelSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\Json;
use yii\helpers\ArrayHelper;
use yii\data\ActiveDataProvider;
use app\models\Province;
use app\models\Ampur;
use app\models\Tambon;
use app\models\Village;

/**
 * PersonController implements the CRUD actions for Person model.
 */
class PersonTravelController extends Controller
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
                        'actions' => ['error', 'create', 'get-tambon', 'get-village', 'get-mamphur', 'get-mtambon','get-amphur'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['index', 'view', 'update', 'delete'],
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
     * Lists all Person models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PersonTravelSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Person model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        

        $personModel = $this->findModel($id);
        $Date = date('Y-m-d', strtotime($personModel->date_in));
        $Date = date('Y-m-d', strtotime($Date. ' + 15 days'));

        $followupModel = $model = FollowupTravel::find()
        ->andFilterWhere(['cid' => $personModel->cid])
        ->andFilterWhere(['>=', 'report_date', $personModel->date_in])
        ->andFilterWhere(['<=', 'report_date', $Date])
        ->orderBy('report_date');

        $dataProvider = new ActiveDataProvider([
            'query' => $followupModel,
        ]);

        $person_dup = PersonTravel::find()->select('id')->where(['cid' => $personModel->cid, 'date_in' => $personModel->date_in])->count();


        return $this->render('view', [
            'model' => $personModel,
            'dataProviderFollowup' => $dataProvider,
            'person_dup' => $person_dup,
        ]);
    }

    /**
     * Creates a new Personsqc model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new PersonTravel();



        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            } 
            $amphur       = ArrayHelper::map($this->getAmphur($model->addr_province), 'id', 'name');
            $tambon       = ArrayHelper::map($this->getTambon($model->addr_ampur), 'id', 'name');
            $village      = ArrayHelper::map($this->getVillage($model->addr_tambon), 'id', 'name');

            $mamphur      = ArrayHelper::map($this->getAmphur($model->move_province), 'id', 'name');
            $mtambon      = ArrayHelper::map($this->getTambon($model->move_ampur), 'id', 'name');
            $mvillage      = ArrayHelper::map($this->getVillage($model->move_tambon), 'id', 'name');
        } else {
            $amphur =[];
            $tambon         = [];
            $village       = [];

            $mamphur       = [];
            $mtambon       = [];
             $mvillage       = [];
        }



        return $this->render('create', [
            'model' => $model,
            'amphur' => $amphur,
            'tambon' => $tambon,
            'village' => $village,

            'mamphur' => $mamphur,
            'mtambon' => $mtambon,
            'mvillage'=>$mvillage,
        ]);
    }

    /**
     * Updates an existing Person model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $amphur       = ArrayHelper::map($this->getAmphur($model->addr_province), 'id', 'name');
        $tambon         = ArrayHelper::map($this->getTambon($model->addr_ampur), 'id', 'name');
        //  $district       = ArrayHelper::map($this->getDistrict($model->addr_ampur),'id','name');
        $village       = ArrayHelper::map($this->getVillage($model->addr_tambon), 'id', 'name');

        $mamphur       = ArrayHelper::map($this->getAmphur($model->move_province), 'id', 'name');
        $mtambon       = ArrayHelper::map($this->getTambon($model->move_ampur), 'id', 'name');
        $mvillage      = ArrayHelper::map($this->getVillage($model->move_tambon), 'id', 'name');
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        $stack = [null => "เลือกอำเภอ..."];
        //$mamphur = array_push($stack, $mamphur);


        return $this->render('update', [
            'model' => $model,
            'amphur' => $amphur,
            'tambon' => $tambon,
            'village' => $village,
            'mamphur' => $mamphur,
            'mtambon' => $mtambon,
            'mvillage'=>$mvillage,
            'stack' => $stack,
            // 'district' =>$district,

        ]);
    }

    /**
     * Deletes an existing Person model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $cid = $model->cid;
        $model->delete();

        $personsqc = PersonTravel::find()->select('id')->where(['cid' => $cid])->all();
        if ($person == NULL) {
            FollowupTravel::deleteAll(['cid' => $cid]);
        }



        return $this->redirect(['index']);
    }

    /**
     * Finds the Person model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Person the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PersonTravel::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }



    /**
     * Finds the Person model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Person the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findFollowupModel($id)
    {
        if (($model = FollowupTravel::find()->where('cid > :cid', [':cid' => $id])->one()) !== null) {
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

    //  public function actionGetVillage() {
    //     $out = [];
    //     if (isset($_POST['depdrop_parents'])) {
    //         $ids = $_POST['depdrop_parents'];
    //         $province_id = empty($ids[0]) ? null : $ids[0];
    //         $amphur_id = empty($ids[1]) ? null : $ids[1];
    //         $tambon_id = empty($ids[2]) ? null : $ids[2];

    //         if ($province_id != null) {
    //            $data = $this->getVillage($tambon_id);      
    //            echo Json::encode(['output'=>$data, 'selected'=>'']);
    //            return;
    //         }
    //     }
    //     echo Json::encode(['output'=>'', 'selected'=>'']);
    // }
    
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

    // protected function getVillage($id){

    //     $datas = Village::find()
    //     ->select(['villagecodefull','concat(villagecode," ",villagename) as  villagename'])
    //     ->where(['tamboncode'=>$id])
    //     ->andwhere('villagecode <> 00')
    //     ->all(); 
    //     return $this->MapData($datas,'villagecodefull','villagename');
    // }

    protected function MapData($datas, $fieldId, $fieldName)
    {
        $obj = [];
        foreach ($datas as $key => $value) {
            array_push($obj, ['id' => $value->{$fieldId}, 'name' => $value->{$fieldName}]);
        }
        return $obj;
    }

    public function actionGetMamphur()
    {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if ($parents != null) {
                $mprovince_id = $parents[0];
                $out = $this->getAmphur($mprovince_id);
                echo Json::encode(['output' => $out, 'selected' => '']);
                return;
            }
        }
        echo Json::encode(['output' => '', 'selected' => '']);
    }
    public function actionGetMtambon()
    {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $ids = $_POST['depdrop_parents'];
            $mprovince_id = empty($ids[0]) ? null : $ids[0];
            $mampur_id = empty($ids[1]) ? null : $ids[1];
            if ($mampur_id != null) {
                $data = $this->getMtambon($mampur_id);
                echo Json::encode(['output' => $data, 'selected' => '']);
                return;
            }
        }
        echo Json::encode(['output' => '', 'selected' => '']);
    }
public function actionGetMvillage()
     {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $ids = $_POST['depdrop_parents'];
            $province_id = empty($ids[0]) ? null : $ids[0];
            $amphur_id = empty($ids[1]) ? null : $ids[1];
            $tambon_id = empty($ids[2]) ? null : $ids[2];

            if ($province_id != null) {
               $data = $this->getMvillage($tambon_id);      
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
    protected function getMtambon($id)
    {
        $datas = Tambon::find()
            ->where(['ampurcode' => $id])
            ->andwhere(['NOT LIKE', 'tambonname', '*'])
            ->andwhere(['flag_status' => 0])
            ->all();
        return $this->MapData($datas, 'tamboncodefull', 'tambonname');
    }
    protected function getMvillage($id)
    {
        $datas = Village::find()
            ->select(['villagecode', 'concat(villagecode," ",villagename) as  villagename'])
            ->where(['tamboncode' => $id])
            ->andwhere('villagecode <> 00')
            ->all();
        return $this->MapData($datas, 'villagecode', 'villagename');
    }
}
