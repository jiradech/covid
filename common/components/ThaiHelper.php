<?php
namespace common\components;


use Yii;
use yii\base\Component;
use yii\base\InvalidConfigException;



class ThaiHelper extends Component
{

    public function thaidate($format = '', $timestamp = '', $be = true)
    {
        if ($timestamp == null) {
            $timestamp = time();
        }
        // month values
        $en_month_long = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
        $en_month_short = array('Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec');
        $th_month_long = array('มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน', 'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม');
        $th_month_short = array('ม.ค.', 'ก.พ.', 'มี.ค.', 'เม.ย.', 'พ.ค.', 'มิ.ย.', 'ก.ค.', 'ส.ค.', 'ก.ย.', 'ต.ค.', 'พ.ย.', 'ธ.ค.');
        // day values
        $en_day_long = array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');
        $en_day_short = array('Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat');
        $th_day_long = array('อาทิตย์', 'จันทร์', 'อังคาร', 'พุธ', 'พฤหัสบดี', 'ศุกร์', 'เสาร์');
        $th_day_short = array('อา.', 'จ.', 'อ.', 'พ.', 'พฤ.', 'ศ.', 'ส.');
        // convert year to buddha era (rg. 2554, 2555, 2556)?
        if ($be == true) {
            if (mb_strpos($format, 'o') !== false) {
                $year = (date('o', $timestamp) + 543);
                $format = str_replace('o', $year, $format);
            } elseif (mb_strpos($format, 'Y') !== false) {
                $year = (date('Y', $timestamp) + 543);
                $format = str_replace('Y', $year, $format);
            } elseif (mb_strpos($format, 'y') !== false) {
                $year = (date('y', $timestamp) + 43);
                $format = str_replace('y', $year, $format);
            }
            unset($year);
        }
        // replace eng to thai from long to short
        $thaidate = date($format, $timestamp);
        if (mb_strpos($format, 'F') !== false) {
            $thaidate = str_replace($en_month_long, $th_month_long, $thaidate);
        } else {
            $thaidate = str_replace($en_month_short, $th_month_short, $thaidate);
        }
        $thaidate = str_replace($en_day_long, $th_day_long, $thaidate);
        $thaidate = str_replace($en_day_short, $th_day_short, $thaidate);
        unset($en_month_long, $en_month_short, $th_month_long, $th_month_short, $en_day_long, $en_day_short, $th_day_long, $th_day_short);
        return $thaidate;
    }// thaidate
}