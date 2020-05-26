<?php

namespace frontend\controllers;

use Yii;
use app\models\FollowupTravel;
use frontend\models\FollowupTravelSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\PersonTravel;

use yii\helpers\ArrayHelper;
/**
 * FollowupController implements the CRUD actions for Followup model.
 */
class FollowupTravelController extends Controller
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
     * Lists all Followup models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new FollowupTravelSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Followup model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id, $cid = NULL, $person_id = NULL)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
            'cid' => $cid,
            'person_id' => $person_id,
        ]);
    }

    /**
     * Creates a new Followup model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($cid = null, $person_id = NULL)
    {
        $fullname = ""; 
        $model = new FollowupTravel();
        $model->cid = $cid;

        if ($cid) {
            $person = PersonTravel::findOne($cid);
            if ($person) {
                $fullname = $person->fname.' '.$person->lname;
            }
            
        }
        
        if ($model->load(Yii::$app->request->post())) {
          if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->id, 'cid' => $cid, 'person_id' => $person_id]);
            }  else {
                return $this->render('create', [
                    'model' => $model,
                    'fullname' => $fullname,
                    
                ]); 
            }
        }  else {
            //$model->report_date = date("Y-m-d");
            $model->person_id = $person_id;
        }

        return $this->render('create', [
            'model' => $model,
            'fullname' => $fullname,
            
        ]);
    }

    /**
     * Updates an existing Followup model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id, $person_id)
    {
        $model = $this->findModel($id);

        $fullname       = ArrayHelper::map($this->getCid($model->cid),'id','name');


        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id, 'person_id' => $person_id]);
        } else {
            $model->person_id = $person_id;
        }

        return $this->render('update', [
            'model' => $model,
            'fullname'=>$fullname,
        ]);
    }

// ($model->addr_ampur),
// protected function getVillage($id){
//         $datas = Village::find()
//         ->select(['villagecodefull','concat(villagecode," ",villagename) as  villagename'])
//         ->where(['tamboncode'=>$id])
//         ->andwhere('villagecode <> 00')
//         ->all(); 
//         return $this->MapData($datas,'villagecodefull','villagename');
//     }


    /**
     * Deletes an existing Followup model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        
        $model = $this->findModel($id);
        $person = PersonTravel::find()
                            ->select(['id'])
                            ->where(['cid'=>$model->cid])
                            ->one();
        
        $model->delete();

        

        return $this->redirect(['person-travel/view', 'id' => $person->id]);
    }

    /**
     * Finds the Followup model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Followup the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = FollowupTravel::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }



    

    public function actionUserList($q = '###', $page = 1)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        //$profile = Profile::getProfileByUserId(\yii::$app->user->identity->getId());
        $query = PersonTravel::find();
        $query->select(['id', 'fname', 'lname','cid']);
        //$query->leftJoin(['user'], ['profile.user_id' => 'user.id'], []);
        //$query->leftJoin('c_hospital', '`profile`.`off_id` = `c_hospital`.`hoscode`', []);

        $query->Where(['like', 'fname', $q])
            ->orFilterWhere(['like', 'lname', $q])
            ->orFilterWhere(['like', 'cid', $q]);

        $query->andWhere('cid IS NOT NULL');


        



       // $query->offset(($page - 1) * 30);
      //  $query->limit(30);

       $result = ['total_count' => $query->count(), 'incomplete_results' => false, 'items' => $query->all()];

        return $result;
    }


    protected function MapData($datas,$fieldId,$fieldName){
        $obj = [];
        foreach ($datas as $key => $value) {
            array_push($obj, ['id'=>$value->{$fieldId},'name'=>$value->{$fieldName}]);
        }
        return $obj;
    }
protected function getCid($id){
        $datas = PersonTravel::find()
        ->select(['cid','concat(fname," ",lname) as  fname'])
        ->where(['cid'=>$id])
       // ->andwhere(['NOT LIKE', 'ampurname', '*'])
        ->all(); 
        return $this->MapData($datas,'cid','fname');
    }
    
}
