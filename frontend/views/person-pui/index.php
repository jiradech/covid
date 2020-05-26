<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\PersonpuiSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'รายชื่อ PUI';
$this->params['breadcrumbs'][] = $this->title;
?>
<!-- Page Header Section Start Here -->
        <section class="page-header" style="padding: 150px 0 0px;">
            <div class="page-header-shape">
                <img src="<?php echo $this->theme->baseUrl ?>/assets/images/banner/home-2/01.jpg" alt="banner-shape">
            </div>

        </section>
        <!-- Page Header Section Ending Here -->
        
        <!-- corona count section start here -->
        <section class="corona-count-section pt-0 padding-tb">
            <div class="container">
                <div class="corona-wrap">
                    <div class="countcorona">
                        
                        <div class="countcorona-area">

    <h4><?= Html::encode($this->title) ?></h4>

    <p>
        <?= Html::a('เพิ่มเคส Pui', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],

            'sort_order',
            'pui_code',
            //'referal_no',
            //'pui_case',
            //'pui',
            //'pui_contact',
            'cid',
            'full_name',
            'sex',
            'age',
            //'nation',
            //'occupation',
            //'addr_no',
            //'addr_villno',
            //'addr_villname',
            //'addr_tambon',
            //'addr_amphur',
            //'addr_province',
            //'villcode',
            //'tamboncode',
            //'amphurcode',
            //'provincecode',
            // 'sick_date',
            [
                //'label' => 'xxxx',
                'attribute' => 'report_date',
                //'valueColOptions'=>['style'=>'width:40%'],
                'value' => function ($model) {
                    return  Yii::$app->thai->thaidate('d F Y', strtotime($model['sick_date']));
                },
            ],
            //'detect_date',
            // 'report_date',
            [
                //'label' => 'xxxx',
                'attribute' => 'report_date',
                //'valueColOptions'=>['style'=>'width:40%'],
                'value' => function ($model) {
                    return  Yii::$app->thai->thaidate('d F Y', strtotime($model['report_date']));
                },
            ],
            //'report_time',
            //'reporter_name',
            //'reporter_phone',
            //'receiver_name',
            //'receiver_phone',
            //'admit_hosp',
            //'sample_place',
            //'sample_type',
            //'pcr_send_date',
            //'pcr_result',
            //'pcr_date',
            //'pcr_time',
            //'discharge_result',
            //'final_dx',
            //'discharge_date',
            'follow_status',
            //'tracking_status',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


<!-- corona count section ending here --> 
                            </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- corona count section ending here --> 
