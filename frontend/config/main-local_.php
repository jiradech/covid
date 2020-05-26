<?php

$config = [
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'g7wrBiiC9AkJapwvluUBUKJ7H5Cbt0ut',
        ],
            'db_covid' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=covid19',
            'username' => 'root',
            'password' => 'zjkowfh!@#',
            'charset' => 'utf8',
        ],
        'view' => [
            'theme' => [
                'basePath' => '@app/themes/covid-19/',
                'baseUrl' => '@web/template/covid-19/',
                'pathMap' => [
                    '@app/views' => '@app/themes/covid-19',
                ],
            ],
        ],

    ],
];

if (!YII_ENV_TEST) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
}

return $config;
