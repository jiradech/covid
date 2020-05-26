<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\PersonSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'โรงแรมและกิจการให้เช่าที่พัก';
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

    <h3><?= Html::encode($this->title) ?></h3>

    <p>
        <?= Html::a('Create Person', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

         //   'id',
                       // [ // รวมคอลัมน์
                    //   'label'=>'เลขบัตรประจำตัวประชาชน',
                    //   'format'=>'html',
                    //   'value'=>function($model, $key, $index, $column){
                    //     return $model->cid;
                    //   }
                    // ],
            'cid',
           // 'prename',
            'fname',
            'lname',
            'age',
            'sex',
            //'occupation',
            //'phone_number',
            //'date_in',
            [
                //'label' => 'xxxx',
                'attribute' => 'report_date',
                //'valueColOptions'=>['style'=>'width:40%'],
                'value' => function ($model) {
                    return  Yii::$app->thai->thaidate('d F Y', strtotime($model['date_in']));
                },
            ],
            //'addr_number',
            //'addr_vill_no',
            //'addr_tambon',
            //'addr_ampur',
            //'addr_province',
            'nation',
            //'house_type',
            //'c_family',
            //'q_from_risk_country',
            //'q_close_to_case',
            //'risk_from_risk_country',
            //'risk_korea_worker',
            //'risk_cambodia_border',
            //'risk_from_bangkok',
            //'q_family_from_risk_country',
            //'q_close_to_foreigner',
            //'q_healthcare_staff',
            //'q_close_to_group_fever',
            //'risk_place',
            //'risk_group_place',
            //'risk_case_place',
            //'note:ntext',
            'reporter_name',
            'reporter_phone',
            //'date_stamp',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
    
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- corona count section ending here --> 


