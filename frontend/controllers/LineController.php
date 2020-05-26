<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;



class LineController extends Controller
{
    public function actionNotify()
    {
        $connection = Yii::$app->db_covid;

        $datas = $connection->createCommand("
            SELECT 
            ampur.ampurcodefull,ampur.ampurname,
            IFNULL(lost.n, 0) as num
            FROM ampur
            LEFT JOIN 
            (
            SELECT
                        person.addr_ampur,
                        COUNT(DISTINCT person.cid) as n
                        FROM
                        person
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
                            ((TIMESTAMPDIFF(day,l_follow.report_date,NOW()) > 1 OR (person.date_in < ADDDATE(person.date_in,INTERVAL 13 day) AND TIMESTAMPDIFF(day,person.date_in,NOW()) > 1 AND l_follow.report_date IS NULL)))
                            AND ((l_follow.report_date < ADDDATE(person.date_in,INTERVAL 13 day ) OR (person.date_in < ADDDATE(person.date_in,INTERVAL 13 day) AND TIMESTAMPDIFF(day,person.date_in,NOW()) > 0 AND l_follow.report_date IS NULL)))
                            AND (person.`status` IS NULL OR person.`status` NOT IN ('0', '1', '5') )
                            GROUP BY  person.addr_ampur
                ) lost ON  lost.addr_ampur =ampur.ampurcodefull
            WHERE
            ampur.changwatcode=27
            GROUP BY
            ampur.ampurcodefull

            ")->queryAll();

        $message = "แจ้งจำนวนบุคคลขาดการติดตามเฝ้าระวังเกินกว่า 2 วัน ณ " . Yii::$app->thai->thaidate('d F Y H:i น.', time()) . "\r\n\r\n";
        foreach ($datas as $rows) {
            $message .= "อ." . $rows['ampurname'] . " " . $rows['num'] . " คน\r\n";
        }
        echo $message;

        $this->notify_message($message, '7jcYC0kSQm1vBOBpGoxAaEYKibMOR1OVwTI9vxW1rsV');
    }
    //กลุ่ม COVID19 7jcYC0kSQm1vBOBpGoxAaEYKibMOR1OVwTI9vxW1rsV
    //กลุ่ม Hardcore mFOiOeNrlEAZc7R82JiWT71rtcyA6W4PC2K4gE2bZiC


    public function actionTodayFollowup()
    {
        $connection = Yii::$app->db_covid;

        $datas = $connection->createCommand("
            SELECT 
            ampur.ampurcodefull,ampur.ampurname,
            IFNULL(lost.n, 0) as num
            FROM ampur
            LEFT JOIN 
            (
            SELECT
                        person.addr_ampur,
                        COUNT(DISTINCT person.cid) as n
                        FROM
                        person
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
                            ((TIMESTAMPDIFF(day,l_follow.report_date,NOW()) > 0 OR (person.date_in < ADDDATE(person.date_in,INTERVAL 13 day) AND TIMESTAMPDIFF(day,person.date_in,NOW()) > 0 AND l_follow.report_date IS NULL)))
                            AND ((l_follow.report_date < ADDDATE(person.date_in,INTERVAL 13 day ) OR (person.date_in < ADDDATE(person.date_in,INTERVAL 13 day) AND TIMESTAMPDIFF(day,person.date_in,NOW()) > 0 AND l_follow.report_date IS NULL)))
                            AND (person.`status` IS NULL OR person.`status` NOT IN ('0', '1', '5') )
                            GROUP BY  person.addr_ampur
                ) lost ON  lost.addr_ampur =ampur.ampurcodefull
            WHERE
            ampur.changwatcode=27
            GROUP BY
            ampur.ampurcodefull

            ")->queryAll();

        $message = "แจ้งจำนวนบุคคลที่ต้องติดตามเฝ้าระวังวันนี้ ณ " . Yii::$app->thai->thaidate('d F Y H:i น.', time()) . "\r\n\r\n";
        foreach ($datas as $rows) {
            $message .= "อ." . $rows['ampurname'] . " " . $rows['num'] . " คน\r\n";
        }
        echo $message;

        $this->notify_message($message, '7jcYC0kSQm1vBOBpGoxAaEYKibMOR1OVwTI9vxW1rsV');
    }


    public function actionNotifyDayS()
    {
        $connection = Yii::$app->db_covid;

        $datas_prov = $connection->createCommand("
          SELECT 
            COUNT(DISTINCT person.cid) AS total,
            COUNT(DISTINCT IF(person.date_in = DATE(NOW()) ,person.cid,NULL)) AS today,
            COUNT(DISTINCT person.cid) AS total_followed,
            COUNT(DISTINCT IF((person.status NOT IN ('0', '1') AND y.temp >= 37.5) OR (person.status NOT IN ('0', '1') AND person.temp >= 37.5 AND y.temp  IS NULL), person.cid,NULL)) AS one_sign,
            COUNT(DISTINCT IF(DATE(NOW()) > DATE_ADD(person.date_in,INTERVAL 13 day),person.cid,NULL)) AS pass14days
                        
            FROM (SELECT * FROM person GROUP BY cid) person
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







        $datas_amphur = $connection->createCommand("
          SELECT
            IF(daily.addr_ampur ='','รหัสผิดพลาด',ampur.ampurname) AS aname,


            COUNT(DISTINCT daily.cid) AS total,
            COUNT(DISTINCT IF(daily.date_in = DATE(NOW()) ,daily.cid,NULL)) AS today,
            COUNT(DISTINCT daily.cid) AS total_followed,

            COUNT(DISTINCT IF((daily.status NOT IN ('0', '1') AND daily.temp >= 37.5) OR (daily.status NOT IN ('0', '1') AND daily.temp >= 37.5 AND daily.temp  IS NULL), daily.cid,NULL)) AS one_sign,

            COUNT(DISTINCT IF(daily.temp >= 37.5 OR (daily.temp >= 37.5 AND daily.temp  IS NULL), daily.cid,NULL)) AS one_sign_old,
            COUNT(DISTINCT IF(DATE(NOW()) > DATE_ADD(daily.date_in,INTERVAL 13 day),daily.cid,NULL)) AS pass14days

            FROM ampur
            RIGHT JOIN 
            (
            SELECT 
            person.fname,person.lname,
            person.cid,person.addr_ampur,
            person.date_in,
            y.temp,person.status
                        
            FROM (SELECT * FROM person GROUP BY cid) person
            LEFT JOIN
                (SELECT f.cid, f.q_fever, f.temp, f.q_sick_sign FROM (SELECT 
                            followup.cid,
                            MAX(followup.report_date) AS report_date
                            FROM 
                            followup
                            GROUP BY
                            followup.cid) a INNER JOIN followup f ON a.cid = f.cid AND a.report_date = f.report_date
                            GROUP BY f.cid) y ON y.cid = person.cid
                            
                        
) daily ON  daily.addr_ampur = ampur.ampurcodefull


GROUP BY
daily.addr_ampur


            ")->queryAll();

        $message = "สรุปสถานการณ์ประชากรเคลื่อนย้าย  " . Yii::$app->thai->thaidate('d F Y H:i น.', time()) . "\r\n\r\n";
        foreach ($datas_amphur as $rows) {
            $message .= "อ." . $rows['aname'] . " เข้าพื้นที่วันนี้ " . $rows['today'] . " เฝ้าระวัง " . ($rows['total'] - $rows['pass14days']) . " มีไข้ " . $rows['one_sign'] . "\r\n\r\n";
        }
        $message2 = "รวมจังหวัด  เข้าพื้นที่วันนี้ " . number_format($datas_prov[0]['today']) .
            " เฝ้าระวัง " . number_format($datas_prov[0]['total'] - $datas_prov[0]['pass14days']) .
            " มีไข้ " . number_format($datas_prov[0]['one_sign']) . "\r\n\r\n";
        echo $message . $message2;
        //number_format($covid[0]['cure'])           
        $this->notify_message($message . $message2, '7jcYC0kSQm1vBOBpGoxAaEYKibMOR1OVwTI9vxW1rsV');
    }
    //กลุ่ม COVID19 7jcYC0kSQm1vBOBpGoxAaEYKibMOR1OVwTI9vxW1rsV
    //กลุ่ม Hardcore mFOiOeNrlEAZc7R82JiWT71rtcyA6W4PC2K4gE2bZiC 

public function actionNotifyDayPos()
    {
        $connection = Yii::$app->db_covid;
        $datas_puiday = $connection->createCommand
                ("
                  SELECT
COUNT(DISTINCT IF(pui_lab.pcr_send_date = DATE(NOW()), person_pui.pui_code,NULL)) as testD,
COUNT(DISTINCT person_pui.pui_code) as testC,
COUNT(DISTINCT IF(pui_lab.pcr_result LIKE '%Detected%' AND pui_lab.pcr_date = NOW(), person_pui.pui_code,NULL)) as posD,
COUNT(DISTINCT IF(pui_lab.pcr_result LIKE '%Detected%', person_pui.pui_code,NULL)) as posC,
COUNT(DISTINCT IF(person_pui.follow_status LIKE '%รักษาหาย%', person_pui.pui_code,NULL)) as cure,
COUNT(DISTINCT IF(pui_lab.pcr_result LIKE '%Detected%' AND person_pui.follow_status LIKE 'อยุ่ระหว่างการรักษา', person_pui.pui_code,NULL)) as treat,
COUNT(DISTINCT IF(pui_lab.pcr_result LIKE '%Detected%' AND person_pui.follow_status LIKE 'เสียชีวิต', person_pui.pui_code,NULL)) as dead
                  FROM
                  person_pui
                  INNER JOIN pui_lab ON person_pui.pui_code = pui_lab.pui_code

                ")->queryAll();

$message3 = "สรุปอัตราการพบผู้ป่วยรายใหม่ประจำวันที่ ".Yii::$app->thai->thaidate('d F Y H:i น.', time())."\r\n".
            " - ส่งตรวจเชื้อ ".$datas_puiday[0]['testD']."\r\n".
            " - ส่งตรวจเชื้อสะสม ".$datas_puiday[0]['testC']."\r\n".
            " - Positive ".$datas_puiday[0]['posD']."\r\n".
            " - Positive สะสม ".$datas_puiday[0]['posC']."\r\n".
            " - หายแล้ว ".$datas_puiday[0]['cure']."\r\n".
            " - รักษาอยู่ใน รพ. ".$datas_puiday[0]['treat']."\r\n".
            " - เสียชีวิต ".$datas_puiday[0]['dead']."\r\n"
            ;

echo $message3;
 //number_format($covid[0]['cure'])           
        $this->notify_message($message3,'7jcYC0kSQm1vBOBpGoxAaEYKibMOR1OVwTI9vxW1rsV');
                    //กลุ่ม COVID19 7jcYC0kSQm1vBOBpGoxAaEYKibMOR1OVwTI9vxW1rsV
                    //กลุ่ม Hardcore mFOiOeNrlEAZc7R82JiWT71rtcyA6W4PC2K4gE2bZiC
} 


    public function notify_message($message, $token)
    {
        $line_api = 'https://notify-api.line.me/api/notify';
        $line_token = $token;
        // 'vhWpSdQoE53kAGmssUuFVFV4c1W2HYc2GoY8MzDGkf8' คปสจ.
        // PV18y6G9EnJJH5Rv4iU83sJ0c89BwplCKxTORBaRqbv  ไอทีสาสุขs
        $queryData = array('message' => $message);
        $queryData = http_build_query($queryData, '', '&');
        $headerOptions = array(
            'http' => array(
                'method' => 'POST',
                'header' => "Content-Type: application/x-www-form-urlencoded\r\n"
                    . "Authorization: Bearer " . $line_token . "\r\n"
                    . "Content-Length: " . strlen($queryData) . "\r\n",
                'content' => $queryData
            )
        );
        $context = stream_context_create($headerOptions);
        $result = file_get_contents($line_api, FALSE, $context);
        $res = json_decode($result);
        return $res;
    }
}
