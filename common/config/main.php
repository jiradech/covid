<?php
use \kartik\datecontrol\Module;

return [
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'timeZone' => 'Asia/Bangkok',
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'language'=>'th',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'thai' => [

            'class' => 'common\components\ThaiHelper',

        ],

    ],
 'modules' => [

        // 'gridview' => [
        //     'class' => '\kartik\grid\Module'
        //     // enter optional module parameters below - only if you need to
        //     // use your own export download action or custom translation
        //     // message source
        //     // 'downloadAction' => 'gridview/export/download',
        //     // 'i18n' => []
        // ],
        'datecontrol' => [
            'class' => 'kartik\datecontrol\Module',

            //'language'=> 'th',

            // format settings for displaying each date attribute
            'displaySettings' => [
                'date' => 'd MMMM yyyy',
                'time' => 'hh:mm:ss',
                'datetime' => 'dd MMMM yyyy hh:mm:ss',
            ],


            // format settings for saving each date attribute
            'saveSettings' => [
                'date' => 'php:Y-m-d',
                'time' => 'php:H:i:s',
                'datetime' => 'php:Y-m-d H:i:s',
            ],

            // set your display timezone
            'displayTimezone' => 'Asia/Bangkok',

            // set your timezone for date saved to db
            'saveTimezone' => 'Asia/Bangkok',
            // automatically use kartik\widgets for each of the above formats
            'autoWidget' => true,

            'autoWidgetSettings' => [
                Module::FORMAT_DATE => ['type'=>2, 'pluginOptions'=>['autoclose'=>true]], // example
                Module::FORMAT_DATETIME => [], // setup if needed
                Module::FORMAT_TIME => [], // setup if needed
            ],

        ],


    ],       
];
