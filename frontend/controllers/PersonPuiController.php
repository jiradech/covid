<?php

namespace frontend\controllers;

use Yii;
use app\models\PersonPui;
use app\models\PuiLab;
use frontend\models\PersonPuiSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use app\models\Ampur;
use app\models\Tambon;
use app\models\Village;
use yii\data\ActiveDataProvider;
/**
 * PersonpuiController implements the CRUD actions for PersonPui model.
 */
class PersonPuiController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all PersonPui models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PersonpuiSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single PersonPui model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */

    // public function actionView($id)
    // {

    //     return $this->render('view', [
    //         'model' => $this->findModel($id),
    //     ]);
    // }
    public function actionView($id)
    {
        $personpuiModel = $this->findModel($id);
        $puilabModel = $model = PuiLab::find()
                                ->andFilterWhere(['pui_code' => $personpuiModel->pui_code])
                                ->orderBy('pcr_send_date' );

        $dataProvider = new ActiveDataProvider([
            'query' => $puilabModel,
        ]);



        return $this->render('view', [
            'model' => $personpuiModel,
            'dataProviderPuiLab' => $dataProvider,
        ]);
    }

    /**
     * Creates a new PersonPui model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new PersonPui();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['pui-lab/create', 'pui_code' => $model->pui_code,]);
        }

        return $this->render('create', [
            'model' => $model,
            'amphur'=> [],
            'district' =>[],
            'village'=>[],
        ]);
    }

    /**
     * Updates an existing PersonPui model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $amphur         = ArrayHelper::map($this->getAmphur($model->provincecode),'id','name');
        $district       = ArrayHelper::map($this->getDistrict($model->amphurcode),'id','name');
        $village       = ArrayHelper::map($this->getVillage($model->tamboncode),'id','name');
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
            'amphur'=> $amphur,
            'district' =>$district,
            'village' =>$village,
        ]);
    }

    /**
     * Deletes an existing PersonPui model.
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
     * Finds the PersonPui model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return PersonPui the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PersonPui::findOne($id)) !== null) {
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

    public function actionGetDistrict() {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $ids = $_POST['depdrop_parents'];
            $province_id = empty($ids[0]) ? null : $ids[0];
            $amphur_id = empty($ids[1]) ? null : $ids[1];
            if ($province_id != null) {
               $data = $this->getDistrict($amphur_id);      
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

    protected function getAmphur($id){
        $datas = Ampur::find()
        ->where(['changwatcode'=>$id])
        ->andwhere(['NOT LIKE', 'ampurname', '*'])
        ->all(); 
        return $this->MapData($datas,'ampurcodefull','ampurname');
    }

    protected function getDistrict($id){
        $datas = Tambon::find()
        ->where(['ampurcode'=>$id])
        ->andwhere(['NOT LIKE', 'tambonname', '*'])
        ->andwhere(['flag_status'=>0])
        ->all(); 
        return $this->MapData($datas,'tamboncodefull','tambonname');
    }

    protected function getVillage($id){

        $datas = Village::find()
        ->select(['villagecodefull','concat(villagecode," ",villagename) as  villagename'])
        ->where(['tamboncode'=>$id])
        ->andwhere('villagecode <> 00')
        ->all(); 
        return $this->MapData($datas,'villagecodefull','villagename');
    }

    protected function MapData($datas,$fieldId,$fieldName){
        $obj = [];
        foreach ($datas as $key => $value) {
            array_push($obj, ['id'=>$value->{$fieldId},'name'=>$value->{$fieldName}]);
        }
        return $obj;
    }
}
