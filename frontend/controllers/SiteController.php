<?php

namespace frontend\controllers;

use app\models\Locate;
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
use yii\helpers\ArrayHelper;
use app\models\Ampur;
use app\models\Tambon;
use app\models\Village;
use yii\helpers\Json;
use app\models\Covid19th;


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
                        'actions' => ['index', 'district', 'covid19api',  'quarantine', 'login', 'error', 'self-screening',  'gen-cid', 'search-person'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index', 'lost-input', 'subdistrict', 'village', 'sick-case', 'clear-cache','passdays','getindays','gethbdays','observe'],
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
        $model = new Locate();
        if ($model->load(Yii::$app->request->post())) {
            if ($model->village != '') {
                return $this->redirect(['village', 'villagecode' => $model->subdistrict.$model->village]);
            } elseif ($model->subdistrict != '') {
                return $this->redirect(['subdistrict', 'tamboncodefull' => $model->subdistrict]);
            } elseif ($model->district != '') {
                return $this->redirect(['district', 'ampurcodefull' => $model->district]);
            } 
        }


        // $tambon         = ArrayHelper::map($this->getTambon($model->district), 'id', 'name');
        // $village       = ArrayHelper::map($this->getVillage($model->subdistrict), 'id', 'name');





        $connection = Yii::$app->db_covid;



        $days = 60;
        $sql_query = "SELECT ";
        $d = 0;
        for ($x = $days; $x >= 0; $x--) {
            $d++;
            $sql_query .= "DATE(SUBDATE(NOW(),INTERVAL ".($x)." day)) d".($d).",";
            $sql_query .= "YEAR(DATE(SUBDATE(NOW(),INTERVAL ".($x)." day))) d".($d)."_y,";
            $sql_query .= "MONTH(DATE(SUBDATE(NOW(),INTERVAL ".($x)." day))) -1 d".($d)."_m,";
            $sql_query .= "DAY(DATE(SUBDATE(NOW(),INTERVAL ".($x)." day))) d".($d)."_d,";
            $sql_query .= " (SELECT COUNT(person_pui.id) c FROM person_pui WHERE detect_date = DATE(SUBDATE(NOW(),INTERVAL ".($x)." day)) GROUP BY detect_date) d".($d)."_case, ";
            $sql_query .= " (SELECT COUNT(IF(person_pui.pcr_result LIKE '%Detected%', person_pui.id,NULL)) c FROM person_pui WHERE detect_date = DATE(SUBDATE(NOW(),INTERVAL ".($x)." day)) GROUP BY detect_date) d".($d)."_pos, ";

        }
        $sql_query .= " 0 AS dummy";

        $trend  = $connection->cache(function ($connection) use ($sql_query) {
            return $connection->createCommand($sql_query)->queryAll();
        }, 180, null);


        $gis  = $connection->cache(function ($connection) {
            return $connection->createCommand("
 
            SELECT
            village.villagecodefull,IF(village.`villagename` IS NULL,'*รหัสเขตปกครองผิดพลาด',CONCAT(village.`villagename`, ' ต.', t.tambonname)) AS vill_name,
            a.ampurname,
            COUNT(*) AS detected,
            COUNT(IF(pui.end_date > DATE(NOW()), pui.id, NULL) ) AS pui_count, 
            COUNT(IF(pui.pcr_result LIKE '%Detected%' AND pui.end_date > DATE(NOW()), pui.id, NULL) ) AS pui_pos_count, 
            CONCAT(SUBSTRING_INDEX(SUBSTRING_INDEX(geo_village.coordinates, ',', 2), ',', -1), ',', SUBSTRING_INDEX(geo_village.coordinates, ',', 1)) AS coordinates,
            SUM(IF(DATE(NOW()) > DATE_ADD(person.date_in,INTERVAL 13 day),'1','0')) AS pass14days,
            SUM(IF(y.q_fever = 1 OR (person.temp >= 37.5 AND y.q_fever  IS NULL), 1, 0)) AS q_fever,
                        SUM(y.q_sick_sign) AS q_sick_sign,
                        SUM(IF(y.q_fever = '1' OR y.q_sick_sign = '1',1,0)) AS one_sign,
                        SUM(IF(y.temp >= 37.5,1,0)) AS person_in_risk
                        
            FROM
            (SELECT * FROM person GROUP BY cid) person
            LEFT JOIN
                (SELECT f.cid, f.q_fever, f.temp, f.q_sick_sign FROM (SELECT 
                            followup.cid,
                            MAX(followup.report_date) AS report_date
                            FROM 
                            followup
                            GROUP BY
                            followup.cid) a INNER JOIN followup f ON a.cid = f.cid AND a.report_date = f.report_date
                            WHERE
                            f.temp >= 37.5 OR f.q_sick_sign = '1'
                            GROUP BY f.cid) y ON y.cid = person.cid

            LEFT JOIN geo_village
            ON CONCAT(person.addr_tambon, person.addr_vill_no) = geo_village.villagecodefull
            LEFT JOIN village
            ON CONCAT(person.addr_tambon, person.addr_vill_no) = village.villagecodefull
            LEFT JOIN person_pui pui ON person.cid = pui.cid
            LEFT JOIN ampur a ON person.addr_ampur = a.ampurcodefull
            LEFT JOIN tambon t ON person.addr_tambon = t.tamboncodefull
            GROUP BY
            village.villagecodefull;
            ")->queryAll();
        }, 180, null);



        $sumary = $connection->cache(function ($connection) {
            return $connection->createCommand("
            SELECT COUNT(DISTINCT person.cid) AS total,
            COUNT(DISTINCT IF(person.date_in = DATE(NOW()) ,person.cid,NULL)) AS today,
            COUNT(DISTINCT person.cid) AS total_followed,

            COUNT(DISTINCT IF(((person.status IS NULL OR person.status NOT IN ('0', '1')) AND y.temp >= 37.5) OR ((person.status IS NULL OR person.status NOT IN ('0', '1')) AND person.temp >= 37.5 AND y.temp  IS NULL), person.cid,NULL)) AS one_sign,

            COUNT(DISTINCT IF(DATE(NOW()) > DATE_ADD(person.date_in,INTERVAL 13 day),person.cid,NULL)) AS pass14days
            FROM person
            LEFT JOIN
                (SELECT f.cid, f.q_fever, f.temp, f.q_sick_sign FROM (SELECT 
                            followup.cid,
                            MAX(followup.report_date) AS report_date
                            FROM 
                            followup
                            GROUP BY
                            followup.cid) a INNER JOIN followup f ON a.cid = f.cid AND a.report_date = f.report_date
                            GROUP BY f.cid) y ON y.cid = person.cid
        ")->queryAll();
        }, 180, null);



        $district = $connection->cache(function ($connection) {
            return $connection->createCommand("
            SELECT
                a.ampurcodefull,
                DATE(DATE_ADD(NOW(),INTERVAL -1 day)) AS yesterday,
                IF(a.ampurname IS NULL, '*รหัสเขตปกครองผิดพลาด', a.ampurname) AS ampurname,

                COUNT(DISTINCT IF(person.date_in =  DATE(DATE_ADD(NOW(),INTERVAL -1 day)), person.cid, NULL)) AS newcase,
                COUNT(DISTINCT person.cid) AS detected,
                COUNT(DISTINCT IF(NOW() >  DATE(DATE_ADD(person.date_in,INTERVAL 14 day)), person.cid, NULL)) AS newcase_ended,
                
                COUNT(DISTINCT IF(risk_korea_worker = '1' AND person.date_in =  DATE(DATE_ADD(NOW(),INTERVAL -1 day)),person.cid,null)) AS risk_korea_worker_new,
                COUNT(DISTINCT IF(risk_korea_worker = '1',person.cid,null)) AS risk_korea_worker,
                COUNT(DISTINCT IF(risk_korea_worker = '1' AND NOW() >  DATE(DATE_ADD(person.date_in,INTERVAL 14 day)), person.cid, NULL)) AS risk_korea_worker_ended,

                COUNT(DISTINCT IF(risk_cambodia_border = '1' AND person.date_in =  DATE(DATE_ADD(NOW(),INTERVAL -1 day)),person.cid,null)) AS risk_cambodia_border_new,
                COUNT(DISTINCT IF(risk_cambodia_border = '1',person.cid,null)) AS risk_cambodia_border,
                COUNT(DISTINCT IF(risk_cambodia_border = '1' AND NOW() >  DATE(DATE_ADD(person.date_in,INTERVAL 14 day)), person.cid, NULL)) AS risk_cambodia_border_ended,

                0 AS q_close_to_case_new,
                0 AS q_close_to_case,
                0 AS q_close_to_case_ended,

                0 AS q_close_to_case_new,
                COUNT(DISTINCT pui.id) AS q_close_to_case,
                COUNT(DISTINCT IF(pui.id IS NOT NULL AND NOW() >  pui.discharge_date IS NOT NULL, pui.id, NULL)) AS q_close_to_case_ended,



                COUNT(DISTINCT IF(risk_from_bangkok = '1' AND person.date_in =  DATE(DATE_ADD(NOW(),INTERVAL -1 day)),person.cid,null)) AS risk_from_bangkok_new,
                COUNT(DISTINCT IF(risk_from_bangkok = '1',person.cid,null)) AS risk_from_bangkok,
                COUNT(DISTINCT IF(risk_from_bangkok = '1' AND NOW() >  DATE(DATE_ADD(person.date_in,INTERVAL 14 day)), person.cid, NULL)) AS risk_from_bangkok_ended,

                COUNT(DISTINCT IF(q_from_risk_country = '1' AND person.date_in =  DATE(DATE_ADD(NOW(),INTERVAL -1 day)),person.cid,null)) AS q_from_risk_country_new,
                COUNT(DISTINCT IF(q_from_risk_country = '1',person.cid,null)) AS q_from_risk_country,
                COUNT(DISTINCT IF(q_from_risk_country = '1' AND NOW() >  DATE(DATE_ADD(person.date_in,INTERVAL 14 day)), person.cid, NULL)) AS q_from_risk_country_ended,
                
                COUNT(DISTINCT IF(y.q_fever = '1' OR y.q_sick_sign = '1',person.cid,null)) AS one_sign,
                COUNT(pui.id) AS pui_count, 
                        COUNT(IF(pui.pcr_result LIKE '%Detected%', pui.id, NULL) ) AS pui_pos_count
            FROM
                (SELECT * FROM person GROUP BY cid) person
                
                LEFT JOIN ampur a ON person.addr_ampur = a.ampurcodefull 
                LEFT JOIN person_pui pui ON person.cid = pui.cid
                LEFT JOIN
                (SELECT f.cid, f.q_fever, f.q_sick_sign FROM (SELECT 
                            followup.cid,
                            MAX(followup.report_date) AS report_date
                            FROM 
                            followup
                            GROUP BY
                            followup.cid) a INNER JOIN followup f ON a.cid = f.cid AND a.report_date = f.report_date
                            WHERE
                            f.temp >= 37.5 OR f.q_sick_sign = '1'
                            GROUP BY f.cid) y ON y.cid = person.cid
            GROUP BY
            a.ampurname
            ")->queryAll();
        }, 180, null);



        $calendar = $connection->cache(function ($connection) {
            return $connection->createCommand("
            SELECT
            person.date_in,
            CONCAT('[ new Date(', YEAR(date_in), ', ', MONTH(date_in) -1 ,', ', DAY(date_in), '), ', COUNT(person.id), ' ],') AS d,
            COUNT(person.id) AS total
            FROM
            (SELECT * FROM person GROUP BY cid) person
            WHERE
            date_in BETWEEN '2020-01-01' AND DATE(NOW())
            GROUP BY
            person.date_in
            ORDER BY
            person.date_in
            ")->queryAll();
        }, 180, null);


        $covid = $connection->cache(function ($connection) {
            return $connection->createCommand("
            SELECT
            p.provincecode,
            SUM(IF(p.pcr_result LIKE '%Detected%',1,0) )as infect,
            SUM(IF(p.pcr_result LIKE '%Detected%' AND p.follow_status LIKE '%ระหว่างการรักษา%',1,0) )as treat,
            SUM(IF(p.pcr_result LIKE '%Detected%' AND p.follow_status LIKE '%หาย%',1,0) )as cure,
            SUM(IF(p.pcr_result LIKE '%Detected%' AND p.discharge_result LIKE '%เสีย%' ,1,0) )as dead
            FROM person_pui p
            ")->queryAll();
        }, 180, null);


        $observe = $connection->cache(function ($connection) {
            return $connection->createCommand("
            SELECT COUNT(DISTINCT person_sqc.cid) AS total,
            COUNT(DISTINCT IF(person_sqc.date_in = DATE(NOW()) ,person_sqc.cid,NULL)) AS today,
            COUNT(DISTINCT person_sqc.cid) AS total_followed,

            COUNT(DISTINCT IF(person_sqc.status NOT IN ('0', '1') OR person_sqc.status IS NULL, person_sqc.cid,NULL)) AS live,
                        COUNT(DISTINCT IF(person_sqc.status IN ('0', '1') , person_sqc.cid,NULL)) AS leaved,

            COUNT(DISTINCT IF(DATE(NOW()) > DATE_ADD(person_sqc.date_in,INTERVAL 13 day),person_sqc.cid,NULL)) AS pass14days
            FROM person_sqc
            LEFT JOIN
                (SELECT f.cid, f.q_fever, f.temp, f.q_sick_sign FROM (SELECT 
                            followup_sqc.cid,
                            MAX(followup_sqc.report_date) AS report_date
                            FROM 
                            followup_sqc
                            GROUP BY
                            followup_sqc.cid) a INNER JOIN followup_sqc f ON a.cid = f.cid AND a.report_date = f.report_date
                            GROUP BY f.cid) y ON y.cid = person_sqc.cid
        ")->queryAll();
        }, 180, null);  


        return $this->render('index', [
            'gis' => $gis,
            'sumary' => $sumary,
            'district' => $district,
            'calendar' => $calendar,
            'model' => $model,
            'trend' => $trend,
            'days' => $days,
            'covid' => $covid,
            'observe' => $observe,
        ]);
    }


    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionDistrict($ampurcodefull)
    {
        $condition = "";
        $condition2 = "";
        $condition3 = "";
        if ($ampurcodefull == '0') {
            $condition = 'a.ampurcodefull IS NULL';
            $condition2 = 'person.addr_ampur IS NULL';
            $condition3 = "am.ampurcodefull IS NULL";
        } else {
            $condition = "a.ampurcodefull = '" . $ampurcodefull . "'";
            $condition2 = "person.addr_ampur= '" . $ampurcodefull . "'";
            $condition3 = "person.addr_ampur = '" . $ampurcodefull . "'";
        }


        $connection = Yii::$app->db_covid;
        $gis = $connection->cache(function ($connection) use ($condition) {
            return $connection->createCommand("
            SELECT
            village.villagecodefull,IF(village.`villagename` IS NULL,'*รหัสเขตปกครองผิดพลาด',CONCAT(village.`villagename`, ' ต.', t.tambonname)) AS vill_name,
            a.ampurname,
            COUNT(*) AS detected,
                        COUNT(pui.id) AS pui_count, 
                        COUNT(IF(pui.pcr_result LIKE '%Detected%', pui.id, NULL) ) AS pui_pos_count, 
            CONCAT(SUBSTRING_INDEX(SUBSTRING_INDEX(geo_village.coordinates, ',', 2), ',', -1), ',', SUBSTRING_INDEX(geo_village.coordinates, ',', 1)) AS coordinates,
            SUM(IF(DATE(NOW()) > DATE_ADD(person.date_in,INTERVAL 13 day),'1','0')) AS pass14days,
            SUM(IF(y.q_fever = 1 OR (person.temp >= 37.5 AND y.q_fever  IS NULL), 1, 0)) AS q_fever,
                        SUM(y.q_sick_sign) AS q_sick_sign,
                        SUM(IF(y.q_fever = '1' OR y.q_sick_sign = '1',1,0)) AS one_sign,
                        SUM(IF(y.q_fever = '1' AND y.q_sick_sign = '1',1,0)) AS person_in_risk
                        
            FROM
            (SELECT * FROM person GROUP BY cid) person
            LEFT JOIN
                (SELECT f.cid, f.q_fever, f.q_sick_sign FROM (SELECT 
                            followup.cid,
                            MAX(followup.report_date) AS report_date
                            FROM 
                            followup
                            GROUP BY
                            followup.cid) a INNER JOIN followup f ON a.cid = f.cid AND a.report_date = f.report_date
                            WHERE
                            f.temp >= 37.5 OR f.q_sick_sign = '1'
                            GROUP BY f.cid) y ON y.cid = person.cid

            LEFT JOIN geo_village
            ON CONCAT(person.addr_tambon, person.addr_vill_no) = geo_village.villagecodefull
            LEFT JOIN village
            ON CONCAT(person.addr_tambon, person.addr_vill_no) = village.villagecodefull
            LEFT JOIN person_pui pui ON person.cid = pui.cid
            LEFT JOIN ampur a ON person.addr_ampur = a.ampurcodefull
            LEFT JOIN tambon t ON person.addr_tambon = t.tamboncodefull
            WHERE " . $condition . "
            GROUP BY
            village.villagecodefull;

            ")->queryAll();
        }, 180, null);




        $sumary = $connection->cache(function ($connection) use ($condition2) {
            return $connection->createCommand(
                "
            SELECT COUNT(DISTINCT person.cid) AS total,
            COUNT(DISTINCT IF(person.date_in = DATE(NOW()) ,person.cid,NULL)) AS today,
            COUNT(DISTINCT person.cid) AS total_followed,
            #COUNT(DISTINCT IF(y.temp >= 37.5 ,person.cid,NULL)) AS one_sign,
            COUNT(DISTINCT IF(y.temp >= 37.5 OR (person.temp >= 37.5 AND y.temp  IS NULL), person.cid,NULL)) AS one_sign,
            COUNT(DISTINCT IF(DATE(NOW()) > DATE_ADD(person.date_in,INTERVAL 13 day),person.cid,NULL)) AS pass14days
            FROM person
            LEFT JOIN
                (SELECT f.cid, f.q_fever, f.temp, f.q_sick_sign FROM (SELECT 
                            followup.cid,
                            MAX(followup.report_date) AS report_date
                            FROM 
                            followup
                            GROUP BY
                            followup.cid) a INNER JOIN followup f ON a.cid = f.cid AND a.report_date = f.report_date
                            GROUP BY f.cid) y ON y.cid = person.cid

            WHERE " . $condition2
            )->queryAll();
        }, 180, null);



        $district = $connection->cache(function ($connection) use ($condition3) {
            return $connection->createCommand("
            SELECT
                person.addr_tambon AS tamboncodefull,
                DATE(DATE_ADD(NOW(),INTERVAL -1 day)) AS yesterday,
                IF(a.tambonname IS NULL, '*รหัสเขตปกครองผิดพลาด', a.tambonname) AS tambonname,

                COUNT(DISTINCT IF(person.date_in =  DATE(DATE_ADD(NOW(),INTERVAL -1 day)), person.cid, NULL)) AS newcase,
                COUNT(DISTINCT person.cid) AS detected,
                COUNT(DISTINCT IF(NOW() >  DATE(DATE_ADD(person.date_in,INTERVAL 14 day)), person.cid, NULL)) AS newcase_ended,
                
                COUNT(DISTINCT IF(risk_korea_worker = '1' AND person.date_in =  DATE(DATE_ADD(NOW(),INTERVAL -1 day)),person.cid,null)) AS risk_korea_worker_new,
                COUNT(DISTINCT IF(risk_korea_worker = '1',person.cid,null)) AS risk_korea_worker,
                COUNT(DISTINCT IF(risk_korea_worker = '1' AND NOW() >  DATE(DATE_ADD(person.date_in,INTERVAL 14 day)), person.cid, NULL)) AS risk_korea_worker_ended,

                COUNT(DISTINCT IF(risk_cambodia_border = '1' AND person.date_in =  DATE(DATE_ADD(NOW(),INTERVAL -1 day)),person.cid,null)) AS risk_cambodia_border_new,
                COUNT(DISTINCT IF(risk_cambodia_border = '1',person.cid,null)) AS risk_cambodia_border,
                COUNT(DISTINCT IF(risk_cambodia_border = '1' AND NOW() >  DATE(DATE_ADD(person.date_in,INTERVAL 14 day)), person.cid, NULL)) AS risk_cambodia_border_ended,

                0 AS q_close_to_case_new,
                0 AS q_close_to_case,
                0 AS q_close_to_case_ended,

                0 AS q_close_to_case_new,
                COUNT(DISTINCT pui.id) AS q_close_to_case,
                COUNT(DISTINCT IF(pui.id IS NOT NULL AND NOW() >  pui.discharge_date IS NOT NULL, pui.id, NULL)) AS q_close_to_case_ended,



                COUNT(DISTINCT IF(risk_from_bangkok = '1' AND person.date_in =  DATE(DATE_ADD(NOW(),INTERVAL -1 day)),person.cid,null)) AS risk_from_bangkok_new,
                COUNT(DISTINCT IF(risk_from_bangkok = '1',person.cid,null)) AS risk_from_bangkok,
                COUNT(DISTINCT IF(risk_from_bangkok = '1' AND NOW() >  DATE(DATE_ADD(person.date_in,INTERVAL 14 day)), person.cid, NULL)) AS risk_from_bangkok_ended,

                COUNT(DISTINCT IF(q_from_risk_country = '1' AND person.date_in =  DATE(DATE_ADD(NOW(),INTERVAL -1 day)),person.cid,null)) AS q_from_risk_country_new,
                COUNT(DISTINCT IF(q_from_risk_country = '1',person.cid,null)) AS q_from_risk_country,
                COUNT(DISTINCT IF(q_from_risk_country = '1' AND NOW() >  DATE(DATE_ADD(person.date_in,INTERVAL 14 day)), person.cid, NULL)) AS q_from_risk_country_ended,
                
                COUNT(DISTINCT IF(y.q_fever = '1' OR y.q_sick_sign = '1',person.cid,null)) AS one_sign,
                COUNT(pui.id) AS pui_count, 
                        COUNT(IF(pui.pcr_result LIKE '%Detected%', pui.id, NULL) ) AS pui_pos_count
            FROM
                (SELECT * FROM person GROUP BY cid) person

                LEFT JOIN ampur am ON person.addr_ampur = am.ampurcodefull 
                LEFT JOIN person_pui pui ON person.cid = pui.cid
                LEFT JOIN
                (SELECT f.cid, f.q_fever, f.q_sick_sign FROM (SELECT 
                            followup.cid,
                            MAX(followup.report_date) AS report_date
                            FROM 
                            followup
                            GROUP BY
                            followup.cid) a INNER JOIN followup f ON a.cid = f.cid AND a.report_date = f.report_date
                            WHERE
                            f.temp >= 37.5 OR f.q_sick_sign = '1'
                            GROUP BY f.cid) y ON y.cid = person.cid
                LEFT JOIN tambon a ON person.addr_ampur = a.ampurcode AND person.addr_tambon = a.tamboncodefull
            WHERE " . $condition3 . "
            GROUP BY
            a.tambonname

    ")->queryAll();
        }, 180, null);




        $calendar = $connection->cache(function ($connection) use ($ampurcodefull) {
            return $connection->createCommand("
            SELECT
            person.date_in,
            CONCAT('[ new Date(', YEAR(date_in), ', ', MONTH(date_in) -1 ,', ', DAY(date_in), '), ', COUNT(person.id), ' ],') AS d,
            COUNT(person.id) AS total
            FROM
            (SELECT * FROM person GROUP BY cid) person
            WHERE
            date_in BETWEEN '2020-01-01' AND DATE(NOW())
            AND person.addr_ampur = '" . $ampurcodefull . "'
            GROUP BY
            person.date_in
            ORDER BY
            person.date_in
            ")->queryAll();
        }, 180, null);

        $covid = $connection->cache(function ($connection) use ($ampurcodefull) {
            return $connection->createCommand("
            SELECT
            p.provincecode,
            SUM(IF(p.pcr_result LIKE '%Detected%',1,0) )as infect,
            SUM(IF(p.pcr_result LIKE '%Detected%' AND p.follow_status LIKE '%ระหว่างการรักษา%',1,0) )as treat,
            SUM(IF(p.pcr_result LIKE '%Detected%' AND p.follow_status LIKE '%หาย%',1,0) )as cure,
            SUM(IF(p.pcr_result LIKE '%Detected%' AND p.discharge_result LIKE '%เสีย%' ,1,0) )as dead
            FROM person_pui p 
            ")->queryAll();
        }, 180, null);

        $covid_dis = $connection->cache(function ($connection) use ($ampurcodefull) {
            return $connection->createCommand("
            SELECT
						p.admit_hosp,
						h.hosname,
            h.provcode,
						h.distcode,
            SUM(IF(p.pcr_result LIKE '%Detected%',1,0) )as infect,
            SUM(IF(p.pcr_result LIKE '%Detected%' AND p.follow_status LIKE '%ระหว่างการรักษา%',1,0) )as treat,
            SUM(IF(p.pcr_result LIKE '%Detected%' AND p.follow_status LIKE '%หาย%',1,0) )as cure,
            SUM(IF(p.pcr_result LIKE '%Detected%' AND p.discharge_result LIKE '%เสีย%' ,1,0) )as dead
            FROM person_pui p 
						LEFT JOIN hospital h ON p.admit_hosp = h.hoscode
            WHERE CONCAT(h.provcode,h.distcode) = '" . $ampurcodefull . "'
            ")->queryAll();
        }, 180, null);

        $days = 50;
        $sql_query = "SELECT ";
        $d = 0;
        for ($x = $days; $x >= 0; $x--) {
            $d++;
            $sql_query .= "DATE(SUBDATE(NOW(),INTERVAL ".($x)." day)) d".($d).",";
            $sql_query .= "YEAR(DATE(SUBDATE(NOW(),INTERVAL ".($x)." day))) d".($d)."_y,";
            $sql_query .= "MONTH(DATE(SUBDATE(NOW(),INTERVAL ".($x)." day))) -1 d".($d)."_m,";
            $sql_query .= "DAY(DATE(SUBDATE(NOW(),INTERVAL ".($x)." day))) d".($d)."_d,";
            $sql_query .= " (SELECT COUNT(person_pui.id) c FROM person_pui WHERE detect_date = DATE(SUBDATE(NOW(),INTERVAL ".($x)." day)) and person_pui.amphurcode='".$ampurcodefull."' GROUP BY detect_date) d".($d)."_case, ";
            $sql_query .= " (SELECT COUNT(IF(person_pui.pcr_result LIKE '%Detected%', person_pui.id,NULL)) c FROM person_pui WHERE detect_date = DATE(SUBDATE(NOW(),INTERVAL ".($x)." day)) and person_pui.amphurcode='".$ampurcodefull."' GROUP BY detect_date) d".($d)."_pos, ";

        }
        $sql_query .= " 0 AS dummy";

        $trend  = $connection->cache(function ($connection) use ($sql_query) {
            return $connection->createCommand($sql_query)->queryAll();
        }, 180, null);


        return $this->render('district', [
            'gis' => $gis,
            'sumary' => $sumary,
            'district' => $district,
            'calendar' => $calendar,
            'ampurcodefull' => $ampurcodefull,
            'trend' => $trend,
            'days' => $days,
            'covid' => $covid,
            'covid_dis' => $covid_dis,
        ]);
    }




    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionSubdistrict($tamboncodefull)
    {


        $model = new Locate();
        $condition2 = "TIMESTAMPDIFF(day,person.date_in,NOW()) <= 13 AND" ;
        if ($model->load(Yii::$app->request->post())) {
            $tamboncodefull = $model->subdistrict;
            if ($model->lost_days == '1') {
                $condition2 = "TIMESTAMPDIFF(day,person.date_in,NOW()) >= 14 AND"  ;
            } elseif ($model->lost_days == '2')  {
                $condition2 = "TIMESTAMPDIFF(day,person.date_in,NOW()) <= 13 AND"  ;
            } elseif ($model->lost_days == '3') {
                $condition2 = "";
            }   

        } else {
            $model->lost_days = '2';
            $model->district =  substr($tamboncodefull,0,4);
            $model->subdistrict =  $tamboncodefull;
        }



        $tambon         = ArrayHelper::map($this->getTambon($model->district), 'id', 'name');
        $village       = ArrayHelper::map($this->getVillage($model->subdistrict), 'id', 'name');



        $condition = "";

        
        if ($tamboncodefull == '0') {
            $condition = 'person.addr_tambon IS NULL';
            //$condition2 = 'person.addr_tambon IS NULL';
        } else {
            $condition = "person.addr_tambon = '" . $tamboncodefull . "'";
            //$condition2 = "person.addr_tambon = '" . $tamboncodefull . "'";
        }

        $connection = Yii::$app->db_covid;
        $gis = $connection->cache(function ($connection) use ($condition) {
            return $connection->createCommand("
            SELECT
            village.villagecodefull,
            IF(village.`villagename` IS NULL,'*รหัสเขตปกครองผิดพลาด',CONCAT(village.`villagename`, ' ม.', RIGHT(village.villagecodefull, 2),' ต.', a.tambonname)) AS vill_name,
            a.tambonname,
            COUNT(*) AS detected,
            CONCAT(SUBSTRING_INDEX(SUBSTRING_INDEX(geo_village.coordinates, ',', 2), ',', -1), ',', SUBSTRING_INDEX(geo_village.coordinates, ',', 1)) AS coordinates,
            SUM(IF(DATE(NOW()) > DATE_ADD(person.date_in,INTERVAL 13 day),'1','0')) AS pass14days
            FROM
            (SELECT * FROM person GROUP BY cid) person
            LEFT JOIN geo_village
            ON CONCAT(person.addr_tambon, person.addr_vill_no) = geo_village.villagecodefull
            LEFT JOIN village
            ON CONCAT(person.addr_tambon, person.addr_vill_no) = village.villagecodefull
            LEFT JOIN tambon a ON person.addr_tambon= a.tamboncodefull 
            WHERE " . $condition . "
            GROUP BY
            village.villagecodefull;
            ")->queryAll();
        }, 180, null);



        $sumary = $connection->cache(function ($connection) use ($condition) {
            return $connection->createCommand(
                "
            SELECT COUNT(*) AS total,
            COUNT(IF(person.date_in = DATE(NOW()) ,1,NULL)) AS today,
            COUNT(*) AS total_followed,
            SUM(IF(DATE(NOW()) > DATE_ADD(person.date_in,INTERVAL 13 day),'1','0')) AS pass14days
            FROM (SELECT * FROM person GROUP BY cid) person
            LEFT JOIN village
            ON CONCAT(person.addr_tambon, person.addr_vill_no) = village.villagecodefull
            LEFT JOIN tambon a ON person.addr_tambon = a.tamboncodefull
            WHERE " . $condition
            )->queryAll();
        }, 180, null);



        $district = $connection->cache(function ($connection) use ($condition) {
            return $connection->createCommand("
SELECT
village.villagecodefull,
    IF(village.villagename IS NULL, '*รหัสเขตปกครองผิดพลาด', village.villagename) villagename,
    IF(a.tambonname IS NULL, '*รหัสเขตปกครองผิดพลาด', a.tambonname) AS tambonname,
    COUNT( * ) AS detected,
    SUM(IF(risk_korea_worker = '1',1,0)) AS risk_korea_worker,
    SUM(IF(risk_cambodia_border = '1',1,0)) AS risk_cambodia_border,
    0 AS q_close_to_case,
    SUM(IF(risk_from_bangkok = '1',1,0)) AS risk_from_bangkok,
    SUM(IF(q_from_risk_country = '1',1,0)) AS q_from_risk_country
FROM
    (SELECT * FROM person GROUP BY cid) person
    LEFT JOIN village ON CONCAT( person.addr_tambon, person.addr_vill_no ) = village.villagecodefull
    LEFT JOIN tambon a ON person.addr_ampur = a.ampurcode AND person.addr_tambon = a.tamboncodefull
        WHERE " . $condition . "
GROUP BY
village.villagecodefull
    ")->queryAll();
        }, 180, null);




        $village_data = $connection->cache(function ($connection) use ($condition, $condition2) {
            return $connection->createCommand("
            SELECT
            person.*,
            village.villagecodefull,
                                    IF(village.`villagename` IS NULL,'รหัสผิดพลาด',CONCAT(village.`villagename`, ' ต.', a.tambonname)) AS vill_name,
            #a.ampurname,
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
(SELECT q_fever FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 1 day) LIMIT 1) d2_fever,
(SELECT q_sick_sign FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 1 day) LIMIT 1) d2_sick,
(SELECT temp FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 1 day) LIMIT 1) d2_temp,
(SELECT sick_sign FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 1 day) LIMIT 1) d2_sign,
(SELECT remark FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 1 day) LIMIT 1) d2_remark,

DATE_ADD(person.date_in,INTERVAL 2 day) d3,
IF(DATE(NOW()) > DATE_ADD(person.date_in,INTERVAL 1 day), '1', '0') d3_ended,
(SELECT q_fever FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 2 day) LIMIT 1) d3_fever,
(SELECT q_sick_sign FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 2 day) LIMIT 1) d3_sick,
(SELECT temp FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 2 day) LIMIT 1) d3_temp,
(SELECT sick_sign FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 2 day) LIMIT 1) d3_sign,
(SELECT remark FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 2 day) LIMIT 1) d3_remark,

DATE_ADD(person.date_in,INTERVAL 3 day) d4,
IF(DATE(NOW()) > DATE_ADD(person.date_in,INTERVAL 2 day), '1', '0') d4_ended,
(SELECT q_fever FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 3 day) LIMIT 1) d4_fever,
(SELECT q_sick_sign FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 3 day) LIMIT 1) d4_sick,
(SELECT temp FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 3 day) LIMIT 1) d4_temp,
(SELECT sick_sign FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 3 day) LIMIT 1) d4_sign,
(SELECT remark FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 3 day) LIMIT 1) d4_remark,

DATE_ADD(person.date_in,INTERVAL 4 day) d5,
IF(DATE(NOW()) > DATE_ADD(person.date_in,INTERVAL 3 day), '1', '0') d5_ended,
(SELECT q_fever FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 4 day) LIMIT 1) d5_fever,
(SELECT q_sick_sign FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 4 day) LIMIT 1) d5_sick,
(SELECT temp FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 4 day) LIMIT 1) d5_temp,
(SELECT sick_sign FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 4 day) LIMIT 1) d5_sign,
(SELECT remark FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 4 day) LIMIT 1) d5_remark,

     
DATE_ADD(person.date_in,INTERVAL 5 day) d6,
IF(DATE(NOW()) > DATE_ADD(person.date_in,INTERVAL 4 day), '1', '0') d6_ended,
(SELECT q_fever FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 5 day) LIMIT 1) d6_fever,
(SELECT q_sick_sign FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 5 day) LIMIT 1) d6_sick,
(SELECT temp FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 5 day) LIMIT 1) d6_temp,
(SELECT sick_sign FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 5 day) LIMIT 1) d6_ssign,
(SELECT remark FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 5 day) LIMIT 1) d6_remark,

DATE_ADD(person.date_in,INTERVAL 6 day) d7,
IF(DATE(NOW()) > DATE_ADD(person.date_in,INTERVAL 5 day), '1', '0') d7_ended,
(SELECT q_fever FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 6 day) LIMIT 1) d7_fever,
(SELECT q_sick_sign FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 6 day) LIMIT 1) d7_sick,
(SELECT temp FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 6 day) LIMIT 1) d7_temp,
(SELECT sick_sign FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 6 day) LIMIT 1) d7_sign,
(SELECT remark FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 6 day) LIMIT 1) d7_remark,

DATE_ADD(person.date_in,INTERVAL 7 day) d8,
IF(DATE(NOW()) > DATE_ADD(person.date_in,INTERVAL 6 day), '1', '0') d8_ended,
(SELECT q_fever FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 7 day) LIMIT 1) d8_fever,
(SELECT q_sick_sign FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 7 day) LIMIT 1) d8_sick,
(SELECT temp FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 7 day) LIMIT 1) d8_temp,
(SELECT sick_sign FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 7 day) LIMIT 1) d8_sign,
(SELECT remark FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 7 day) LIMIT 1) d8_remark,

DATE_ADD(person.date_in,INTERVAL 8 day) d9,
IF(DATE(NOW()) > DATE_ADD(person.date_in,INTERVAL 7 day), '1', '0') d9_ended,
(SELECT q_fever FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 8 day) LIMIT 1) d9_fever,
(SELECT q_sick_sign FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 8 day) LIMIT 1) d9_sick,
(SELECT temp FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 8 day) LIMIT 1) d9_temp,
(SELECT sick_sign FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 8 day) LIMIT 1) d9_sign,
(SELECT remark FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 8 day) LIMIT 1) d9_remark,

DATE_ADD(person.date_in,INTERVAL 9 day) d10,
IF(DATE(NOW()) > DATE_ADD(person.date_in,INTERVAL 8 day), '1', '0') d10_ended,
(SELECT q_fever FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 9 day) LIMIT 1) d10_fever,
(SELECT q_sick_sign FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 9 day) LIMIT 1) d10_sick,
(SELECT temp FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 9 day) LIMIT 1) d10_temp,
(SELECT sick_sign FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 9 day) LIMIT 1) d10_sign,
(SELECT remark FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 9 day) LIMIT 1) d10_remark,

DATE_ADD(person.date_in,INTERVAL 10 day) d11,
IF(DATE(NOW()) > DATE_ADD(person.date_in,INTERVAL 9 day), '1', '0') d11_ended,
(SELECT q_fever FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 10 day) LIMIT 1) d11_fever,
(SELECT q_sick_sign FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 10 day) LIMIT 1) d11_sick,
(SELECT temp FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 10 day) LIMIT 1) d11_temp,
(SELECT sick_sign FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 10 day) LIMIT 1) d11_sign,
(SELECT remark FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 10 day) LIMIT 1) d11_remark,

DATE_ADD(person.date_in,INTERVAL 11 day) d12,
IF(DATE(NOW()) > DATE_ADD(person.date_in,INTERVAL 10 day), '1', '0') d12_ended,
(SELECT q_fever FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 11 day) LIMIT 1) d12_fever,
(SELECT q_sick_sign FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 11 day) LIMIT 1) d12_sick,
(SELECT temp FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 11 day) LIMIT 1) d12_temp,
(SELECT sick_sign FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 11 day) LIMIT 1) d12_sign,
(SELECT remark FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 11 day) LIMIT 1) d12_remark,

DATE_ADD(person.date_in,INTERVAL 12 day) d13,
IF(DATE(NOW()) > DATE_ADD(person.date_in,INTERVAL 11 day), '1', '0') d13_ended,
(SELECT q_fever FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 12 day) LIMIT 1) d13_fever,
(SELECT q_sick_sign FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 12 day) LIMIT 1) d13_sick,
(SELECT temp FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 12 day) LIMIT 1) d13_temp,
(SELECT sick_sign FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 12 day) LIMIT 1) d13_sign,
(SELECT remark FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 12 day) LIMIT 1) d13_remark,

DATE_ADD(person.date_in,INTERVAL 13 day) d14,
IF(DATE(NOW()) > DATE_ADD(person.date_in,INTERVAL 12 day), '1', '0') d14_ended,
(SELECT q_fever FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 13 day) LIMIT 1) d14_fever,
(SELECT q_sick_sign FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 13 day) LIMIT 1) d14_sick,
(SELECT temp FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 13 day) LIMIT 1) d14_temp,
(SELECT sick_sign FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 13 day) LIMIT 1) d14_sign,
(SELECT remark FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 13 day) LIMIT 1) d14_remark,
            IF(DATE(NOW()) > DATE_ADD(person.date_in,INTERVAL 13 day),'1','0') AS pass14days,
            pui.id AS pui_id,
            pui.pcr_date,
            pui.discharge_result,
            pui.pcr_result
            FROM
            person
                        LEFT JOIN village
                        ON CONCAT(person.addr_tambon, person.addr_vill_no) = village.villagecodefull
                        LEFT JOIN tambon a ON person.addr_ampur = a.ampurcode AND person.addr_tambon = a.tamboncodefull
                LEFT JOIN province p ON person.move_province = p.changwatcode
                LEFT JOIN ampur am ON person.move_ampur = am.ampurcodefull
                LEFT JOIN tambon  t ON person.move_tambon = t.tamboncodefull
                LEFT JOIN person_pui pui ON person.cid = pui.cid

            WHERE 
            ".$condition2."
            " . $condition . "
            


            GROUP BY person.cid
            ORDER BY person.date_in DESC
            ")->queryAll();
        }, 180, null);


        $calendar = $connection->cache(function ($connection) use ($tamboncodefull) {
            return $connection->createCommand("
            SELECT
            person.date_in,
            CONCAT('[ new Date(', YEAR(date_in), ', ', MONTH(date_in) -1 ,', ', DAY(date_in), '), ', COUNT(person.id), ' ],') AS d,
            COUNT(person.id) AS total
            FROM
            (SELECT * FROM person GROUP BY cid) person
            WHERE
            date_in BETWEEN '2020-01-01' AND DATE(NOW())
            AND person.addr_tambon = '" . $tamboncodefull . "'
            GROUP BY
            person.date_in
            ORDER BY
            person.date_in
            ")->queryAll();
        }, 180, null);



        return $this->render('subdistrict', [
            'gis' => $gis,
            'sumary' => $sumary,
            'district' => $district,
            'village_data' => $village_data,
            'calendar' => $calendar,
            'village' => $village ,
            'tambon' => $tambon,
            'model' => $model
        ]);
    }



    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionVillage($villagecode)
    {
        $condition = "";
        if ($villagecode == '0') {
            $condition = 'village.villagecodefull IS NULL';
        } else {
            $condition = "CONCAT(person.addr_tambon, person.addr_vill_no) = '" . $villagecode . "'";
        }


        $connection = Yii::$app->db_covid;

        $person_group = $connection->cache(function ($connection) use ($condition) {
            return $connection->createCommand("
        SELECT
        village.villagecodefull,
            village.villagename,
            IF(a.tambonname IS NULL, '*รหัสเขตปกครองผิดพลาด', a.tambonname) AS tambonname,
            COUNT( * ) AS detected,
            SUM(IF(risk_korea_worker = '1',1,0)) AS risk_korea_worker,
            SUM(IF(risk_cambodia_border = '1',1,0)) AS risk_cambodia_border,
            0 AS q_close_to_case,
            SUM(IF(risk_from_bangkok = '1',1,0)) AS risk_from_bangkok,
            SUM(IF(q_from_risk_country = '1',1,0)) AS q_from_risk_country
        FROM
            (SELECT * FROM person GROUP BY cid) person
            LEFT JOIN village ON CONCAT( person.addr_tambon, person.addr_vill_no ) = village.villagecodefull
            LEFT JOIN tambon a ON person.addr_ampur = a.ampurcode AND person.addr_tambon = a.tamboncodefull
                WHERE " . $condition . "
        GROUP BY
        village.villagecodefull
            ")->queryAll();
        }, 180, null);



        $village_data = $connection->createCommand("
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
(SELECT q_fever FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 1 day) LIMIT 1) d2_fever,
(SELECT q_sick_sign FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 1 day) LIMIT 1) d2_sick,
(SELECT temp FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 1 day) LIMIT 1) d2_temp,
(SELECT sick_sign FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 1 day) LIMIT 1) d2_sign,
(SELECT remark FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 1 day) LIMIT 1) d2_remark,

DATE_ADD(person.date_in,INTERVAL 2 day) d3,
IF(DATE(NOW()) > DATE_ADD(person.date_in,INTERVAL 1 day), '1', '0') d3_ended,
(SELECT q_fever FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 2 day) LIMIT 1) d3_fever,
(SELECT q_sick_sign FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 2 day) LIMIT 1) d3_sick,
(SELECT temp FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 2 day) LIMIT 1) d3_temp,
(SELECT sick_sign FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 2 day) LIMIT 1) d3_sign,
(SELECT remark FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 2 day) LIMIT 1) d3_remark,

DATE_ADD(person.date_in,INTERVAL 3 day) d4,
IF(DATE(NOW()) > DATE_ADD(person.date_in,INTERVAL 2 day), '1', '0') d4_ended,
(SELECT q_fever FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 3 day) LIMIT 1) d4_fever,
(SELECT q_sick_sign FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 3 day) LIMIT 1) d4_sick,
(SELECT temp FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 3 day) LIMIT 1) d4_temp,
(SELECT sick_sign FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 3 day) LIMIT 1) d4_sign,
(SELECT remark FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 3 day) LIMIT 1) d4_remark,

DATE_ADD(person.date_in,INTERVAL 4 day) d5,
IF(DATE(NOW()) > DATE_ADD(person.date_in,INTERVAL 3 day), '1', '0') d5_ended,
(SELECT q_fever FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 4 day) LIMIT 1) d5_fever,
(SELECT q_sick_sign FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 4 day) LIMIT 1) d5_sick,
(SELECT temp FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 4 day) LIMIT 1) d5_temp,
(SELECT sick_sign FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 4 day) LIMIT 1) d5_sign,
(SELECT remark FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 4 day) LIMIT 1) d5_remark,

     
DATE_ADD(person.date_in,INTERVAL 5 day) d6,
IF(DATE(NOW()) > DATE_ADD(person.date_in,INTERVAL 4 day), '1', '0') d6_ended,
(SELECT q_fever FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 5 day) LIMIT 1) d6_fever,
(SELECT q_sick_sign FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 5 day) LIMIT 1) d6_sick,
(SELECT temp FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 5 day) LIMIT 1) d6_temp,
(SELECT sick_sign FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 5 day) LIMIT 1) d6_ssign,
(SELECT remark FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 5 day) LIMIT 1) d6_remark,

DATE_ADD(person.date_in,INTERVAL 6 day) d7,
IF(DATE(NOW()) > DATE_ADD(person.date_in,INTERVAL 5 day), '1', '0') d7_ended,
(SELECT q_fever FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 6 day) LIMIT 1) d7_fever,
(SELECT q_sick_sign FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 6 day) LIMIT 1) d7_sick,
(SELECT temp FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 6 day) LIMIT 1) d7_temp,
(SELECT sick_sign FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 6 day) LIMIT 1) d7_sign,
(SELECT remark FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 6 day) LIMIT 1) d7_remark,

DATE_ADD(person.date_in,INTERVAL 7 day) d8,
IF(DATE(NOW()) > DATE_ADD(person.date_in,INTERVAL 6 day), '1', '0') d8_ended,
(SELECT q_fever FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 7 day) LIMIT 1) d8_fever,
(SELECT q_sick_sign FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 7 day) LIMIT 1) d8_sick,
(SELECT temp FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 7 day) LIMIT 1) d8_temp,
(SELECT sick_sign FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 7 day) LIMIT 1) d8_sign,
(SELECT remark FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 7 day) LIMIT 1) d8_remark,

DATE_ADD(person.date_in,INTERVAL 8 day) d9,
IF(DATE(NOW()) > DATE_ADD(person.date_in,INTERVAL 7 day), '1', '0') d9_ended,
(SELECT q_fever FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 8 day) LIMIT 1) d9_fever,
(SELECT q_sick_sign FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 8 day) LIMIT 1) d9_sick,
(SELECT temp FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 8 day) LIMIT 1) d9_temp,
(SELECT sick_sign FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 8 day) LIMIT 1) d9_sign,
(SELECT remark FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 8 day) LIMIT 1) d9_remark,

DATE_ADD(person.date_in,INTERVAL 9 day) d10,
IF(DATE(NOW()) > DATE_ADD(person.date_in,INTERVAL 8 day), '1', '0') d10_ended,
(SELECT q_fever FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 9 day) LIMIT 1) d10_fever,
(SELECT q_sick_sign FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 9 day) LIMIT 1) d10_sick,
(SELECT temp FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 9 day) LIMIT 1) d10_temp,
(SELECT sick_sign FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 9 day) LIMIT 1) d10_sign,
(SELECT remark FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 9 day) LIMIT 1) d10_remark,

DATE_ADD(person.date_in,INTERVAL 10 day) d11,
IF(DATE(NOW()) > DATE_ADD(person.date_in,INTERVAL 9 day), '1', '0') d11_ended,
(SELECT q_fever FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 10 day) LIMIT 1) d11_fever,
(SELECT q_sick_sign FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 10 day) LIMIT 1) d11_sick,
(SELECT temp FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 10 day) LIMIT 1) d11_temp,
(SELECT sick_sign FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 10 day) LIMIT 1) d11_sign,
(SELECT remark FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 10 day) LIMIT 1) d11_remark,

DATE_ADD(person.date_in,INTERVAL 11 day) d12,
IF(DATE(NOW()) > DATE_ADD(person.date_in,INTERVAL 10 day), '1', '0') d12_ended,
(SELECT q_fever FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 11 day) LIMIT 1) d12_fever,
(SELECT q_sick_sign FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 11 day) LIMIT 1) d12_sick,
(SELECT temp FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 11 day) LIMIT 1) d12_temp,
(SELECT sick_sign FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 11 day) LIMIT 1) d12_sign,
(SELECT remark FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 11 day) LIMIT 1) d12_remark,

DATE_ADD(person.date_in,INTERVAL 12 day) d13,
IF(DATE(NOW()) > DATE_ADD(person.date_in,INTERVAL 11 day), '1', '0') d13_ended,
(SELECT q_fever FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 12 day) LIMIT 1) d13_fever,
(SELECT q_sick_sign FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 12 day) LIMIT 1) d13_sick,
(SELECT temp FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 12 day) LIMIT 1) d13_temp,
(SELECT sick_sign FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 12 day) LIMIT 1) d13_sign,
(SELECT remark FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 12 day) LIMIT 1) d13_remark,

DATE_ADD(person.date_in,INTERVAL 13 day) d14,
IF(DATE(NOW()) > DATE_ADD(person.date_in,INTERVAL 12 day), '1', '0') d14_ended,
(SELECT q_fever FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 13 day) LIMIT 1) d14_fever,
(SELECT q_sick_sign FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 13 day) LIMIT 1) d14_sick,
(SELECT temp FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 13 day) LIMIT 1) d14_temp,
(SELECT sick_sign FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 13 day) LIMIT 1) d14_sign,
(SELECT remark FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 13 day) LIMIT 1) d14_remark,
pui.id AS pui_id,
pui.pcr_date,
pui.discharge_result,
pui.pcr_result,

            IF(DATE(NOW()) > DATE_ADD(person.date_in,INTERVAL 13 day),'1','0') AS pass14days
            FROM
            person
                        LEFT JOIN ampur a ON person.addr_ampur = a.ampurcodefull
                        LEFT JOIN tambon  ON person.addr_tambon = tambon.tamboncodefull
                        LEFT JOIN village v ON CONCAT(person.addr_tambon, person.addr_vill_no) = v.villagecodefull
                LEFT JOIN province p ON person.move_province = p.changwatcode
                LEFT JOIN ampur am ON person.move_ampur = am.ampurcodefull
                LEFT JOIN tambon  t ON person.move_tambon = t.tamboncodefull
                LEFT JOIN person_pui pui ON person.cid = pui.cid
            WHERE " . $condition . "
            GROUP BY cid
            ORDER BY person.date_in DESC
            ")->queryAll();




        $sumary = $connection->cache(function ($connection) use ($condition) {
            return $connection->createCommand(
                "
            SELECT COUNT(*) AS total,
            COUNT(IF(person.date_in = DATE(NOW()) ,1,NULL)) AS today,
            COUNT(*) AS total_followed,
            SUM(IF(DATE(NOW()) > DATE_ADD(person.date_in,INTERVAL 13 day),'1','0')) AS pass14days
            FROM (SELECT * FROM person GROUP BY cid) person
            LEFT JOIN village
            ON CONCAT(person.addr_tambon, person.addr_vill_no) = village.villagecodefull
            LEFT JOIN ampur a ON person.addr_ampur = a.ampurcodefull
            WHERE " . $condition
            )->queryAll();
        }, 180, null);


        $calendar = $connection->cache(function ($connection) use ($villagecode) {
            return $connection->createCommand("
            SELECT
            person.date_in,
            CONCAT('[ new Date(', YEAR(date_in), ', ', MONTH(date_in) -1 ,', ', DAY(date_in), '), ', COUNT(person.id), ' ],') AS d,
            COUNT(person.id) AS total
            FROM
            (SELECT * FROM person GROUP BY cid) person
            WHERE
            date_in BETWEEN '2020-01-01' AND DATE(NOW())
            AND CONCAT(person.addr_tambon, person.addr_vill_no) = '" . $villagecode . "'
            GROUP BY
            person.date_in
            ORDER BY
            person.date_in
            ")->queryAll();
        }, 180, null);





        return $this->render('village', [
            'village_data' => $village_data,
            'sumary' => $sumary,
            'calendar' => $calendar,
            'person_group' => $person_group,
        ]);
    }





    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionSickCase()
    {
        $villagecode = '00';
        $condition = "";
        if ($villagecode == '0') {
            $condition = 'geo_village.villagecodefull IS NULL';
        } else {
            $condition = "CONCAT(person.addr_tambon, person.addr_vill_no) = '" . $villagecode . "'";
        }


        $connection = Yii::$app->db_covid;

        // $person_group = $connection->cache(function ($connection) use ($condition) {
        //     return $connection->createCommand("
        // SELECT
        //     geo_village.villagecodefull,
        //     geo_village.villagename,
        //     IF(a.tambonname IS NULL, '*รหัสเขตปกครองผิดพลาด', a.tambonname) AS tambonname,
        //     COUNT( * ) AS detected,
        //     SUM(IF(risk_korea_worker = '1',1,0)) AS risk_korea_worker,
        //     SUM(IF(risk_cambodia_border = '1',1,0)) AS risk_cambodia_border,
        //     0 AS q_close_to_case,
        //     SUM(IF(risk_from_bangkok = '1',1,0)) AS risk_from_bangkok,
        //     SUM(IF(q_from_risk_country = '1',1,0)) AS q_from_risk_country
        // FROM
        //     (SELECT * FROM person GROUP BY cid) person
        //     LEFT JOIN geo_village ON CONCAT( person.addr_tambon, person.addr_vill_no ) = geo_village.villagecodefull
        //     LEFT JOIN tambon a ON person.addr_ampur = a.ampurcode AND person.addr_tambon = a.tamboncodefull
        //         WHERE " . $condition . "
        // GROUP BY
        //     geo_village.villagecodefull
        //     ")->queryAll();
        // }, 180, null);



        //$dependency = new \yii\caching\DbDependency(['sql' => 'SELECT id FROM fever_update']);

        $village_data = $connection->cache(function ($connection) use ($villagecode) {
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
(SELECT q_fever FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 1 day) LIMIT 1) d2_fever,
(SELECT q_sick_sign FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 1 day) LIMIT 1) d2_sick,
(SELECT temp FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 1 day) LIMIT 1) d2_temp,
(SELECT sick_sign FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 1 day) LIMIT 1) d2_sign,
(SELECT remark FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 1 day) LIMIT 1) d2_remark,

DATE_ADD(person.date_in,INTERVAL 2 day) d3,
IF(DATE(NOW()) > DATE_ADD(person.date_in,INTERVAL 1 day), '1', '0') d3_ended,
(SELECT q_fever FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 2 day) LIMIT 1) d3_fever,
(SELECT q_sick_sign FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 2 day) LIMIT 1) d3_sick,
(SELECT temp FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 2 day) LIMIT 1) d3_temp,
(SELECT sick_sign FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 2 day) LIMIT 1) d3_sign,
(SELECT remark FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 2 day) LIMIT 1) d3_remark,

DATE_ADD(person.date_in,INTERVAL 3 day) d4,
IF(DATE(NOW()) > DATE_ADD(person.date_in,INTERVAL 2 day), '1', '0') d4_ended,
(SELECT q_fever FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 3 day) LIMIT 1) d4_fever,
(SELECT q_sick_sign FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 3 day) LIMIT 1) d4_sick,
(SELECT temp FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 3 day) LIMIT 1) d4_temp,
(SELECT sick_sign FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 3 day) LIMIT 1) d4_sign,
(SELECT remark FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 3 day) LIMIT 1) d4_remark,

DATE_ADD(person.date_in,INTERVAL 4 day) d5,
IF(DATE(NOW()) > DATE_ADD(person.date_in,INTERVAL 3 day), '1', '0') d5_ended,
(SELECT q_fever FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 4 day) LIMIT 1) d5_fever,
(SELECT q_sick_sign FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 4 day) LIMIT 1) d5_sick,
(SELECT temp FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 4 day) LIMIT 1) d5_temp,
(SELECT sick_sign FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 4 day) LIMIT 1) d5_sign,
(SELECT remark FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 4 day) LIMIT 1) d5_remark,

     
DATE_ADD(person.date_in,INTERVAL 5 day) d6,
IF(DATE(NOW()) > DATE_ADD(person.date_in,INTERVAL 4 day), '1', '0') d6_ended,
(SELECT q_fever FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 5 day) LIMIT 1) d6_fever,
(SELECT q_sick_sign FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 5 day) LIMIT 1) d6_sick,
(SELECT temp FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 5 day) LIMIT 1) d6_temp,
(SELECT sick_sign FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 5 day) LIMIT 1) d6_ssign,
(SELECT remark FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 5 day) LIMIT 1) d6_remark,

DATE_ADD(person.date_in,INTERVAL 6 day) d7,
IF(DATE(NOW()) > DATE_ADD(person.date_in,INTERVAL 5 day), '1', '0') d7_ended,
(SELECT q_fever FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 6 day) LIMIT 1) d7_fever,
(SELECT q_sick_sign FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 6 day) LIMIT 1) d7_sick,
(SELECT temp FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 6 day) LIMIT 1) d7_temp,
(SELECT sick_sign FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 6 day) LIMIT 1) d7_sign,
(SELECT remark FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 6 day) LIMIT 1) d7_remark,

DATE_ADD(person.date_in,INTERVAL 7 day) d8,
IF(DATE(NOW()) > DATE_ADD(person.date_in,INTERVAL 6 day), '1', '0') d8_ended,
(SELECT q_fever FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 7 day) LIMIT 1) d8_fever,
(SELECT q_sick_sign FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 7 day) LIMIT 1) d8_sick,
(SELECT temp FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 7 day) LIMIT 1) d8_temp,
(SELECT sick_sign FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 7 day) LIMIT 1) d8_sign,
(SELECT remark FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 7 day) LIMIT 1) d8_remark,

DATE_ADD(person.date_in,INTERVAL 8 day) d9,
IF(DATE(NOW()) > DATE_ADD(person.date_in,INTERVAL 7 day), '1', '0') d9_ended,
(SELECT q_fever FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 8 day) LIMIT 1) d9_fever,
(SELECT q_sick_sign FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 8 day) LIMIT 1) d9_sick,
(SELECT temp FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 8 day) LIMIT 1) d9_temp,
(SELECT sick_sign FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 8 day) LIMIT 1) d9_sign,
(SELECT remark FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 8 day) LIMIT 1) d9_remark,

DATE_ADD(person.date_in,INTERVAL 9 day) d10,
IF(DATE(NOW()) > DATE_ADD(person.date_in,INTERVAL 8 day), '1', '0') d10_ended,
(SELECT q_fever FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 9 day) LIMIT 1) d10_fever,
(SELECT q_sick_sign FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 9 day) LIMIT 1) d10_sick,
(SELECT temp FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 9 day) LIMIT 1) d10_temp,
(SELECT sick_sign FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 9 day) LIMIT 1) d10_sign,
(SELECT remark FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 9 day) LIMIT 1) d10_remark,

DATE_ADD(person.date_in,INTERVAL 10 day) d11,
IF(DATE(NOW()) > DATE_ADD(person.date_in,INTERVAL 9 day), '1', '0') d11_ended,
(SELECT q_fever FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 10 day) LIMIT 1) d11_fever,
(SELECT q_sick_sign FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 10 day) LIMIT 1) d11_sick,
(SELECT temp FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 10 day) LIMIT 1) d11_temp,
(SELECT sick_sign FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 10 day) LIMIT 1) d11_sign,
(SELECT remark FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 10 day) LIMIT 1) d11_remark,

DATE_ADD(person.date_in,INTERVAL 11 day) d12,
IF(DATE(NOW()) > DATE_ADD(person.date_in,INTERVAL 10 day), '1', '0') d12_ended,
(SELECT q_fever FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 11 day) LIMIT 1) d12_fever,
(SELECT q_sick_sign FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 11 day) LIMIT 1) d12_sick,
(SELECT temp FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 11 day) LIMIT 1) d12_temp,
(SELECT sick_sign FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 11 day) LIMIT 1) d12_sign,
(SELECT remark FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 11 day) LIMIT 1) d12_remark,

DATE_ADD(person.date_in,INTERVAL 12 day) d13,
IF(DATE(NOW()) > DATE_ADD(person.date_in,INTERVAL 11 day), '1', '0') d13_ended,
(SELECT q_fever FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 12 day) LIMIT 1) d13_fever,
(SELECT q_sick_sign FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 12 day) LIMIT 1) d13_sick,
(SELECT temp FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 12 day) LIMIT 1) d13_temp,
(SELECT sick_sign FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 12 day) LIMIT 1) d13_sign,
(SELECT remark FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 12 day) LIMIT 1) d13_remark,

DATE_ADD(person.date_in,INTERVAL 13 day) d14,
IF(DATE(NOW()) > DATE_ADD(person.date_in,INTERVAL 12 day), '1', '0') d14_ended,
(SELECT q_fever FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 13 day) LIMIT 1) d14_fever,
(SELECT q_sick_sign FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 13 day) LIMIT 1) d14_sick,
(SELECT temp FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 13 day) LIMIT 1) d14_temp,
(SELECT sick_sign FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 13 day) LIMIT 1) d14_sign,
(SELECT remark FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 13 day) LIMIT 1) d14_remark,

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
                        LEFT JOIN ampur a ON person.addr_ampur = a.ampurcodefull
                        LEFT JOIN tambon  ON person.addr_tambon = tambon.tamboncodefull
                        LEFT JOIN village v ON CONCAT(person.addr_tambon, person.addr_vill_no) = v.villagecodefull
                        LEFT JOIN province p ON person.move_province = p.changwatcode
                LEFT JOIN ampur am ON person.move_ampur = am.ampurcodefull
                LEFT JOIN tambon  t ON person.move_tambon = t.tamboncodefull
                LEFT JOIN person_pui pui ON person.cid = pui.cid

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
            WHERE l_follow.temp >= 37.5 OR (person.temp >= 37.5 AND l_follow.temp IS NULL)
            AND person.status NOT IN ('0', '1')
            GROUP BY person.cid
            ORDER BY person.addr_ampur
            ")->queryAll();
        }, 180, null);




        // $sumary = $connection->cache(function ($connection) use ($condition) {
        //     return $connection->createCommand(
        //         "
        //     SELECT COUNT(*) AS total,
        //     COUNT(IF(person.date_in = DATE(NOW()) ,1,NULL)) AS today,
        //     COUNT(*) AS total_followed,
        //     SUM(IF(DATE(NOW()) > DATE_ADD(person.date_in,INTERVAL 13 day),'1','0')) AS pass14days
        //     FROM (SELECT * FROM person GROUP BY cid) person
        //     LEFT JOIN geo_village
        //     ON CONCAT(person.addr_tambon, person.addr_vill_no) = geo_village.villagecodefull
        //     LEFT JOIN ampur a ON person.addr_ampur = a.ampurcodefull
        //     WHERE " . $condition
        //     )->queryAll();
        // }, 180, null);




        // $calendar = $connection->cache(function ($connection) use ($villagecode) {
        //     return $connection->createCommand("
        //     SELECT
        //     person.date_in,
        //     CONCAT('[ new Date(', YEAR(date_in), ', ', MONTH(date_in) -1 ,', ', DAY(date_in), '), ', COUNT(person.id), ' ],') AS d,
        //     COUNT(person.id) AS total
        //     FROM
        //     (SELECT * FROM person GROUP BY cid) person
        //     WHERE
        //     date_in BETWEEN '2020-01-01' AND DATE(NOW())
        //     AND CONCAT(person.addr_tambon, person.addr_vill_no) = '" . $villagecode . "'
        //     GROUP BY
        //     person.date_in
        //     ORDER BY
        //     person.date_in
        //     ")->queryAll();
        // }, 180, null);


        $sumary = [];
        $calendar = [];
        $person_group = [];


        return $this->render('sick-case', [
            'village_data' => $village_data,
            'sumary' => $sumary,
            'calendar' => $calendar,
            'person_group' => $person_group,
        ]);
    }


    public function actionQuarantine()
    {
        $connection = Yii::$app->db_covid;
        $local = $connection->cache(function ($connection) {
            return $connection->createCommand("
            SELECT
            local_quarantine.id,
            local_quarantine.local_name,
            local_quarantine.tambon,
            local_quarantine.amphur,
            local_quarantine.remark,
            COUNT(DISTINCT person.cid) c
            FROM
            local_quarantine
            LEFT JOIN person
            ON local_quarantine.local_name = person.remark
            GROUP BY
            covid19.local_quarantine.id
            ORDER BY
            local_quarantine.amphur ASC
        ")->queryAll();
        }, 180, null);

        return $this->render('quarantine', [
            'local' => $local
        ]);
    }


    public function actionLostInput()
    {
        $model = new Locate();
        $model->load(Yii::$app->request->post());

        $condition = "";
        if ($model->village != '') {
            $condition = "person.addr_tambon = '" . $model->subdistrict . "' AND person.addr_vill_no = RIGHT('" . $model->village . "',2)";
        } elseif ($model->subdistrict != '') {
            $condition = "person.addr_tambon = '" . $model->subdistrict . "'";
        } elseif ($model->district != '') {
            $condition = "person.addr_ampur = '" . $model->district . "'";
        } else {
            $condition = "person.addr_ampur = '2701'";
            $person_data = [];
        }

        if ($model->lost_days == '') {
            $model->lost_days = 1;
        }
        $days = $model->lost_days;



        $tambon         = ArrayHelper::map($this->getTambon($model->district), 'id', 'name');
        $village       = ArrayHelper::map($this->getVillage($model->subdistrict), 'id', 'name');






        $connection = Yii::$app->db_covid;

        if ($model->district != '') {
            $person_data = $connection->cache(function ($connection) use ($condition, $days) {
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
            (SELECT q_fever FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 1 day) LIMIT 1) d2_fever,
            (SELECT q_sick_sign FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 1 day) LIMIT 1) d2_sick,
            (SELECT temp FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 1 day) LIMIT 1) d2_temp,
            (SELECT sick_sign FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 1 day) LIMIT 1) d2_sign,
            (SELECT remark FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 1 day) LIMIT 1) d2_remark,

            DATE_ADD(person.date_in,INTERVAL 2 day) d3,
            IF(DATE(NOW()) > DATE_ADD(person.date_in,INTERVAL 1 day), '1', '0') d3_ended,
            (SELECT q_fever FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 2 day) LIMIT 1) d3_fever,
            (SELECT q_sick_sign FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 2 day) LIMIT 1) d3_sick,
            (SELECT temp FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 2 day) LIMIT 1) d3_temp,
            (SELECT sick_sign FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 2 day) LIMIT 1) d3_sign,
            (SELECT remark FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 2 day) LIMIT 1) d3_remark,

            DATE_ADD(person.date_in,INTERVAL 3 day) d4,
            IF(DATE(NOW()) > DATE_ADD(person.date_in,INTERVAL 2 day), '1', '0') d4_ended,
            (SELECT q_fever FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 3 day) LIMIT 1) d4_fever,
            (SELECT q_sick_sign FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 3 day) LIMIT 1) d4_sick,
            (SELECT temp FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 3 day) LIMIT 1) d4_temp,
            (SELECT sick_sign FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 3 day) LIMIT 1) d4_sign,
            (SELECT remark FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 3 day) LIMIT 1) d4_remark,

            DATE_ADD(person.date_in,INTERVAL 4 day) d5,
            IF(DATE(NOW()) > DATE_ADD(person.date_in,INTERVAL 3 day), '1', '0') d5_ended,
            (SELECT q_fever FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 4 day) LIMIT 1) d5_fever,
            (SELECT q_sick_sign FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 4 day) LIMIT 1) d5_sick,
            (SELECT temp FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 4 day) LIMIT 1) d5_temp,
            (SELECT sick_sign FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 4 day) LIMIT 1) d5_sign,
            (SELECT remark FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 4 day) LIMIT 1) d5_remark,

                
            DATE_ADD(person.date_in,INTERVAL 5 day) d6,
            IF(DATE(NOW()) > DATE_ADD(person.date_in,INTERVAL 4 day), '1', '0') d6_ended,
            (SELECT q_fever FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 5 day) LIMIT 1) d6_fever,
            (SELECT q_sick_sign FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 5 day) LIMIT 1) d6_sick,
            (SELECT temp FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 5 day) LIMIT 1) d6_temp,
            (SELECT sick_sign FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 5 day) LIMIT 1) d6_ssign,
            (SELECT remark FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 5 day) LIMIT 1) d6_remark,

            DATE_ADD(person.date_in,INTERVAL 6 day) d7,
            IF(DATE(NOW()) > DATE_ADD(person.date_in,INTERVAL 5 day), '1', '0') d7_ended,
            (SELECT q_fever FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 6 day) LIMIT 1) d7_fever,
            (SELECT q_sick_sign FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 6 day) LIMIT 1) d7_sick,
            (SELECT temp FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 6 day) LIMIT 1) d7_temp,
            (SELECT sick_sign FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 6 day) LIMIT 1) d7_sign,
            (SELECT remark FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 6 day) LIMIT 1) d7_remark,

            DATE_ADD(person.date_in,INTERVAL 7 day) d8,
            IF(DATE(NOW()) > DATE_ADD(person.date_in,INTERVAL 6 day), '1', '0') d8_ended,
            (SELECT q_fever FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 7 day) LIMIT 1) d8_fever,
            (SELECT q_sick_sign FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 7 day) LIMIT 1) d8_sick,
            (SELECT temp FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 7 day) LIMIT 1) d8_temp,
            (SELECT sick_sign FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 7 day) LIMIT 1) d8_sign,
            (SELECT remark FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 7 day) LIMIT 1) d8_remark,

            DATE_ADD(person.date_in,INTERVAL 8 day) d9,
            IF(DATE(NOW()) > DATE_ADD(person.date_in,INTERVAL 7 day), '1', '0') d9_ended,
            (SELECT q_fever FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 8 day) LIMIT 1) d9_fever,
            (SELECT q_sick_sign FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 8 day) LIMIT 1) d9_sick,
            (SELECT temp FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 8 day) LIMIT 1) d9_temp,
            (SELECT sick_sign FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 8 day) LIMIT 1) d9_sign,
            (SELECT remark FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 8 day) LIMIT 1) d9_remark,

            DATE_ADD(person.date_in,INTERVAL 9 day) d10,
            IF(DATE(NOW()) > DATE_ADD(person.date_in,INTERVAL 8 day), '1', '0') d10_ended,
            (SELECT q_fever FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 9 day) LIMIT 1) d10_fever,
            (SELECT q_sick_sign FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 9 day) LIMIT 1) d10_sick,
            (SELECT temp FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 9 day) LIMIT 1) d10_temp,
            (SELECT sick_sign FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 9 day) LIMIT 1) d10_sign,
            (SELECT remark FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 9 day) LIMIT 1) d10_remark,

            DATE_ADD(person.date_in,INTERVAL 10 day) d11,
            IF(DATE(NOW()) > DATE_ADD(person.date_in,INTERVAL 9 day), '1', '0') d11_ended,
            (SELECT q_fever FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 10 day) LIMIT 1) d11_fever,
            (SELECT q_sick_sign FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 10 day) LIMIT 1) d11_sick,
            (SELECT temp FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 10 day) LIMIT 1) d11_temp,
            (SELECT sick_sign FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 10 day) LIMIT 1) d11_sign,
            (SELECT remark FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 10 day) LIMIT 1) d11_remark,

            DATE_ADD(person.date_in,INTERVAL 11 day) d12,
            IF(DATE(NOW()) > DATE_ADD(person.date_in,INTERVAL 10 day), '1', '0') d12_ended,
            (SELECT q_fever FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 11 day) LIMIT 1) d12_fever,
            (SELECT q_sick_sign FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 11 day) LIMIT 1) d12_sick,
            (SELECT temp FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 11 day) LIMIT 1) d12_temp,
            (SELECT sick_sign FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 11 day) LIMIT 1) d12_sign,
            (SELECT remark FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 11 day) LIMIT 1) d12_remark,

            DATE_ADD(person.date_in,INTERVAL 12 day) d13,
            IF(DATE(NOW()) > DATE_ADD(person.date_in,INTERVAL 11 day), '1', '0') d13_ended,
            (SELECT q_fever FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 12 day) LIMIT 1) d13_fever,
            (SELECT q_sick_sign FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 12 day) LIMIT 1) d13_sick,
            (SELECT temp FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 12 day) LIMIT 1) d13_temp,
            (SELECT sick_sign FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 12 day) LIMIT 1) d13_sign,
            (SELECT remark FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 12 day) LIMIT 1) d13_remark,

            DATE_ADD(person.date_in,INTERVAL 13 day) d14,
            IF(DATE(NOW()) > DATE_ADD(person.date_in,INTERVAL 12 day), '1', '0') d14_ended,
            (SELECT q_fever FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 13 day) LIMIT 1) d14_fever,
            (SELECT q_sick_sign FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 13 day) LIMIT 1) d14_sick,
            (SELECT temp FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 13 day) LIMIT 1) d14_temp,
            (SELECT sick_sign FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 13 day) LIMIT 1) d14_sign,
            (SELECT remark FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 13 day) LIMIT 1) d14_remark,
            IF(DATE(NOW()) > DATE_ADD(person.date_in,INTERVAL 13 day),'1','0') AS pass14days
            FROM
            person
                        LEFT JOIN ampur a ON person.addr_ampur = a.ampurcodefull
                        LEFT JOIN tambon  ON person.addr_tambon = tambon.tamboncodefull
                        LEFT JOIN village v ON CONCAT(person.addr_tambon, person.addr_vill_no) = v.villagecodefull
                LEFT JOIN province p ON person.move_province = p.changwatcode
                LEFT JOIN ampur am ON person.move_ampur = am.ampurcodefull
                LEFT JOIN tambon  t ON person.move_tambon = t.tamboncodefull
           
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
											
											
					 
            WHERE
            ((TIMESTAMPDIFF(day,l_follow.report_date,NOW()) > " . $days . " OR (person.date_in < ADDDATE(person.date_in,INTERVAL 13 day) AND TIMESTAMPDIFF(day,person.date_in,NOW()) > ".$days." AND l_follow.report_date IS NULL)))
            AND ((l_follow.report_date < ADDDATE(person.date_in,INTERVAL 13 day ) OR (person.date_in < ADDDATE(person.date_in,INTERVAL 13 day) AND TIMESTAMPDIFF(day,person.date_in,NOW()) > ".$days." AND l_follow.report_date IS NULL)))
            AND " . $condition . "
            AND (person.`status` IS NULL OR person.`status` NOT IN ('0', '1', '5') )

            GROUP BY cid
            ORDER BY person.date_in DESC

            ")->queryAll();
            }, 10, null);
        }

        return $this->render('lost-input', [
            'model' => $model,
            'person_data' => $person_data,
            'tambon' => $tambon,
            'village' => $village,
        ]);
    }


    public function actionPassdays()
    {
        $model = new Locate();
        $model->load(Yii::$app->request->post());

        $condition = "";




        $condition2 = "TIMESTAMPDIFF(day,person.date_in,NOW()) <= 13 AND" ;
        if ($model->load(Yii::$app->request->post())) {
            if ($model->village != '') {
                $condition = "person.addr_tambon = '" . $model->subdistrict . "' AND person.addr_vill_no = RIGHT('" . $model->village . "',2)";
            } elseif ($model->subdistrict != '') {
                $condition = "person.addr_tambon = '" . $model->subdistrict . "'";
            } elseif ($model->district != '') {
                $condition = "person.addr_ampur = '" . $model->district . "'";
            } else {
                $condition = "person.addr_ampur = '2701'";
                
            }


            if ($model->lost_days == '1') {
                $condition2 = "TIMESTAMPDIFF(day,person.date_in,NOW()) >= 14 AND"  ;
            } elseif ($model->lost_days == '2')  {
                $condition2 = "TIMESTAMPDIFF(day,person.date_in,NOW()) <= 13 AND"  ;
            } elseif ($model->lost_days == '3') {
                $condition2 = "";
            }   

        } else {
            $model->lost_days = '2';
            $person_data = [];
        }



        $tambon         = ArrayHelper::map($this->getTambon($model->district), 'id', 'name');
        $village       = ArrayHelper::map($this->getVillage($model->subdistrict), 'id', 'name');




        $connection = Yii::$app->db_covid;

        if ($model->district != '') {
            $person_data = $connection->cache(function ($connection) use ($condition, $condition2) {
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
            (SELECT q_fever FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 1 day) LIMIT 1) d2_fever,
            (SELECT q_sick_sign FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 1 day) LIMIT 1) d2_sick,
            (SELECT temp FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 1 day) LIMIT 1) d2_temp,
            (SELECT sick_sign FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 1 day) LIMIT 1) d2_sign,
            (SELECT remark FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 1 day) LIMIT 1) d2_remark,

            DATE_ADD(person.date_in,INTERVAL 2 day) d3,
            IF(DATE(NOW()) > DATE_ADD(person.date_in,INTERVAL 1 day), '1', '0') d3_ended,
            (SELECT q_fever FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 2 day) LIMIT 1) d3_fever,
            (SELECT q_sick_sign FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 2 day) LIMIT 1) d3_sick,
            (SELECT temp FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 2 day) LIMIT 1) d3_temp,
            (SELECT sick_sign FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 2 day) LIMIT 1) d3_sign,
            (SELECT remark FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 2 day) LIMIT 1) d3_remark,

            DATE_ADD(person.date_in,INTERVAL 3 day) d4,
            IF(DATE(NOW()) > DATE_ADD(person.date_in,INTERVAL 2 day), '1', '0') d4_ended,
            (SELECT q_fever FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 3 day) LIMIT 1) d4_fever,
            (SELECT q_sick_sign FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 3 day) LIMIT 1) d4_sick,
            (SELECT temp FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 3 day) LIMIT 1) d4_temp,
            (SELECT sick_sign FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 3 day) LIMIT 1) d4_sign,
            (SELECT remark FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 3 day) LIMIT 1) d4_remark,

            DATE_ADD(person.date_in,INTERVAL 4 day) d5,
            IF(DATE(NOW()) > DATE_ADD(person.date_in,INTERVAL 3 day), '1', '0') d5_ended,
            (SELECT q_fever FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 4 day) LIMIT 1) d5_fever,
            (SELECT q_sick_sign FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 4 day) LIMIT 1) d5_sick,
            (SELECT temp FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 4 day) LIMIT 1) d5_temp,
            (SELECT sick_sign FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 4 day) LIMIT 1) d5_sign,
            (SELECT remark FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 4 day) LIMIT 1) d5_remark,

                
            DATE_ADD(person.date_in,INTERVAL 5 day) d6,
            IF(DATE(NOW()) > DATE_ADD(person.date_in,INTERVAL 4 day), '1', '0') d6_ended,
            (SELECT q_fever FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 5 day) LIMIT 1) d6_fever,
            (SELECT q_sick_sign FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 5 day) LIMIT 1) d6_sick,
            (SELECT temp FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 5 day) LIMIT 1) d6_temp,
            (SELECT sick_sign FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 5 day) LIMIT 1) d6_ssign,
            (SELECT remark FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 5 day) LIMIT 1) d6_remark,

            DATE_ADD(person.date_in,INTERVAL 6 day) d7,
            IF(DATE(NOW()) > DATE_ADD(person.date_in,INTERVAL 5 day), '1', '0') d7_ended,
            (SELECT q_fever FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 6 day) LIMIT 1) d7_fever,
            (SELECT q_sick_sign FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 6 day) LIMIT 1) d7_sick,
            (SELECT temp FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 6 day) LIMIT 1) d7_temp,
            (SELECT sick_sign FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 6 day) LIMIT 1) d7_sign,
            (SELECT remark FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 6 day) LIMIT 1) d7_remark,

            DATE_ADD(person.date_in,INTERVAL 7 day) d8,
            IF(DATE(NOW()) > DATE_ADD(person.date_in,INTERVAL 6 day), '1', '0') d8_ended,
            (SELECT q_fever FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 7 day) LIMIT 1) d8_fever,
            (SELECT q_sick_sign FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 7 day) LIMIT 1) d8_sick,
            (SELECT temp FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 7 day) LIMIT 1) d8_temp,
            (SELECT sick_sign FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 7 day) LIMIT 1) d8_sign,
            (SELECT remark FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 7 day) LIMIT 1) d8_remark,

            DATE_ADD(person.date_in,INTERVAL 8 day) d9,
            IF(DATE(NOW()) > DATE_ADD(person.date_in,INTERVAL 7 day), '1', '0') d9_ended,
            (SELECT q_fever FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 8 day) LIMIT 1) d9_fever,
            (SELECT q_sick_sign FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 8 day) LIMIT 1) d9_sick,
            (SELECT temp FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 8 day) LIMIT 1) d9_temp,
            (SELECT sick_sign FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 8 day) LIMIT 1) d9_sign,
            (SELECT remark FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 8 day) LIMIT 1) d9_remark,

            DATE_ADD(person.date_in,INTERVAL 9 day) d10,
            IF(DATE(NOW()) > DATE_ADD(person.date_in,INTERVAL 8 day), '1', '0') d10_ended,
            (SELECT q_fever FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 9 day) LIMIT 1) d10_fever,
            (SELECT q_sick_sign FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 9 day) LIMIT 1) d10_sick,
            (SELECT temp FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 9 day) LIMIT 1) d10_temp,
            (SELECT sick_sign FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 9 day) LIMIT 1) d10_sign,
            (SELECT remark FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 9 day) LIMIT 1) d10_remark,

            DATE_ADD(person.date_in,INTERVAL 10 day) d11,
            IF(DATE(NOW()) > DATE_ADD(person.date_in,INTERVAL 9 day), '1', '0') d11_ended,
            (SELECT q_fever FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 10 day) LIMIT 1) d11_fever,
            (SELECT q_sick_sign FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 10 day) LIMIT 1) d11_sick,
            (SELECT temp FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 10 day) LIMIT 1) d11_temp,
            (SELECT sick_sign FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 10 day) LIMIT 1) d11_sign,
            (SELECT remark FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 10 day) LIMIT 1) d11_remark,

            DATE_ADD(person.date_in,INTERVAL 11 day) d12,
            IF(DATE(NOW()) > DATE_ADD(person.date_in,INTERVAL 10 day), '1', '0') d12_ended,
            (SELECT q_fever FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 11 day) LIMIT 1) d12_fever,
            (SELECT q_sick_sign FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 11 day) LIMIT 1) d12_sick,
            (SELECT temp FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 11 day) LIMIT 1) d12_temp,
            (SELECT sick_sign FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 11 day) LIMIT 1) d12_sign,
            (SELECT remark FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 11 day) LIMIT 1) d12_remark,

            DATE_ADD(person.date_in,INTERVAL 12 day) d13,
            IF(DATE(NOW()) > DATE_ADD(person.date_in,INTERVAL 11 day), '1', '0') d13_ended,
            (SELECT q_fever FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 12 day) LIMIT 1) d13_fever,
            (SELECT q_sick_sign FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 12 day) LIMIT 1) d13_sick,
            (SELECT temp FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 12 day) LIMIT 1) d13_temp,
            (SELECT sick_sign FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 12 day) LIMIT 1) d13_sign,
            (SELECT remark FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 12 day) LIMIT 1) d13_remark,

            DATE_ADD(person.date_in,INTERVAL 13 day) d14,
            IF(DATE(NOW()) > DATE_ADD(person.date_in,INTERVAL 12 day), '1', '0') d14_ended,
            (SELECT q_fever FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 13 day) LIMIT 1) d14_fever,
            (SELECT q_sick_sign FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 13 day) LIMIT 1) d14_sick,
            (SELECT temp FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 13 day) LIMIT 1) d14_temp,
            (SELECT sick_sign FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 13 day) LIMIT 1) d14_sign,
            (SELECT remark FROM followup f WHERE f.cid = person.cid AND f.report_date = DATE_ADD(person.date_in,INTERVAL 13 day) LIMIT 1) d14_remark,
            IF(DATE(NOW()) > DATE_ADD(person.date_in,INTERVAL 13 day),'1','0') AS pass14days
            FROM
            person
                        LEFT JOIN ampur a ON person.addr_ampur = a.ampurcodefull
                        LEFT JOIN tambon  ON person.addr_tambon = tambon.tamboncodefull
                        LEFT JOIN village v ON CONCAT(person.addr_tambon, person.addr_vill_no) = v.villagecodefull
                LEFT JOIN province p ON person.move_province = p.changwatcode
                LEFT JOIN ampur am ON person.move_ampur = am.ampurcodefull
                LEFT JOIN tambon  t ON person.move_tambon = t.tamboncodefull
           
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
														 
                        WHERE 
                        ".$condition2."
                        " . $condition . "


            GROUP BY cid
            ORDER BY person.date_in DESC

            ")->queryAll();
            }, 120, null);
        }

        return $this->render('passdays', [
            'model' => $model,
            'person_data' => $person_data,
            'tambon' => $tambon,
            'village' => $village,
        ]);
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionSelfScreening()
    {
        return $this->render('self_screening');
    }

    public function actionCovid19api()
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, 'https://covid19.th-stat.com/api/open/today');
        $result = curl_exec($ch);
        curl_close($ch);

        // var_dump($result);

        if( $result != null ){
             $obj = json_decode($result);
              $model =  Covid19th::findOne(1);
              $model->confirmed=$obj->Confirmed;
              $model->recovered=$obj->Recovered;
              $model->hospitalized=$obj->Hospitalized;
              $model->deaths=$obj->Deaths;
              $model->newConfirmed=$obj->NewConfirmed;
              $model->newRecovered=$obj->NewRecovered;
              $model->newHospitalized=$obj->NewHospitalized;
              $model->newDeaths=$obj->NewDeaths;
            //   $model->updateDate=$obj->UpdateDate;
              $model->save();
        }
         var_dump($obj);
      
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

            //$this->layout = '/main_blank';
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


    public function actionClearCache()
    {
        $r = Yii::$app->cache->flush();
        return $this->redirect(['index']);
    }


    public function actionGenCid()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $connection = Yii::$app->db_fingerprint;
        $cid = $connection->createCommand('SELECT auto_cid() AS cid')->queryAll();

        if (strlen($cid[0]['cid']) == 13) {
            return $this->asJson(['result' => 'ok', 'cid' => $cid[0]['cid']]);
        } else {
            return $this->asJson(['result' => 'error', 'cid' => '']);
        }
    }

    public function actionGetTambon()
    {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if ($parents != null) {
                $ampur_id = $parents[0];
                $out = $this->getTambon($ampur_id);
                echo Json::encode(['output' => $out, 'selected' => '']);
                return;
            }
        }
        echo Json::encode(['output' => '', 'selected' => '']);
    }

    public function actionGetVillage()
    {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $ids = $_POST['depdrop_parents'];
            $ampur_id = empty($ids[0]) ? null : $ids[0];
            $tambon_id = empty($ids[1]) ? null : $ids[1];
            if ($ampur_id != null) {
                $data = $this->getVillage($tambon_id);
                echo Json::encode(['output' => $data, 'selected' => '']);
                return;
            }
        }
        echo Json::encode(['output' => '', 'selected' => '']);
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


    protected function getAmphur($id)
    {
        $datas = Ampur::find()
            ->where(['changwatcode' => $id])
            ->andwhere(['NOT LIKE', 'ampurname', '*'])
            ->all();
        return $this->MapData($datas, 'ampurcodefull', 'ampurname');
    }

        public function actionGetindays()
    {
        $model = new Locate();
        $model->load(Yii::$app->request->post());

        $condition = "";




        $condition2 = "TIMESTAMPDIFF(day,person_sqc.date_in,NOW()) <= 13 AND" ;
        if ($model->load(Yii::$app->request->post())) {
            if ($model->village != '') {
                $condition = "person_sqc.addr_tambon = '" . $model->subdistrict . "' AND person_sqc.addr_vill_no = RIGHT('" . $model->village . "',2)";
            } elseif ($model->subdistrict != '') {
                $condition = "person_sqc.addr_tambon = '" . $model->subdistrict . "'";
            } elseif ($model->district != '') {
                $condition = "person_sqc.addr_ampur = '" . $model->district . "'";
            } else {
                $condition = "person_sqc.addr_ampur = '2701'";
                
            }


            if ($model->lost_days == '1') {
                $condition2 = "TIMESTAMPDIFF(day,person_sqc.date_in,NOW()) <= 13 AND"  ;
               // $condition2 = "TIMESTAMPDIFF(day,person_sqc.date_in,NOW()) <= 30 AND"  ;
            } elseif ($model->lost_days == '2')  {
                
                $condition2 = "TIMESTAMPDIFF(day,person_sqc.date_in,NOW()) <= 30 AND"  ;
            } elseif ($model->lost_days == '3')  {
                
                $condition2 = "TIMESTAMPDIFF(day,person_sqc.date_in,NOW()) <= 60 AND"  ;
            } elseif ($model->lost_days == '4')  {
                
                $condition2 = "TIMESTAMPDIFF(day,person_sqc.date_in,NOW()) <= 90 AND"  ;
            } elseif ($model->lost_days == '5') {
                $condition2 = "";
            }   

        } else {
            $model->lost_days = '1';
            $person_data = [];
        }



        $tambon         = ArrayHelper::map($this->getTambon($model->district), 'id', 'name');
        $village       = ArrayHelper::map($this->getVillage($model->subdistrict), 'id', 'name');




        $connection = Yii::$app->db_covid;

        if ($model->district != '') {
            $person_data = $connection->cache(function ($connection) use ($condition, $condition2) {
                return $connection->createCommand("
            SELECT
            person_sqc.*,
            v.villagecodefull,
                                    IF(v.`villagename` IS NULL,'รหัสผิดพลาด',CONCAT(v.`villagename`, ' ต.', tambon.tambonname)) AS vill_name,
            a.ampurname,
            p.changwatname,
            am.ampurname AS mampurname,
            t.tambonname,
            person_sqc.date_in AS d1,
            IF(DATE(NOW()) > person_sqc.date_in, '1', '0') d1_ended,
            IF(person_sqc.temp >= 37.5,'1','0') d1_fever,
            person_sqc.q_sick_sign d1_sick,
            person_sqc.temp d1_temp,
            person_sqc.sick_sign d1_sign,
            person_sqc.status d1_remark,
            DATE_ADD(person_sqc.date_in,INTERVAL 1 day) d2,
            IF(DATE(NOW()) > person_sqc.date_in, '1', '0') d2_ended,
            (SELECT q_fever FROM followup_sqc f WHERE f.cid = person_sqc.cid AND f.report_date = DATE_ADD(person_sqc.date_in,INTERVAL 1 day) LIMIT 1) d2_fever,
            (SELECT q_sick_sign FROM followup_sqc f WHERE f.cid = person_sqc.cid AND f.report_date = DATE_ADD(person_sqc.date_in,INTERVAL 1 day) LIMIT 1) d2_sick,
            (SELECT temp FROM followup_sqc f WHERE f.cid = person_sqc.cid AND f.report_date = DATE_ADD(person_sqc.date_in,INTERVAL 1 day) LIMIT 1) d2_temp,
            (SELECT sick_sign FROM followup_sqc f WHERE f.cid = person_sqc.cid AND f.report_date = DATE_ADD(person_sqc.date_in,INTERVAL 1 day) LIMIT 1) d2_sign,
            (SELECT remark FROM followup_sqc f WHERE f.cid = person_sqc.cid AND f.report_date = DATE_ADD(person_sqc.date_in,INTERVAL 1 day) LIMIT 1) d2_remark,

            DATE_ADD(person_sqc.date_in,INTERVAL 2 day) d3,
            IF(DATE(NOW()) > DATE_ADD(person_sqc.date_in,INTERVAL 1 day), '1', '0') d3_ended,
            (SELECT q_fever FROM followup_sqc f WHERE f.cid = person_sqc.cid AND f.report_date = DATE_ADD(person_sqc.date_in,INTERVAL 2 day) LIMIT 1) d3_fever,
            (SELECT q_sick_sign FROM followup_sqc f WHERE f.cid = person_sqc.cid AND f.report_date = DATE_ADD(person_sqc.date_in,INTERVAL 2 day) LIMIT 1) d3_sick,
            (SELECT temp FROM followup_sqc f WHERE f.cid = person_sqc.cid AND f.report_date = DATE_ADD(person_sqc.date_in,INTERVAL 2 day) LIMIT 1) d3_temp,
            (SELECT sick_sign FROM followup_sqc f WHERE f.cid = person_sqc.cid AND f.report_date = DATE_ADD(person_sqc.date_in,INTERVAL 2 day) LIMIT 1) d3_sign,
            (SELECT remark FROM followup_sqc f WHERE f.cid = person_sqc.cid AND f.report_date = DATE_ADD(person_sqc.date_in,INTERVAL 2 day) LIMIT 1) d3_remark,

            DATE_ADD(person_sqc.date_in,INTERVAL 3 day) d4,
            IF(DATE(NOW()) > DATE_ADD(person_sqc.date_in,INTERVAL 2 day), '1', '0') d4_ended,
            (SELECT q_fever FROM followup_sqc f WHERE f.cid = person_sqc.cid AND f.report_date = DATE_ADD(person_sqc.date_in,INTERVAL 3 day) LIMIT 1) d4_fever,
            (SELECT q_sick_sign FROM followup_sqc f WHERE f.cid = person_sqc.cid AND f.report_date = DATE_ADD(person_sqc.date_in,INTERVAL 3 day) LIMIT 1) d4_sick,
            (SELECT temp FROM followup_sqc f WHERE f.cid = person_sqc.cid AND f.report_date = DATE_ADD(person_sqc.date_in,INTERVAL 3 day) LIMIT 1) d4_temp,
            (SELECT sick_sign FROM followup_sqc f WHERE f.cid = person_sqc.cid AND f.report_date = DATE_ADD(person_sqc.date_in,INTERVAL 3 day) LIMIT 1) d4_sign,
            (SELECT remark FROM followup_sqc f WHERE f.cid = person_sqc.cid AND f.report_date = DATE_ADD(person_sqc.date_in,INTERVAL 3 day) LIMIT 1) d4_remark,

            DATE_ADD(person_sqc.date_in,INTERVAL 4 day) d5,
            IF(DATE(NOW()) > DATE_ADD(person_sqc.date_in,INTERVAL 3 day), '1', '0') d5_ended,
            (SELECT q_fever FROM followup_sqc f WHERE f.cid = person_sqc.cid AND f.report_date = DATE_ADD(person_sqc.date_in,INTERVAL 4 day) LIMIT 1) d5_fever,
            (SELECT q_sick_sign FROM followup_sqc f WHERE f.cid = person_sqc.cid AND f.report_date = DATE_ADD(person_sqc.date_in,INTERVAL 4 day) LIMIT 1) d5_sick,
            (SELECT temp FROM followup_sqc f WHERE f.cid = person_sqc.cid AND f.report_date = DATE_ADD(person_sqc.date_in,INTERVAL 4 day) LIMIT 1) d5_temp,
            (SELECT sick_sign FROM followup_sqc f WHERE f.cid = person_sqc.cid AND f.report_date = DATE_ADD(person_sqc.date_in,INTERVAL 4 day) LIMIT 1) d5_sign,
            (SELECT remark FROM followup_sqc f WHERE f.cid = person_sqc.cid AND f.report_date = DATE_ADD(person_sqc.date_in,INTERVAL 4 day) LIMIT 1) d5_remark,

                
            DATE_ADD(person_sqc.date_in,INTERVAL 5 day) d6,
            IF(DATE(NOW()) > DATE_ADD(person_sqc.date_in,INTERVAL 4 day), '1', '0') d6_ended,
            (SELECT q_fever FROM followup_sqc f WHERE f.cid = person_sqc.cid AND f.report_date = DATE_ADD(person_sqc.date_in,INTERVAL 5 day) LIMIT 1) d6_fever,
            (SELECT q_sick_sign FROM followup_sqc f WHERE f.cid = person_sqc.cid AND f.report_date = DATE_ADD(person_sqc.date_in,INTERVAL 5 day) LIMIT 1) d6_sick,
            (SELECT temp FROM followup_sqc f WHERE f.cid = person_sqc.cid AND f.report_date = DATE_ADD(person_sqc.date_in,INTERVAL 5 day) LIMIT 1) d6_temp,
            (SELECT sick_sign FROM followup_sqc f WHERE f.cid = person_sqc.cid AND f.report_date = DATE_ADD(person_sqc.date_in,INTERVAL 5 day) LIMIT 1) d6_ssign,
            (SELECT remark FROM followup_sqc f WHERE f.cid = person_sqc.cid AND f.report_date = DATE_ADD(person_sqc.date_in,INTERVAL 5 day) LIMIT 1) d6_remark,

            DATE_ADD(person_sqc.date_in,INTERVAL 6 day) d7,
            IF(DATE(NOW()) > DATE_ADD(person_sqc.date_in,INTERVAL 5 day), '1', '0') d7_ended,
            (SELECT q_fever FROM followup_sqc f WHERE f.cid = person_sqc.cid AND f.report_date = DATE_ADD(person_sqc.date_in,INTERVAL 6 day) LIMIT 1) d7_fever,
            (SELECT q_sick_sign FROM followup_sqc f WHERE f.cid = person_sqc.cid AND f.report_date = DATE_ADD(person_sqc.date_in,INTERVAL 6 day) LIMIT 1) d7_sick,
            (SELECT temp FROM followup_sqc f WHERE f.cid = person_sqc.cid AND f.report_date = DATE_ADD(person_sqc.date_in,INTERVAL 6 day) LIMIT 1) d7_temp,
            (SELECT sick_sign FROM followup_sqc f WHERE f.cid = person_sqc.cid AND f.report_date = DATE_ADD(person_sqc.date_in,INTERVAL 6 day) LIMIT 1) d7_sign,
            (SELECT remark FROM followup_sqc f WHERE f.cid = person_sqc.cid AND f.report_date = DATE_ADD(person_sqc.date_in,INTERVAL 6 day) LIMIT 1) d7_remark,

            DATE_ADD(person_sqc.date_in,INTERVAL 7 day) d8,
            IF(DATE(NOW()) > DATE_ADD(person_sqc.date_in,INTERVAL 6 day), '1', '0') d8_ended,
            (SELECT q_fever FROM followup_sqc f WHERE f.cid = person_sqc.cid AND f.report_date = DATE_ADD(person_sqc.date_in,INTERVAL 7 day) LIMIT 1) d8_fever,
            (SELECT q_sick_sign FROM followup_sqc f WHERE f.cid = person_sqc.cid AND f.report_date = DATE_ADD(person_sqc.date_in,INTERVAL 7 day) LIMIT 1) d8_sick,
            (SELECT temp FROM followup_sqc f WHERE f.cid = person_sqc.cid AND f.report_date = DATE_ADD(person_sqc.date_in,INTERVAL 7 day) LIMIT 1) d8_temp,
            (SELECT sick_sign FROM followup_sqc f WHERE f.cid = person_sqc.cid AND f.report_date = DATE_ADD(person_sqc.date_in,INTERVAL 7 day) LIMIT 1) d8_sign,
            (SELECT remark FROM followup_sqc f WHERE f.cid = person_sqc.cid AND f.report_date = DATE_ADD(person_sqc.date_in,INTERVAL 7 day) LIMIT 1) d8_remark,

            DATE_ADD(person_sqc.date_in,INTERVAL 8 day) d9,
            IF(DATE(NOW()) > DATE_ADD(person_sqc.date_in,INTERVAL 7 day), '1', '0') d9_ended,
            (SELECT q_fever FROM followup_sqc f WHERE f.cid = person_sqc.cid AND f.report_date = DATE_ADD(person_sqc.date_in,INTERVAL 8 day) LIMIT 1) d9_fever,
            (SELECT q_sick_sign FROM followup_sqc f WHERE f.cid = person_sqc.cid AND f.report_date = DATE_ADD(person_sqc.date_in,INTERVAL 8 day) LIMIT 1) d9_sick,
            (SELECT temp FROM followup_sqc f WHERE f.cid = person_sqc.cid AND f.report_date = DATE_ADD(person_sqc.date_in,INTERVAL 8 day) LIMIT 1) d9_temp,
            (SELECT sick_sign FROM followup_sqc f WHERE f.cid = person_sqc.cid AND f.report_date = DATE_ADD(person_sqc.date_in,INTERVAL 8 day) LIMIT 1) d9_sign,
            (SELECT remark FROM followup_sqc f WHERE f.cid = person_sqc.cid AND f.report_date = DATE_ADD(person_sqc.date_in,INTERVAL 8 day) LIMIT 1) d9_remark,

            DATE_ADD(person_sqc.date_in,INTERVAL 9 day) d10,
            IF(DATE(NOW()) > DATE_ADD(person_sqc.date_in,INTERVAL 8 day), '1', '0') d10_ended,
            (SELECT q_fever FROM followup_sqc f WHERE f.cid = person_sqc.cid AND f.report_date = DATE_ADD(person_sqc.date_in,INTERVAL 9 day) LIMIT 1) d10_fever,
            (SELECT q_sick_sign FROM followup_sqc f WHERE f.cid = person_sqc.cid AND f.report_date = DATE_ADD(person_sqc.date_in,INTERVAL 9 day) LIMIT 1) d10_sick,
            (SELECT temp FROM followup_sqc f WHERE f.cid = person_sqc.cid AND f.report_date = DATE_ADD(person_sqc.date_in,INTERVAL 9 day) LIMIT 1) d10_temp,
            (SELECT sick_sign FROM followup_sqc f WHERE f.cid = person_sqc.cid AND f.report_date = DATE_ADD(person_sqc.date_in,INTERVAL 9 day) LIMIT 1) d10_sign,
            (SELECT remark FROM followup_sqc f WHERE f.cid = person_sqc.cid AND f.report_date = DATE_ADD(person_sqc.date_in,INTERVAL 9 day) LIMIT 1) d10_remark,

            DATE_ADD(person_sqc.date_in,INTERVAL 10 day) d11,
            IF(DATE(NOW()) > DATE_ADD(person_sqc.date_in,INTERVAL 9 day), '1', '0') d11_ended,
            (SELECT q_fever FROM followup_sqc f WHERE f.cid = person_sqc.cid AND f.report_date = DATE_ADD(person_sqc.date_in,INTERVAL 10 day) LIMIT 1) d11_fever,
            (SELECT q_sick_sign FROM followup_sqc f WHERE f.cid = person_sqc.cid AND f.report_date = DATE_ADD(person_sqc.date_in,INTERVAL 10 day) LIMIT 1) d11_sick,
            (SELECT temp FROM followup_sqc f WHERE f.cid = person_sqc.cid AND f.report_date = DATE_ADD(person_sqc.date_in,INTERVAL 10 day) LIMIT 1) d11_temp,
            (SELECT sick_sign FROM followup_sqc f WHERE f.cid = person_sqc.cid AND f.report_date = DATE_ADD(person_sqc.date_in,INTERVAL 10 day) LIMIT 1) d11_sign,
            (SELECT remark FROM followup_sqc f WHERE f.cid = person_sqc.cid AND f.report_date = DATE_ADD(person_sqc.date_in,INTERVAL 10 day) LIMIT 1) d11_remark,

            DATE_ADD(person_sqc.date_in,INTERVAL 11 day) d12,
            IF(DATE(NOW()) > DATE_ADD(person_sqc.date_in,INTERVAL 10 day), '1', '0') d12_ended,
            (SELECT q_fever FROM followup_sqc f WHERE f.cid = person_sqc.cid AND f.report_date = DATE_ADD(person_sqc.date_in,INTERVAL 11 day) LIMIT 1) d12_fever,
            (SELECT q_sick_sign FROM followup_sqc f WHERE f.cid = person_sqc.cid AND f.report_date = DATE_ADD(person_sqc.date_in,INTERVAL 11 day) LIMIT 1) d12_sick,
            (SELECT temp FROM followup_sqc f WHERE f.cid = person_sqc.cid AND f.report_date = DATE_ADD(person_sqc.date_in,INTERVAL 11 day) LIMIT 1) d12_temp,
            (SELECT sick_sign FROM followup_sqc f WHERE f.cid = person_sqc.cid AND f.report_date = DATE_ADD(person_sqc.date_in,INTERVAL 11 day) LIMIT 1) d12_sign,
            (SELECT remark FROM followup_sqc f WHERE f.cid = person_sqc.cid AND f.report_date = DATE_ADD(person_sqc.date_in,INTERVAL 11 day) LIMIT 1) d12_remark,

            DATE_ADD(person_sqc.date_in,INTERVAL 12 day) d13,
            IF(DATE(NOW()) > DATE_ADD(person_sqc.date_in,INTERVAL 11 day), '1', '0') d13_ended,
            (SELECT q_fever FROM followup_sqc f WHERE f.cid = person_sqc.cid AND f.report_date = DATE_ADD(person_sqc.date_in,INTERVAL 12 day) LIMIT 1) d13_fever,
            (SELECT q_sick_sign FROM followup_sqc f WHERE f.cid = person_sqc.cid AND f.report_date = DATE_ADD(person_sqc.date_in,INTERVAL 12 day) LIMIT 1) d13_sick,
            (SELECT temp FROM followup_sqc f WHERE f.cid = person_sqc.cid AND f.report_date = DATE_ADD(person_sqc.date_in,INTERVAL 12 day) LIMIT 1) d13_temp,
            (SELECT sick_sign FROM followup_sqc f WHERE f.cid = person_sqc.cid AND f.report_date = DATE_ADD(person_sqc.date_in,INTERVAL 12 day) LIMIT 1) d13_sign,
            (SELECT remark FROM followup_sqc f WHERE f.cid = person_sqc.cid AND f.report_date = DATE_ADD(person_sqc.date_in,INTERVAL 12 day) LIMIT 1) d13_remark,

            DATE_ADD(person_sqc.date_in,INTERVAL 13 day) d14,
            IF(DATE(NOW()) > DATE_ADD(person_sqc.date_in,INTERVAL 12 day), '1', '0') d14_ended,
            (SELECT q_fever FROM followup_sqc f WHERE f.cid = person_sqc.cid AND f.report_date = DATE_ADD(person_sqc.date_in,INTERVAL 13 day) LIMIT 1) d14_fever,
            (SELECT q_sick_sign FROM followup_sqc f WHERE f.cid = person_sqc.cid AND f.report_date = DATE_ADD(person_sqc.date_in,INTERVAL 13 day) LIMIT 1) d14_sick,
            (SELECT temp FROM followup_sqc f WHERE f.cid = person_sqc.cid AND f.report_date = DATE_ADD(person_sqc.date_in,INTERVAL 13 day) LIMIT 1) d14_temp,
            (SELECT sick_sign FROM followup_sqc f WHERE f.cid = person_sqc.cid AND f.report_date = DATE_ADD(person_sqc.date_in,INTERVAL 13 day) LIMIT 1) d14_sign,
            (SELECT remark FROM followup_sqc f WHERE f.cid = person_sqc.cid AND f.report_date = DATE_ADD(person_sqc.date_in,INTERVAL 13 day) LIMIT 1) d14_remark,
            IF(DATE(NOW()) > DATE_ADD(person_sqc.date_in,INTERVAL 13 day),'1','0') AS pass14days
            FROM
            person_sqc
                        LEFT JOIN ampur a ON person_sqc.addr_ampur = a.ampurcodefull
                        LEFT JOIN tambon  ON person_sqc.addr_tambon = tambon.tamboncodefull
                        LEFT JOIN village v ON CONCAT(person_sqc.addr_tambon, person_sqc.addr_vill_no) = v.villagecodefull
                LEFT JOIN province p ON person_sqc.move_province = p.changwatcode
                LEFT JOIN ampur am ON person_sqc.move_ampur = am.ampurcodefull
                LEFT JOIN tambon  t ON person_sqc.move_tambon = t.tamboncodefull
           
LEFT JOIN (
                        SELECT f.* FROM (SELECT 
                                followup_sqc.cid,
                                MAX(followup_sqc.report_date) AS report_date
                                FROM 
                                followup_sqc
                                GROUP BY
                                followup_sqc.cid) a INNER JOIN followup_sqc f ON a.cid = f.cid AND a.report_date = f.report_date
                                GROUP BY f.cid
                        ) l_follow ON l_follow.cid = person_sqc.cid
                                                         
                       WHERE 
                        ".$condition2."
                        " . $condition . "


            GROUP BY cid
            ORDER BY person_sqc.date_in DESC

            ")->queryAll();
            }, 120, null);
        }

        return $this->render('getindays', [
            'model' => $model,
            'person_data' => $person_data,
            'tambon' => $tambon,
            'village' => $village,
        ]);
    }

     public function actionGethbdays()
    {
        $model = new Locate();
        $model->load(Yii::$app->request->post());

        $condition = "";




        $condition2 = "TIMESTAMPDIFF(day,person_hb.date_in,NOW()) <= 13 AND" ;
        if ($model->load(Yii::$app->request->post())) {
            if ($model->village != '') {
                $condition = "person_hb.addr_tambon = '" . $model->subdistrict . "' AND person_hb.addr_vill_no = RIGHT('" . $model->village . "',2)";
            } elseif ($model->subdistrict != '') {
                $condition = "person_hb.addr_tambon = '" . $model->subdistrict . "'";
            } elseif ($model->district != '') {
                $condition = "person_hb.addr_ampur = '" . $model->district . "'";
            } else {
                $condition = "person_hb.addr_ampur = '2701'";
                
            }


            if ($model->lost_days == '1') {
                $condition2 = "TIMESTAMPDIFF(day,person_hb.date_in,NOW()) <= 13 AND"  ;
               // $condition2 = "TIMESTAMPDIFF(day,person_hb.date_in,NOW()) <= 30 AND"  ;
            } elseif ($model->lost_days == '2')  {
                
                $condition2 = "TIMESTAMPDIFF(day,person_hb.date_in,NOW()) <= 30 AND"  ;
            } elseif ($model->lost_days == '3')  {
                
                $condition2 = "TIMESTAMPDIFF(day,person_hb.date_in,NOW()) <= 60 AND"  ;
            } elseif ($model->lost_days == '4')  {
                
                $condition2 = "TIMESTAMPDIFF(day,person_hb.date_in,NOW()) <= 90 AND"  ;
            } elseif ($model->lost_days == '5') {
                $condition2 = "";
            }   

        } else {
            $model->lost_days = '1';
            $person_data = [];
        }



        $tambon        = ArrayHelper::map($this->getTambon($model->district), 'id', 'name');
        $village       = ArrayHelper::map($this->getVillage($model->subdistrict), 'id', 'name');




        $connection = Yii::$app->db_covid;

        if ($model->district != '') {
            $person_data = $connection->cache(function ($connection) use ($condition, $condition2) {
                return $connection->createCommand("
            SELECT
            person_hb.*,
            v.villagecodefull,
                                    IF(v.`villagename` IS NULL,'รหัสผิดพลาด',CONCAT(v.`villagename`, ' ต.', tambon.tambonname)) AS vill_name,
            a.ampurname,
            p.changwatname,
            am.ampurname AS mampurname,
            t.tambonname,
            person_hb.date_in AS d1,
            person_hb.date_out AS do,
            IF(DATE(NOW()) > person_hb.date_in, '1', '0') d1_ended,
            IF(person_hb.temp >= 37.5,'1','0') d1_fever,
            person_hb.q_sick_sign d1_sick,
            person_hb.temp d1_temp,
            person_hb.sick_sign d1_sign,
            person_hb.status d1_remark,
            DATE_ADD(person_hb.date_in,INTERVAL 1 day) d2,
            IF(DATE(NOW()) > person_hb.date_in, '1', '0') d2_ended,
            (SELECT q_fever FROM followup_hb f WHERE f.cid = person_hb.cid AND f.report_date = DATE_ADD(person_hb.date_in,INTERVAL 1 day) LIMIT 1) d2_fever,
            (SELECT q_sick_sign FROM followup_hb f WHERE f.cid = person_hb.cid AND f.report_date = DATE_ADD(person_hb.date_in,INTERVAL 1 day) LIMIT 1) d2_sick,
            (SELECT temp FROM followup_hb f WHERE f.cid = person_hb.cid AND f.report_date = DATE_ADD(person_hb.date_in,INTERVAL 1 day) LIMIT 1) d2_temp,
            (SELECT sick_sign FROM followup_hb f WHERE f.cid = person_hb.cid AND f.report_date = DATE_ADD(person_hb.date_in,INTERVAL 1 day) LIMIT 1) d2_sign,
            (SELECT remark FROM followup_hb f WHERE f.cid = person_hb.cid AND f.report_date = DATE_ADD(person_hb.date_in,INTERVAL 1 day) LIMIT 1) d2_remark,

            DATE_ADD(person_hb.date_in,INTERVAL 2 day) d3,
            IF(DATE(NOW()) > DATE_ADD(person_hb.date_in,INTERVAL 1 day), '1', '0') d3_ended,
            (SELECT q_fever FROM followup_hb f WHERE f.cid = person_hb.cid AND f.report_date = DATE_ADD(person_hb.date_in,INTERVAL 2 day) LIMIT 1) d3_fever,
            (SELECT q_sick_sign FROM followup_hb f WHERE f.cid = person_hb.cid AND f.report_date = DATE_ADD(person_hb.date_in,INTERVAL 2 day) LIMIT 1) d3_sick,
            (SELECT temp FROM followup_hb f WHERE f.cid = person_hb.cid AND f.report_date = DATE_ADD(person_hb.date_in,INTERVAL 2 day) LIMIT 1) d3_temp,
            (SELECT sick_sign FROM followup_hb f WHERE f.cid = person_hb.cid AND f.report_date = DATE_ADD(person_hb.date_in,INTERVAL 2 day) LIMIT 1) d3_sign,
            (SELECT remark FROM followup_hb f WHERE f.cid = person_hb.cid AND f.report_date = DATE_ADD(person_hb.date_in,INTERVAL 2 day) LIMIT 1) d3_remark,

            DATE_ADD(person_hb.date_in,INTERVAL 3 day) d4,
            IF(DATE(NOW()) > DATE_ADD(person_hb.date_in,INTERVAL 2 day), '1', '0') d4_ended,
            (SELECT q_fever FROM followup_hb f WHERE f.cid = person_hb.cid AND f.report_date = DATE_ADD(person_hb.date_in,INTERVAL 3 day) LIMIT 1) d4_fever,
            (SELECT q_sick_sign FROM followup_hb f WHERE f.cid = person_hb.cid AND f.report_date = DATE_ADD(person_hb.date_in,INTERVAL 3 day) LIMIT 1) d4_sick,
            (SELECT temp FROM followup_hb f WHERE f.cid = person_hb.cid AND f.report_date = DATE_ADD(person_hb.date_in,INTERVAL 3 day) LIMIT 1) d4_temp,
            (SELECT sick_sign FROM followup_hb f WHERE f.cid = person_hb.cid AND f.report_date = DATE_ADD(person_hb.date_in,INTERVAL 3 day) LIMIT 1) d4_sign,
            (SELECT remark FROM followup_hb f WHERE f.cid = person_hb.cid AND f.report_date = DATE_ADD(person_hb.date_in,INTERVAL 3 day) LIMIT 1) d4_remark,

            DATE_ADD(person_hb.date_in,INTERVAL 4 day) d5,
            IF(DATE(NOW()) > DATE_ADD(person_hb.date_in,INTERVAL 3 day), '1', '0') d5_ended,
            (SELECT q_fever FROM followup_hb f WHERE f.cid = person_hb.cid AND f.report_date = DATE_ADD(person_hb.date_in,INTERVAL 4 day) LIMIT 1) d5_fever,
            (SELECT q_sick_sign FROM followup_hb f WHERE f.cid = person_hb.cid AND f.report_date = DATE_ADD(person_hb.date_in,INTERVAL 4 day) LIMIT 1) d5_sick,
            (SELECT temp FROM followup_hb f WHERE f.cid = person_hb.cid AND f.report_date = DATE_ADD(person_hb.date_in,INTERVAL 4 day) LIMIT 1) d5_temp,
            (SELECT sick_sign FROM followup_hb f WHERE f.cid = person_hb.cid AND f.report_date = DATE_ADD(person_hb.date_in,INTERVAL 4 day) LIMIT 1) d5_sign,
            (SELECT remark FROM followup_hb f WHERE f.cid = person_hb.cid AND f.report_date = DATE_ADD(person_hb.date_in,INTERVAL 4 day) LIMIT 1) d5_remark,

                
            DATE_ADD(person_hb.date_in,INTERVAL 5 day) d6,
            IF(DATE(NOW()) > DATE_ADD(person_hb.date_in,INTERVAL 4 day), '1', '0') d6_ended,
            (SELECT q_fever FROM followup_hb f WHERE f.cid = person_hb.cid AND f.report_date = DATE_ADD(person_hb.date_in,INTERVAL 5 day) LIMIT 1) d6_fever,
            (SELECT q_sick_sign FROM followup_hb f WHERE f.cid = person_hb.cid AND f.report_date = DATE_ADD(person_hb.date_in,INTERVAL 5 day) LIMIT 1) d6_sick,
            (SELECT temp FROM followup_hb f WHERE f.cid = person_hb.cid AND f.report_date = DATE_ADD(person_hb.date_in,INTERVAL 5 day) LIMIT 1) d6_temp,
            (SELECT sick_sign FROM followup_hb f WHERE f.cid = person_hb.cid AND f.report_date = DATE_ADD(person_hb.date_in,INTERVAL 5 day) LIMIT 1) d6_ssign,
            (SELECT remark FROM followup_hb f WHERE f.cid = person_hb.cid AND f.report_date = DATE_ADD(person_hb.date_in,INTERVAL 5 day) LIMIT 1) d6_remark,

            DATE_ADD(person_hb.date_in,INTERVAL 6 day) d7,
            IF(DATE(NOW()) > DATE_ADD(person_hb.date_in,INTERVAL 5 day), '1', '0') d7_ended,
            (SELECT q_fever FROM followup_hb f WHERE f.cid = person_hb.cid AND f.report_date = DATE_ADD(person_hb.date_in,INTERVAL 6 day) LIMIT 1) d7_fever,
            (SELECT q_sick_sign FROM followup_hb f WHERE f.cid = person_hb.cid AND f.report_date = DATE_ADD(person_hb.date_in,INTERVAL 6 day) LIMIT 1) d7_sick,
            (SELECT temp FROM followup_hb f WHERE f.cid = person_hb.cid AND f.report_date = DATE_ADD(person_hb.date_in,INTERVAL 6 day) LIMIT 1) d7_temp,
            (SELECT sick_sign FROM followup_hb f WHERE f.cid = person_hb.cid AND f.report_date = DATE_ADD(person_hb.date_in,INTERVAL 6 day) LIMIT 1) d7_sign,
            (SELECT remark FROM followup_hb f WHERE f.cid = person_hb.cid AND f.report_date = DATE_ADD(person_hb.date_in,INTERVAL 6 day) LIMIT 1) d7_remark,

            DATE_ADD(person_hb.date_in,INTERVAL 7 day) d8,
            IF(DATE(NOW()) > DATE_ADD(person_hb.date_in,INTERVAL 6 day), '1', '0') d8_ended,
            (SELECT q_fever FROM followup_hb f WHERE f.cid = person_hb.cid AND f.report_date = DATE_ADD(person_hb.date_in,INTERVAL 7 day) LIMIT 1) d8_fever,
            (SELECT q_sick_sign FROM followup_hb f WHERE f.cid = person_hb.cid AND f.report_date = DATE_ADD(person_hb.date_in,INTERVAL 7 day) LIMIT 1) d8_sick,
            (SELECT temp FROM followup_hb f WHERE f.cid = person_hb.cid AND f.report_date = DATE_ADD(person_hb.date_in,INTERVAL 7 day) LIMIT 1) d8_temp,
            (SELECT sick_sign FROM followup_hb f WHERE f.cid = person_hb.cid AND f.report_date = DATE_ADD(person_hb.date_in,INTERVAL 7 day) LIMIT 1) d8_sign,
            (SELECT remark FROM followup_hb f WHERE f.cid = person_hb.cid AND f.report_date = DATE_ADD(person_hb.date_in,INTERVAL 7 day) LIMIT 1) d8_remark,

            DATE_ADD(person_hb.date_in,INTERVAL 8 day) d9,
            IF(DATE(NOW()) > DATE_ADD(person_hb.date_in,INTERVAL 7 day), '1', '0') d9_ended,
            (SELECT q_fever FROM followup_hb f WHERE f.cid = person_hb.cid AND f.report_date = DATE_ADD(person_hb.date_in,INTERVAL 8 day) LIMIT 1) d9_fever,
            (SELECT q_sick_sign FROM followup_hb f WHERE f.cid = person_hb.cid AND f.report_date = DATE_ADD(person_hb.date_in,INTERVAL 8 day) LIMIT 1) d9_sick,
            (SELECT temp FROM followup_hb f WHERE f.cid = person_hb.cid AND f.report_date = DATE_ADD(person_hb.date_in,INTERVAL 8 day) LIMIT 1) d9_temp,
            (SELECT sick_sign FROM followup_hb f WHERE f.cid = person_hb.cid AND f.report_date = DATE_ADD(person_hb.date_in,INTERVAL 8 day) LIMIT 1) d9_sign,
            (SELECT remark FROM followup_hb f WHERE f.cid = person_hb.cid AND f.report_date = DATE_ADD(person_hb.date_in,INTERVAL 8 day) LIMIT 1) d9_remark,

            DATE_ADD(person_hb.date_in,INTERVAL 9 day) d10,
            IF(DATE(NOW()) > DATE_ADD(person_hb.date_in,INTERVAL 8 day), '1', '0') d10_ended,
            (SELECT q_fever FROM followup_hb f WHERE f.cid = person_hb.cid AND f.report_date = DATE_ADD(person_hb.date_in,INTERVAL 9 day) LIMIT 1) d10_fever,
            (SELECT q_sick_sign FROM followup_hb f WHERE f.cid = person_hb.cid AND f.report_date = DATE_ADD(person_hb.date_in,INTERVAL 9 day) LIMIT 1) d10_sick,
            (SELECT temp FROM followup_hb f WHERE f.cid = person_hb.cid AND f.report_date = DATE_ADD(person_hb.date_in,INTERVAL 9 day) LIMIT 1) d10_temp,
            (SELECT sick_sign FROM followup_hb f WHERE f.cid = person_hb.cid AND f.report_date = DATE_ADD(person_hb.date_in,INTERVAL 9 day) LIMIT 1) d10_sign,
            (SELECT remark FROM followup_hb f WHERE f.cid = person_hb.cid AND f.report_date = DATE_ADD(person_hb.date_in,INTERVAL 9 day) LIMIT 1) d10_remark,

            DATE_ADD(person_hb.date_in,INTERVAL 10 day) d11,
            IF(DATE(NOW()) > DATE_ADD(person_hb.date_in,INTERVAL 9 day), '1', '0') d11_ended,
            (SELECT q_fever FROM followup_hb f WHERE f.cid = person_hb.cid AND f.report_date = DATE_ADD(person_hb.date_in,INTERVAL 10 day) LIMIT 1) d11_fever,
            (SELECT q_sick_sign FROM followup_hb f WHERE f.cid = person_hb.cid AND f.report_date = DATE_ADD(person_hb.date_in,INTERVAL 10 day) LIMIT 1) d11_sick,
            (SELECT temp FROM followup_hb f WHERE f.cid = person_hb.cid AND f.report_date = DATE_ADD(person_hb.date_in,INTERVAL 10 day) LIMIT 1) d11_temp,
            (SELECT sick_sign FROM followup_hb f WHERE f.cid = person_hb.cid AND f.report_date = DATE_ADD(person_hb.date_in,INTERVAL 10 day) LIMIT 1) d11_sign,
            (SELECT remark FROM followup_hb f WHERE f.cid = person_hb.cid AND f.report_date = DATE_ADD(person_hb.date_in,INTERVAL 10 day) LIMIT 1) d11_remark,

            DATE_ADD(person_hb.date_in,INTERVAL 11 day) d12,
            IF(DATE(NOW()) > DATE_ADD(person_hb.date_in,INTERVAL 10 day), '1', '0') d12_ended,
            (SELECT q_fever FROM followup_hb f WHERE f.cid = person_hb.cid AND f.report_date = DATE_ADD(person_hb.date_in,INTERVAL 11 day) LIMIT 1) d12_fever,
            (SELECT q_sick_sign FROM followup_hb f WHERE f.cid = person_hb.cid AND f.report_date = DATE_ADD(person_hb.date_in,INTERVAL 11 day) LIMIT 1) d12_sick,
            (SELECT temp FROM followup_hb f WHERE f.cid = person_hb.cid AND f.report_date = DATE_ADD(person_hb.date_in,INTERVAL 11 day) LIMIT 1) d12_temp,
            (SELECT sick_sign FROM followup_hb f WHERE f.cid = person_hb.cid AND f.report_date = DATE_ADD(person_hb.date_in,INTERVAL 11 day) LIMIT 1) d12_sign,
            (SELECT remark FROM followup_hb f WHERE f.cid = person_hb.cid AND f.report_date = DATE_ADD(person_hb.date_in,INTERVAL 11 day) LIMIT 1) d12_remark,

            DATE_ADD(person_hb.date_in,INTERVAL 12 day) d13,
            IF(DATE(NOW()) > DATE_ADD(person_hb.date_in,INTERVAL 11 day), '1', '0') d13_ended,
            (SELECT q_fever FROM followup_hb f WHERE f.cid = person_hb.cid AND f.report_date = DATE_ADD(person_hb.date_in,INTERVAL 12 day) LIMIT 1) d13_fever,
            (SELECT q_sick_sign FROM followup_hb f WHERE f.cid = person_hb.cid AND f.report_date = DATE_ADD(person_hb.date_in,INTERVAL 12 day) LIMIT 1) d13_sick,
            (SELECT temp FROM followup_hb f WHERE f.cid = person_hb.cid AND f.report_date = DATE_ADD(person_hb.date_in,INTERVAL 12 day) LIMIT 1) d13_temp,
            (SELECT sick_sign FROM followup_hb f WHERE f.cid = person_hb.cid AND f.report_date = DATE_ADD(person_hb.date_in,INTERVAL 12 day) LIMIT 1) d13_sign,
            (SELECT remark FROM followup_hb f WHERE f.cid = person_hb.cid AND f.report_date = DATE_ADD(person_hb.date_in,INTERVAL 12 day) LIMIT 1) d13_remark,

            DATE_ADD(person_hb.date_in,INTERVAL 13 day) d14,
            IF(DATE(NOW()) > DATE_ADD(person_hb.date_in,INTERVAL 12 day), '1', '0') d14_ended,
            (SELECT q_fever FROM followup_hb f WHERE f.cid = person_hb.cid AND f.report_date = DATE_ADD(person_hb.date_in,INTERVAL 13 day) LIMIT 1) d14_fever,
            (SELECT q_sick_sign FROM followup_hb f WHERE f.cid = person_hb.cid AND f.report_date = DATE_ADD(person_hb.date_in,INTERVAL 13 day) LIMIT 1) d14_sick,
            (SELECT temp FROM followup_hb f WHERE f.cid = person_hb.cid AND f.report_date = DATE_ADD(person_hb.date_in,INTERVAL 13 day) LIMIT 1) d14_temp,
            (SELECT sick_sign FROM followup_hb f WHERE f.cid = person_hb.cid AND f.report_date = DATE_ADD(person_hb.date_in,INTERVAL 13 day) LIMIT 1) d14_sign,
            (SELECT remark FROM followup_hb f WHERE f.cid = person_hb.cid AND f.report_date = DATE_ADD(person_hb.date_in,INTERVAL 13 day) LIMIT 1) d14_remark,
            IF(DATE(NOW()) > DATE_ADD(person_hb.date_in,INTERVAL 13 day),'1','0') AS pass14days
            FROM
            person_hb
                        LEFT JOIN ampur a ON person_hb.addr_ampur = a.ampurcodefull
                        LEFT JOIN tambon  ON person_hb.addr_tambon = tambon.tamboncodefull
                        LEFT JOIN village v ON CONCAT(person_hb.addr_tambon, person_hb.addr_vill_no) = v.villagecodefull
                LEFT JOIN province p ON person_hb.move_province = p.changwatcode
                LEFT JOIN ampur am ON person_hb.move_ampur = am.ampurcodefull
                LEFT JOIN tambon  t ON person_hb.move_tambon = t.tamboncodefull
           
LEFT JOIN (
                        SELECT f.* FROM (SELECT 
                                followup_hb.cid,
                                MAX(followup_hb.report_date) AS report_date
                                FROM 
                                followup_hb
                                GROUP BY
                                followup_hb.cid) a INNER JOIN followup_hb f ON a.cid = f.cid AND a.report_date = f.report_date
                                GROUP BY f.cid
                        ) l_follow ON l_follow.cid = person_hb.cid
                                                         
                       WHERE 
                        ".$condition2."
                        " . $condition . "


            GROUP BY cid
            ORDER BY person_hb.date_in DESC

            ")->queryAll();
            }, 120, null);
        }

        return $this->render('gethbdays', [
            'model' => $model,
            'person_data' => $person_data,
            'tambon' => $tambon,
            'village' => $village,
        ]);
    }


public function actionObserve()
    {
        $villagecode = '00';
        $condition = "";
        if ($villagecode == '0') {
            $condition = 'geo_village.villagecodefull IS NULL';
        } else {
            $condition = "CONCAT(person.addr_tambon, person.addr_vill_no) = '" . $villagecode . "'";
        }


        $connection = Yii::$app->db_covid;

        // $person_group = $connection->cache(function ($connection) use ($condition) {
        //     return $connection->createCommand("
        // SELECT
        //     geo_village.villagecodefull,
        //     geo_village.villagename,
        //     IF(a.tambonname IS NULL, '*รหัสเขตปกครองผิดพลาด', a.tambonname) AS tambonname,
        //     COUNT( * ) AS detected,
        //     SUM(IF(risk_korea_worker = '1',1,0)) AS risk_korea_worker,
        //     SUM(IF(risk_cambodia_border = '1',1,0)) AS risk_cambodia_border,
        //     0 AS q_close_to_case,
        //     SUM(IF(risk_from_bangkok = '1',1,0)) AS risk_from_bangkok,
        //     SUM(IF(q_from_risk_country = '1',1,0)) AS q_from_risk_country
        // FROM
        //     (SELECT * FROM person GROUP BY cid) person
        //     LEFT JOIN geo_village ON CONCAT( person.addr_tambon, person.addr_vill_no ) = geo_village.villagecodefull
        //     LEFT JOIN tambon a ON person.addr_ampur = a.ampurcode AND person.addr_tambon = a.tamboncodefull
        //         WHERE " . $condition . "
        // GROUP BY
        //     geo_village.villagecodefull
        //     ")->queryAll();
        // }, 180, null);



        //$dependency = new \yii\caching\DbDependency(['sql' => 'SELECT id FROM fever_update']);

        $village_data = $connection->cache(function ($connection) use ($villagecode) {
            return $connection->createCommand("
                SELECT
            person_sqc.*,
            v.villagecodefull,
                                    IF(v.`villagename` IS NULL,'รหัสผิดพลาด',CONCAT(v.`villagename`, ' ต.', tambon.tambonname)) AS vill_name,
            a.ampurname,
            p.changwatname,
            am.ampurname AS mampurname,
            t.tambonname,
            person_sqc.date_in AS d1,
            IF(DATE(NOW()) > person_sqc.date_in, '1', '0') d1_ended,
            IF(person_sqc.temp >= 37.5,'1','0') d1_fever,
            person_sqc.q_sick_sign d1_sick,
            person_sqc.temp d1_temp,
            person_sqc.sick_sign d1_sign,
            person_sqc.status d1_remark,
            
            DATE_ADD(person_sqc.date_in,INTERVAL 1 day) d2,
IF(DATE(NOW()) > person_sqc.date_in, '1', '0') d2_ended,
(SELECT q_fever FROM followup_sqc f WHERE f.cid = person_sqc.cid AND f.report_date = DATE_ADD(person_sqc.date_in,INTERVAL 1 day) LIMIT 1) d2_fever,
(SELECT q_sick_sign FROM followup_sqc f WHERE f.cid = person_sqc.cid AND f.report_date = DATE_ADD(person_sqc.date_in,INTERVAL 1 day) LIMIT 1) d2_sick,
(SELECT temp FROM followup_sqc f WHERE f.cid = person_sqc.cid AND f.report_date = DATE_ADD(person_sqc.date_in,INTERVAL 1 day) LIMIT 1) d2_temp,
(SELECT sick_sign FROM followup_sqc f WHERE f.cid = person_sqc.cid AND f.report_date = DATE_ADD(person_sqc.date_in,INTERVAL 1 day) LIMIT 1) d2_sign,
(SELECT remark FROM followup_sqc f WHERE f.cid = person_sqc.cid AND f.report_date = DATE_ADD(person_sqc.date_in,INTERVAL 1 day) LIMIT 1) d2_remark,

DATE_ADD(person_sqc.date_in,INTERVAL 2 day) d3,
IF(DATE(NOW()) > DATE_ADD(person_sqc.date_in,INTERVAL 1 day), '1', '0') d3_ended,
(SELECT q_fever FROM followup_sqc f WHERE f.cid = person_sqc.cid AND f.report_date = DATE_ADD(person_sqc.date_in,INTERVAL 2 day) LIMIT 1) d3_fever,
(SELECT q_sick_sign FROM followup_sqc f WHERE f.cid = person_sqc.cid AND f.report_date = DATE_ADD(person_sqc.date_in,INTERVAL 2 day) LIMIT 1) d3_sick,
(SELECT temp FROM followup_sqc f WHERE f.cid = person_sqc.cid AND f.report_date = DATE_ADD(person_sqc.date_in,INTERVAL 2 day) LIMIT 1) d3_temp,
(SELECT sick_sign FROM followup_sqc f WHERE f.cid = person_sqc.cid AND f.report_date = DATE_ADD(person_sqc.date_in,INTERVAL 2 day) LIMIT 1) d3_sign,
(SELECT remark FROM followup_sqc f WHERE f.cid = person_sqc.cid AND f.report_date = DATE_ADD(person_sqc.date_in,INTERVAL 2 day) LIMIT 1) d3_remark,

DATE_ADD(person_sqc.date_in,INTERVAL 3 day) d4,
IF(DATE(NOW()) > DATE_ADD(person_sqc.date_in,INTERVAL 2 day), '1', '0') d4_ended,
(SELECT q_fever FROM followup_sqc f WHERE f.cid = person_sqc.cid AND f.report_date = DATE_ADD(person_sqc.date_in,INTERVAL 3 day) LIMIT 1) d4_fever,
(SELECT q_sick_sign FROM followup_sqc f WHERE f.cid = person_sqc.cid AND f.report_date = DATE_ADD(person_sqc.date_in,INTERVAL 3 day) LIMIT 1) d4_sick,
(SELECT temp FROM followup_sqc f WHERE f.cid = person_sqc.cid AND f.report_date = DATE_ADD(person_sqc.date_in,INTERVAL 3 day) LIMIT 1) d4_temp,
(SELECT sick_sign FROM followup_sqc f WHERE f.cid = person_sqc.cid AND f.report_date = DATE_ADD(person_sqc.date_in,INTERVAL 3 day) LIMIT 1) d4_sign,
(SELECT remark FROM followup_sqc f WHERE f.cid = person_sqc.cid AND f.report_date = DATE_ADD(person_sqc.date_in,INTERVAL 3 day) LIMIT 1) d4_remark,

DATE_ADD(person_sqc.date_in,INTERVAL 4 day) d5,
IF(DATE(NOW()) > DATE_ADD(person_sqc.date_in,INTERVAL 3 day), '1', '0') d5_ended,
(SELECT q_fever FROM followup_sqc f WHERE f.cid = person_sqc.cid AND f.report_date = DATE_ADD(person_sqc.date_in,INTERVAL 4 day) LIMIT 1) d5_fever,
(SELECT q_sick_sign FROM followup_sqc f WHERE f.cid = person_sqc.cid AND f.report_date = DATE_ADD(person_sqc.date_in,INTERVAL 4 day) LIMIT 1) d5_sick,
(SELECT temp FROM followup_sqc f WHERE f.cid = person_sqc.cid AND f.report_date = DATE_ADD(person_sqc.date_in,INTERVAL 4 day) LIMIT 1) d5_temp,
(SELECT sick_sign FROM followup_sqc f WHERE f.cid = person_sqc.cid AND f.report_date = DATE_ADD(person_sqc.date_in,INTERVAL 4 day) LIMIT 1) d5_sign,
(SELECT remark FROM followup_sqc f WHERE f.cid = person_sqc.cid AND f.report_date = DATE_ADD(person_sqc.date_in,INTERVAL 4 day) LIMIT 1) d5_remark,

     
DATE_ADD(person_sqc.date_in,INTERVAL 5 day) d6,
IF(DATE(NOW()) > DATE_ADD(person_sqc.date_in,INTERVAL 4 day), '1', '0') d6_ended,
(SELECT q_fever FROM followup_sqc f WHERE f.cid = person_sqc.cid AND f.report_date = DATE_ADD(person_sqc.date_in,INTERVAL 5 day) LIMIT 1) d6_fever,
(SELECT q_sick_sign FROM followup_sqc f WHERE f.cid = person_sqc.cid AND f.report_date = DATE_ADD(person_sqc.date_in,INTERVAL 5 day) LIMIT 1) d6_sick,
(SELECT temp FROM followup_sqc f WHERE f.cid = person_sqc.cid AND f.report_date = DATE_ADD(person_sqc.date_in,INTERVAL 5 day) LIMIT 1) d6_temp,
(SELECT sick_sign FROM followup_sqc f WHERE f.cid = person_sqc.cid AND f.report_date = DATE_ADD(person_sqc.date_in,INTERVAL 5 day) LIMIT 1) d6_ssign,
(SELECT remark FROM followup_sqc f WHERE f.cid = person_sqc.cid AND f.report_date = DATE_ADD(person_sqc.date_in,INTERVAL 5 day) LIMIT 1) d6_remark,

DATE_ADD(person_sqc.date_in,INTERVAL 6 day) d7,
IF(DATE(NOW()) > DATE_ADD(person_sqc.date_in,INTERVAL 5 day), '1', '0') d7_ended,
(SELECT q_fever FROM followup_sqc f WHERE f.cid = person_sqc.cid AND f.report_date = DATE_ADD(person_sqc.date_in,INTERVAL 6 day) LIMIT 1) d7_fever,
(SELECT q_sick_sign FROM followup_sqc f WHERE f.cid = person_sqc.cid AND f.report_date = DATE_ADD(person_sqc.date_in,INTERVAL 6 day) LIMIT 1) d7_sick,
(SELECT temp FROM followup_sqc f WHERE f.cid = person_sqc.cid AND f.report_date = DATE_ADD(person_sqc.date_in,INTERVAL 6 day) LIMIT 1) d7_temp,
(SELECT sick_sign FROM followup_sqc f WHERE f.cid = person_sqc.cid AND f.report_date = DATE_ADD(person_sqc.date_in,INTERVAL 6 day) LIMIT 1) d7_sign,
(SELECT remark FROM followup_sqc f WHERE f.cid = person_sqc.cid AND f.report_date = DATE_ADD(person_sqc.date_in,INTERVAL 6 day) LIMIT 1) d7_remark,

DATE_ADD(person_sqc.date_in,INTERVAL 7 day) d8,
IF(DATE(NOW()) > DATE_ADD(person_sqc.date_in,INTERVAL 6 day), '1', '0') d8_ended,
(SELECT q_fever FROM followup_sqc f WHERE f.cid = person_sqc.cid AND f.report_date = DATE_ADD(person_sqc.date_in,INTERVAL 7 day) LIMIT 1) d8_fever,
(SELECT q_sick_sign FROM followup_sqc f WHERE f.cid = person_sqc.cid AND f.report_date = DATE_ADD(person_sqc.date_in,INTERVAL 7 day) LIMIT 1) d8_sick,
(SELECT temp FROM followup_sqc f WHERE f.cid = person_sqc.cid AND f.report_date = DATE_ADD(person_sqc.date_in,INTERVAL 7 day) LIMIT 1) d8_temp,
(SELECT sick_sign FROM followup_sqc f WHERE f.cid = person_sqc.cid AND f.report_date = DATE_ADD(person_sqc.date_in,INTERVAL 7 day) LIMIT 1) d8_sign,
(SELECT remark FROM followup_sqc f WHERE f.cid = person_sqc.cid AND f.report_date = DATE_ADD(person_sqc.date_in,INTERVAL 7 day) LIMIT 1) d8_remark,

DATE_ADD(person_sqc.date_in,INTERVAL 8 day) d9,
IF(DATE(NOW()) > DATE_ADD(person_sqc.date_in,INTERVAL 7 day), '1', '0') d9_ended,
(SELECT q_fever FROM followup_sqc f WHERE f.cid = person_sqc.cid AND f.report_date = DATE_ADD(person_sqc.date_in,INTERVAL 8 day) LIMIT 1) d9_fever,
(SELECT q_sick_sign FROM followup_sqc f WHERE f.cid = person_sqc.cid AND f.report_date = DATE_ADD(person_sqc.date_in,INTERVAL 8 day) LIMIT 1) d9_sick,
(SELECT temp FROM followup_sqc f WHERE f.cid = person_sqc.cid AND f.report_date = DATE_ADD(person_sqc.date_in,INTERVAL 8 day) LIMIT 1) d9_temp,
(SELECT sick_sign FROM followup_sqc f WHERE f.cid = person_sqc.cid AND f.report_date = DATE_ADD(person_sqc.date_in,INTERVAL 8 day) LIMIT 1) d9_sign,
(SELECT remark FROM followup_sqc f WHERE f.cid = person_sqc.cid AND f.report_date = DATE_ADD(person_sqc.date_in,INTERVAL 8 day) LIMIT 1) d9_remark,

DATE_ADD(person_sqc.date_in,INTERVAL 9 day) d10,
IF(DATE(NOW()) > DATE_ADD(person_sqc.date_in,INTERVAL 8 day), '1', '0') d10_ended,
(SELECT q_fever FROM followup_sqc f WHERE f.cid = person_sqc.cid AND f.report_date = DATE_ADD(person_sqc.date_in,INTERVAL 9 day) LIMIT 1) d10_fever,
(SELECT q_sick_sign FROM followup_sqc f WHERE f.cid = person_sqc.cid AND f.report_date = DATE_ADD(person_sqc.date_in,INTERVAL 9 day) LIMIT 1) d10_sick,
(SELECT temp FROM followup_sqc f WHERE f.cid = person_sqc.cid AND f.report_date = DATE_ADD(person_sqc.date_in,INTERVAL 9 day) LIMIT 1) d10_temp,
(SELECT sick_sign FROM followup_sqc f WHERE f.cid = person_sqc.cid AND f.report_date = DATE_ADD(person_sqc.date_in,INTERVAL 9 day) LIMIT 1) d10_sign,
(SELECT remark FROM followup_sqc f WHERE f.cid = person_sqc.cid AND f.report_date = DATE_ADD(person_sqc.date_in,INTERVAL 9 day) LIMIT 1) d10_remark,

DATE_ADD(person_sqc.date_in,INTERVAL 10 day) d11,
IF(DATE(NOW()) > DATE_ADD(person_sqc.date_in,INTERVAL 9 day), '1', '0') d11_ended,
(SELECT q_fever FROM followup_sqc f WHERE f.cid = person_sqc.cid AND f.report_date = DATE_ADD(person_sqc.date_in,INTERVAL 10 day) LIMIT 1) d11_fever,
(SELECT q_sick_sign FROM followup_sqc f WHERE f.cid = person_sqc.cid AND f.report_date = DATE_ADD(person_sqc.date_in,INTERVAL 10 day) LIMIT 1) d11_sick,
(SELECT temp FROM followup_sqc f WHERE f.cid = person_sqc.cid AND f.report_date = DATE_ADD(person_sqc.date_in,INTERVAL 10 day) LIMIT 1) d11_temp,
(SELECT sick_sign FROM followup_sqc f WHERE f.cid = person_sqc.cid AND f.report_date = DATE_ADD(person_sqc.date_in,INTERVAL 10 day) LIMIT 1) d11_sign,
(SELECT remark FROM followup_sqc f WHERE f.cid = person_sqc.cid AND f.report_date = DATE_ADD(person_sqc.date_in,INTERVAL 10 day) LIMIT 1) d11_remark,

DATE_ADD(person_sqc.date_in,INTERVAL 11 day) d12,
IF(DATE(NOW()) > DATE_ADD(person_sqc.date_in,INTERVAL 10 day), '1', '0') d12_ended,
(SELECT q_fever FROM followup_sqc f WHERE f.cid = person_sqc.cid AND f.report_date = DATE_ADD(person_sqc.date_in,INTERVAL 11 day) LIMIT 1) d12_fever,
(SELECT q_sick_sign FROM followup_sqc f WHERE f.cid = person_sqc.cid AND f.report_date = DATE_ADD(person_sqc.date_in,INTERVAL 11 day) LIMIT 1) d12_sick,
(SELECT temp FROM followup_sqc f WHERE f.cid = person_sqc.cid AND f.report_date = DATE_ADD(person_sqc.date_in,INTERVAL 11 day) LIMIT 1) d12_temp,
(SELECT sick_sign FROM followup_sqc f WHERE f.cid = person_sqc.cid AND f.report_date = DATE_ADD(person_sqc.date_in,INTERVAL 11 day) LIMIT 1) d12_sign,
(SELECT remark FROM followup_sqc f WHERE f.cid = person_sqc.cid AND f.report_date = DATE_ADD(person_sqc.date_in,INTERVAL 11 day) LIMIT 1) d12_remark,

DATE_ADD(person_sqc.date_in,INTERVAL 12 day) d13,
IF(DATE(NOW()) > DATE_ADD(person_sqc.date_in,INTERVAL 11 day), '1', '0') d13_ended,
(SELECT q_fever FROM followup_sqc f WHERE f.cid = person_sqc.cid AND f.report_date = DATE_ADD(person_sqc.date_in,INTERVAL 12 day) LIMIT 1) d13_fever,
(SELECT q_sick_sign FROM followup_sqc f WHERE f.cid = person_sqc.cid AND f.report_date = DATE_ADD(person_sqc.date_in,INTERVAL 12 day) LIMIT 1) d13_sick,
(SELECT temp FROM followup_sqc f WHERE f.cid = person_sqc.cid AND f.report_date = DATE_ADD(person_sqc.date_in,INTERVAL 12 day) LIMIT 1) d13_temp,
(SELECT sick_sign FROM followup_sqc f WHERE f.cid = person_sqc.cid AND f.report_date = DATE_ADD(person_sqc.date_in,INTERVAL 12 day) LIMIT 1) d13_sign,
(SELECT remark FROM followup_sqc f WHERE f.cid = person_sqc.cid AND f.report_date = DATE_ADD(person_sqc.date_in,INTERVAL 12 day) LIMIT 1) d13_remark,

DATE_ADD(person_sqc.date_in,INTERVAL 13 day) d14,
IF(DATE(NOW()) > DATE_ADD(person_sqc.date_in,INTERVAL 12 day), '1', '0') d14_ended,
(SELECT q_fever FROM followup_sqc f WHERE f.cid = person_sqc.cid AND f.report_date = DATE_ADD(person_sqc.date_in,INTERVAL 13 day) LIMIT 1) d14_fever,
(SELECT q_sick_sign FROM followup_sqc f WHERE f.cid = person_sqc.cid AND f.report_date = DATE_ADD(person_sqc.date_in,INTERVAL 13 day) LIMIT 1) d14_sick,
(SELECT temp FROM followup_sqc f WHERE f.cid = person_sqc.cid AND f.report_date = DATE_ADD(person_sqc.date_in,INTERVAL 13 day) LIMIT 1) d14_temp,
(SELECT sick_sign FROM followup_sqc f WHERE f.cid = person_sqc.cid AND f.report_date = DATE_ADD(person_sqc.date_in,INTERVAL 13 day) LIMIT 1) d14_sign,
(SELECT remark FROM followup_sqc f WHERE f.cid = person_sqc.cid AND f.report_date = DATE_ADD(person_sqc.date_in,INTERVAL 13 day) LIMIT 1) d14_remark,

            IF(DATE(NOW()) > DATE_ADD(person_sqc.date_in,INTERVAL 13 day),'1','0') AS pass14days,
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
            person_sqc
                        LEFT JOIN ampur a ON person_sqc.addr_ampur = a.ampurcodefull
                        LEFT JOIN tambon  ON person_sqc.addr_tambon = tambon.tamboncodefull
                        LEFT JOIN village v ON CONCAT(person_sqc.addr_tambon, person_sqc.addr_vill_no) = v.villagecodefull
                        LEFT JOIN province p ON person_sqc.move_province = p.changwatcode
                LEFT JOIN ampur am ON person_sqc.move_ampur = am.ampurcodefull
                LEFT JOIN tambon  t ON person_sqc.move_tambon = t.tamboncodefull
                LEFT JOIN person_pui pui ON person_sqc.cid = pui.cid

                        LEFT JOIN (
                        SELECT f.* FROM (SELECT 
                                followup_sqc.cid,
                                MAX(followup_sqc.report_date) AS report_date
                                FROM 
                                followup_sqc
                                GROUP BY
                                followup_sqc.cid) a INNER JOIN followup_sqc f ON a.cid = f.cid AND a.report_date = f.report_date
                                GROUP BY f.cid
                        ) l_follow ON l_follow.cid = person_sqc.cid
            WHERE 
#l_follow.temp >= 37.5 OR (person_sqc.temp >= 37.5 AND l_follow.temp IS NULL)  AND person_sqc.status NOT IN ('0', '1')

                        person_sqc.status NOT IN ('0', '1') OR person_sqc.status IS NULL

           
            GROUP BY person_sqc.cid
            ORDER BY person_sqc.addr_ampur
            
            ")->queryAll();
        }, 180, null);




       


        $sumary = [];
        $calendar = [];
        $person_group = [];


        return $this->render('observe', [
            'village_data' => $village_data,
            'sumary' => $sumary,
            'calendar' => $calendar,
            'person_group' => $person_group,
        ]);
    }



}
