<?php

use yii\helpers\Html;
//use yii\widgets\DetailView;
use kartik\detail\DetailView;
use app\models\Sex;
use yii\helpers\Url;
use kartik\datecontrol\DateControl;
use yii\helpers\ArrayHelper;
use yii\grid\GridView;
/* @var $this yii\web\View */
/* @var $model app\models\Person */


$this->title = $model->fname . ' ' . $model->lname;
//$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'People', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);


$discharge = [
    '0' => 'ติดตามไม่ได้',
    '1' => 'ย้ายออกนอกพื้นที่',
    '2' => 'ยังอยู่ในพื้นที่ และทำ Home Quarantine',
    '3' => 'ยังอยู่ในพื้นที่ ฝ่าฝืนหรือไม่ทำ Home Quarantine',
    '4' => 'ยังอยู่ในพื้นที่ Local Quarantine',
    '5' => 'ส่ง รพ.'
];

?>
<style>
    .btn {
        height: 50px;
        padding-left: 20px;
        padding-right: 20px;
        padding-top: 12px;
    }

    @media (max-width: 991px) {
        .datepicker-dropdown {
            font-size: 20px;
        }

        .btn {
            width: 100%;
            height: 50px;
            margin-bottom: 10px;
        }
    }
</style>

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

                    <h3><?= Html::encode($this->title) ?> </h3>
                    
                    <?php
                        if ($personsqc_dup > 1) {
                    ?>
<a href="<?=Url::to(['person-sqc/index', 'PersonSearch[cid]' => $model->cid, 'PersonSearch[date_in]' => $model->date_in])?>"><h5 style="color: red">พบมีการบันทึกคนซ้ำในระบบ <?=$personsqc_dup?> ราย กรุณาตรวจสอบ [คลิก]</h5></a>

                    <?php
                        }
                    ?>
                    <?= $model->status ? '<h5>' . $discharge[$model->status] . ' (สถานะการติดตาม)</h5>' : '' ?>
                    <p>
                        <?= Html::a('เพิ่มข้อมูลผู้เดินทางเข้าจังหวัดสระแก้ว', ['person-sqc/create'], ['class' => 'btn btn-success']) ?>
                        <?= Html::a('แก้ไขข้อมูล', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
                        <?= Html::a('ลบ', ['delete', 'id' => $model->id], [
                            'class' => 'btn btn-danger',
                            'data' => [
                                'confirm' => 'Are you sure you want to delete this item?',
                                'method' => 'post',
                            ],
                        ]) ?>
                    </p>


                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h4 class="panel-title"><i class="fa fa-user"></i> ข้อมูล</h4>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-4">เลขประจำตัวประชาชน : <font color="#ooooFF"> <?= $model->cid; ?></font>
                                </div>
                                <div class="col-md-4">ชื่อ - นามสกุล : <?= $model->fname . ' ' . $model->lname; ?> </div>
                                <div class="col-md-2">เพศ : <?= $model->sex; ?></div>
                                <div class="col-md-4">อายุ : <?= $model->age; ?> ปี</div>
                                <div class="col-md-4">สัญชาติ : <?= ArrayHelper::getValue($model->getNationArray($model->nation), $model->nation, 'ไม่ระบุ') ?></div>
                                <div class="col-md-4">อาชีพ : <?= $model->occupation; ?></div>
                                <div class="col-md-4">เบอร์โทรศัพท์ : <?= $model->phone_number; ?></div>
                            </div>
                           <!--  <div class="row">
                                <div class="col-md-12">ที่อยู่ : บ้านเลขที่ <?= $model->addr_number; ?>
                                    หมู่ <?= ArrayHelper::getValue($model->getVillageArray($model->addr_tambon . $model->addr_vill_no), $model->addr_tambon . $model->addr_vill_no, 'ไม่ระบุ') ?>
                                    ตำบล<?= ArrayHelper::getValue($model->getTambonArray($model->addr_tambon), $model->addr_tambon, 'ไม่ระบุ') ?>
                                    อำเภอ<?= ArrayHelper::getValue($model->getAmphurArray($model->addr_ampur), $model->addr_ampur, 'ไม่ระบุ') ?>
                                    จังหวัดสระแก้ว</div>
                                <div class="col-md-4"> <?= $model->remark ? 'Local Quarantine: ' . $model->remark : '' ?> </div>


                            </div> -->
                        </div>
                    </div>

                    <?= DetailView::widget([
                        'model' => $model,
                        'responsive' => false,
                        'condensed' => true,
                        'hover' => true,
                        'bordered' => true,
                        'striped' => true,
                        'mode' => DetailView::MODE_VIEW,
                        'hAlign' => DetailView::ALIGN_LEFT,

                        'panel' => [
                            'heading' => '<i class="fa fa-user"></i> Information',
                            'type' => DetailView::TYPE_PRIMARY,
                        ],
                        'attributes' => [

                            //  'id',

                            //'cid',
                            //  'prename',

                            //'fname',
                            // 'lname',
                            //'age',
                            // [
                            //     'attribute' => 'PROVIDER',
                            //     'label' => 'อสม.',
                            //     'value' => function ($d) {
                            //         return Sex::getFullname($d['sex'] );
                            //     }

                            // ],
                            //'sex',
                            //'occupation',
                            //'phone_number',
                            //'addr_number',
                            //'addr_vill_no',
                            // 'addr_tambon',
                            //'addr_ampur',
                            // 'addr_province',
                            //'nation',

                            [
                                'label' => 'วันที่ เข้า-ออก',
                                'attribute' => 'date_in',
                                'valueColOptions' => ['style' => 'width:40%'],
                                'value' => Yii::$app->thai->thaidate('d F Y', strtotime($model->date_in)).' ถึง '.Yii::$app->thai->thaidate('d F Y', strtotime($model->date_out))

                            ],
                            



                            [
                                'attribute' => 'เดินทางมาจากจังหวัด',
                                'value' =>
                                'ตำบล' . ArrayHelper::getValue($model->getTambonArray($model->move_tambon), $model->move_tambon, 'ไม่ระบุ') . ' อำเภอ' . ArrayHelper::getValue($model->getAmphurArray($model->move_ampur), $model->move_ampur, 'ไม่ระบุ') . ' จังหวัด' . ArrayHelper::getValue($model->getProvinceArray($model->move_province), $model->move_province, 'ไม่ระบุ')

                            ],
                            [
                                'attribute' => 'เข้าพักอาศัยในเขตพื้นที่จังหวัดสระแก้ว',
                                'value' =>
                                $model->addr_number.' หมู่ '.
                                ArrayHelper::getValue($model->getVillageArray($model->addr_tambon . $model->addr_vill_no), $model->addr_tambon . $model->addr_vill_no, 'ไม่ระบุ').' ตำบล'.ArrayHelper::getValue($model->getTambonArray($model->addr_tambon), $model->addr_tambon, 'ไม่ระบุ').' อำเภอ'.ArrayHelper::getValue($model->getAmphurArray($model->addr_ampur), $model->addr_ampur, 'ไม่ระบุ').' จังหวัดสระแก้ว '

                            ],
                            //   'move_province',
                            //   'move_ampur' ,
                            //   'move_tambon' ,
                            //'house_type',
                            // [
                            //     'attribute' => 'house_type',
                            //     'value' => ArrayHelper::getValue($model->getTypeHomeArray($model->house_type), $model->house_type, 'ไม่ระบุ')
                            // ],

                            //'c_family',
                            
                            [
                                //'label' => 'xxxx',
                                'attribute' => 'temp',
                                'valueColOptions' => ['style' => 'color:' . ($model->temp >= 37.5 ? 'red' : 'green')],
                                'value' => $model->temp

                            ],
                            [
                                'attribute' => 'risk_from_risk_country',
                                'value' => $model->risk_from_risk_country
                            ],
                       
                            [
                                'attribute' => 'q_from_risk_country',
                                'format' => 'raw',
                                'value' => $model->q_from_risk_country ? '<span class="label label-danger">ใช่</span>' : '<span class="label label-success">ไม่ใช่</span>',
                            ],
                            [
                                'attribute' => 'q_close_to_case',
                                'format' => 'raw',
                                'value' => $model->q_close_to_case ? '<span class="label label-danger">ใช่</span>' : '<span class="label label-success">ไม่ใช่</span>',
                            ],
                            
                            [
                                'attribute' => 'q_close_to_foreigner',
                                'format' => 'raw',
                                'value' => $model->q_close_to_foreigner ? '<span class="label label-danger">ใช่</span>' : '<span class="label label-success">ไม่ใช่</span>',
                            ],
                            
                            
                            
                            [
                                'attribute' => 'risk_place',
                                'format' => 'raw',
                                'value' => $model->risk_place ? '<span class="label label-danger">ใช่</span>' : '<span class="label label-success">ไม่ใช่</span>',
                            ],
                            //'risk_place',
                            [
                                'attribute' => 'risk_group_place',
                                'format' => 'raw',
                                'value' => $model->risk_group_place ? '<span class="label label-danger">ใช่</span>' : '<span class="label label-success">ไม่ใช่</span>',
                            ],
                            //'risk_group_place',
                           
                            //'risk_case_place',
                            'remark',
                            [
                                'attribute' => 'note',

                                'format' => 'raw',
                                'value' => '<span class="text-justify"><em>' . $model->note . '</em></span>',
                                'type' => DetailView::INPUT_TEXTAREA,

                                'options' => ['rows' => 4],
                                'valueColOptions' => ['style' => 'width:40%'],
                            ],
                            //'note:ntext',
                            'reporter_name',
                            'reporter_phone',

                            //'date_stamp',
                            [
                                //'label' => '',
                                'attribute' => 'date_stamp',
                                'valueColOptions' => ['style' => 'width:40%'],
                                'value' => Yii::$app->thai->thaidate('d F Y', strtotime($model->date_stamp))


                            ],

                        ],
                        'deleteOptions' => [
                            'url' => ['delete', 'id' => $model->id],
                            'data' => [
                                'confirm' => 'Are you sure you want to delete this item?',
                                'method' => 'post',
                            ],
                        ],
                        'enableEditMode' => false,
                    ]) ?>





                    <h4>ข้อมูลการติดตามเฝ้าระวัง 14 วัน</h4>
                    <p>
                        <?= Html::a('เพิ่มรายงานติดตามเฝ้าระวัง 14 วัน', ['followupSqc/create', 'cid' => $model->id, 'person_id' => $model->id], ['class' => 'btn btn-success']) ?>
                    </p>

                    <?php // echo $this->render('_search', ['model' => $searchModel]); 
                    ?>

                    <?= GridView::widget([
                        'dataProvider' => $dataProviderFollowupsqc,
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],




                            [
                                //'label' => 'xxxx',
                                'attribute' => 'report_date',
                                //'valueColOptions'=>['style'=>'width:40%'],
                                'value' => function ($model) {
                                    return  Yii::$app->thai->thaidate('d F Y', strtotime($model['report_date']));
                                },
                            ],
                            // 'temp',
                            [ // แสดงข้อมูลออกเป็นสีตามเงื่อนไข
                                'attribute' => 'temp',
                                'format'=>'html',
                                'value'=>function($model, $key, $index, $column){
                                  return $model->temp<=37.4 ? "<span style=\"color:green;\">$model->temp</span>":"<span style=\"color:red;\">$model->temp</span>";
                                }
                              ],
                            [
                                'label' => 'อาการแสดง',
                                'attribute' => 'q_sick_sign',
                                'value' =>  function ($model) {
                                    return  $model['q_sick_sign'] == '1' ? 'มี' : 'ไม่มี';
                                },

                            ],

                            'note:ntext',
                            [
                                'format' => 'html',
                                'attribute' => 'reporter_name',
                                'value' => function ($model) {
                                    return  $model['reporter_name'] . '<br>' . $model['reporter_phone'];
                                },
                            ],


                            [
                                'attribute' => 'remark',
                                'value' => function ($model) use ($discharge) {
                                    return ($model['remark'] ? $discharge[$model['remark']] : '');
                                },
                            ],

                            [
                                //'label' => 'xxxx',
                                'attribute' => 'last_update',
                                //'valueColOptions'=>['style'=>'width:40%'],
                                'value' => function ($model) {
                                    return  Yii::$app->thai->thaidate('d F Y', strtotime($model['last_update']));
                                },
                            ],
                            [
                                'class' => 'yii\grid\ActionColumn',
                                'controller' => 'followup-sqc',
                                //'buttonOptions'=>['class'=>'btn btn-default'],
                                //'template'=>'<div class="btn-group btn-group-sm text-center" role="group">{view} {update} {delete} </div>',
                                'options'=> ['style'=>'width:80px;'],
                                'buttons' => [     
                                    'view' => function ($url, $d) use ($model) {
                                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', Url::to(['followup-sqc/view', 'id' => $d['id'], 'person_id' => $model->id] ), [
                                                    'title' => Yii::t('app', 'ดู'),
                                        ]);
                                    },                  
                                    'update' => function ($url, $d) use ($model) {
                                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>', Url::to(['followup-sqc/update', 'id' => $d['id'], 'person_id' => $model->id] ), [
                                                    'title' => Yii::t('app', 'แก้ไข'),
                                        ]);
                                    },
                        
                                  ],

                              ],

                        ],
                    ]); ?>

                </div>
            </div>
        </div>
    </div>
</section>
<!-- corona count section ending here -->