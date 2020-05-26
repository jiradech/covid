<?php
namespace frontend\controllers;

use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;
use frontend\models\Fmd;
use frontend\models\Person;
use yii\helpers\Json;
use linslin\yii2\curl;
use yii\data\ActiveDataProvider;

/**
 * Site controller
 */
class SiteController extends Controller
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
                        'actions' => ['login', 'error', 'upload', 'identified', 'gen-cid', 'search-person'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            $model->password = '';

            $this->layout = '/main_blank';
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return mixed
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail(Yii::$app->params['adminEmail'])) {
                Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
            } else {
                Yii::$app->session->setFlash('error', 'There was an error sending your message.');
            }

            return $this->refresh();
        } else {
            return $this->render('contact', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Displays about page.
     *
     * @return mixed
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionSignup()
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                if (Yii::$app->getUser()->login($user)) {
                    return $this->goHome();
                }
            }
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');

                return $this->goHome();
            } else {
                Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for the provided email address.');
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }


    public function beforeAction($action) 
    { 
        $this->enableCsrfValidation = false; 
        return parent::beforeAction($action); 
    }


    public function actionUpload()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        if (isset($_POST['xml'])) {

            $cid = $_POST['cid'];
            $finger = $_POST['finger'];
            $regis_hosp = $_POST['regis_hosp'];
            $regis_user = $_POST['regis_user'];
            $image = \Yii::$app->security->generateRandomString().'.jpeg';

            $curl = new curl\Curl();
            $response = $curl->setPostParams([
                'Fmd' => $_POST['xml'],
            ])
            ->post('http://203.157.145.4:8080/api/Account/FingerprintIdentified');

            if ($response) {
                $json = json_decode($response);

                if ($json->code == '404') {
                    $data = str_replace(" ","+",$_POST['xml']);
                    $data = str_replace('<?xml+version="1.0"+encoding="UTF-8"?>','<?xml version="1.0" encoding="UTF-8"?>',$data);

                    $fmd = New Fmd();
                    $fmd->cid = $cid;
                    $fmd->finger = $finger;
                    $fmd->regis_hosp = $regis_hosp;
                    $fmd->regis_user = $regis_user;
                    $fmd->image = $image;
                    $fmd->data = $data;
                    $fmd->save(); 


                    $file = \yii\web\UploadedFile::getInstanceByName('finger_image');
                    $file->saveAs(Yii::getAlias('@webroot/').$image);
 

                }

                return Json::encode(['result' => $json->result, 'code' => $json->code, 'msg' => $json->msg]);
            } else {
                return Json::encode(['result' => 'error', 'code' => '500', 'msg' => 'API error']);
            }


        } else {
            return Json::encode(['result' => 'error', 'code' => '404', 'msg' => 'error']);
        }
        

    }

    public function actionIdentified()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        if (isset($_POST['Fmd'])) {

            $curl = new curl\Curl();
            $response = $curl->setPostParams([
                'Fmd' => $_POST['Fmd'],
            ])
            ->post('http://203.157.145.4:8080/api/Account/FingerprintIdentified');

            $data = json_decode($response);

            $person = Person::findOne(['CID' => $data->cid]);//$data->cid
            $result = ['response'=> $data] + ['data' => $person];
            //array_push($result,['data' => $person]);

            return $this->asJson($result);
        } else {

            return 'error';
        }




    }


    public function actionGenCid()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $connection = Yii::$app->db_fingerprint;
        $cid = $connection->createCommand('SELECT auto_cid() AS cid')->queryAll();

        if (strlen($cid[0]['cid']) == 13) {
            return $this->asJson(['result' =>'ok', 'cid' => $cid[0]['cid']]);
        } else {
            return $this->asJson(['result' =>'error', 'cid' => '']);
        }


    }



    public function actionSearchPerson()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $query = Person::find();

        $q = $_POST['q'];
        $q = str_replace("  "," ",$q);
        $q = str_replace("  "," ",$q);


        $keywords = explode(" ",$q);
        if (sizeof($keywords) > 1) {

            $query->orFilterWhere( ['and', [ 'like', 'NAME', $keywords[0].'%' , false], [ 'like', 'LNAME', $keywords[1] ]] );


        } else {
            $query->andFilterWhere(['=', 'cid', $keywords[0]])
                ->orFilterWhere(['like', 'NAME', $keywords[0].'%', false])
                ->orFilterWhere(['like', 'LNAME', $keywords[0], false]);
        }


        $query->orderBy(['NAME' => SORT_ASC]);

        $query->limit(50);




        $person = $query->all();

        if (sizeof($person) > 0){
            $result = ['result'=> 'ok'] + ['count' => sizeof($person)] + ['data' => $person];
        } else {
            $result = ['result'=> 'error'] + ['count' => '0'] + ['data' => ''];
        }

        return $this->asJson($result);
    }




}
