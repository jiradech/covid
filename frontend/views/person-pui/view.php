<?php

use yii\helpers\Html;
//use yii\widgets\DetailView;
use kartik\detail\DetailView;
use yii\helpers\Url;
use kartik\datecontrol\DateControl;
use yii\helpers\ArrayHelper;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model app\models\PersonPui */

$this->title = $model->full_name;
$this->params['breadcrumbs'][] = ['label' => 'Person Puis', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
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
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>

        <?= Html::a('<< Back', ['index'], ['class' => 'btn btn-success']) ?>
  
    </p>

    

    <?= DetailView::widget([
         'model' => $model,
        'condensed'=>true,
                    'hover'=>true,
                    'bordered' => true,
                    'striped' => true,
                    'mode'=> DetailView::MODE_VIEW,
                    'hAlign' => DetailView::ALIGN_LEFT,

                    'panel'=>[
                        'heading'=>'<i class="fa fa-user"></i> Information',
                        'type'=>DetailView::TYPE_PRIMARY,
                    ],
                    'attributes' => [
            //'id',
            'pui_code',
            'referal_no',
            'pui_case',
            'pui_type',
            //'pui',
            //'pui_contact',
            'cid',
            'full_name',
            'sex',
            'age',
            'nation',
            'occupation',
            // 'addr_no',
            // 'addr_villno',
            // 'addr_villname',
            // 'addr_tambon',
            // 'addr_amphur',
            // 'addr_province',
            // 'villcode',
            // 'tamboncode',
            // 'amphurcode',
            // 'provincecode',
            [
                'attribute' => 'ที่อยู่ขณะป่วย',
                'value' => 
            'บ้านเลขที่ '.$model->addr_no.' หมู่ที่ '.ArrayHelper::getValue($model->getVillageArray($model->villcode), $model->villcode, 'ไม่ระบุ').' ตำบล'.ArrayHelper::getValue($model->getTambonArray($model->tamboncode), $model->tamboncode, 'ไม่ระบุ').' อำเภอ'.ArrayHelper::getValue($model->getAmphurArray($model->amphurcode), $model->amphurcode, 'ไม่ระบุ').' จังหวัด'.ArrayHelper::getValue($model->getProvinceArray($model->provincecode), $model->provincecode, 'ไม่ระบุ')

            ],
            //'sick_date',
            [
                //'label' => '',
                'attribute' => 'sick_date',
                'valueColOptions'=>['style'=>'width:40%'],
                'value' => Yii::$app->thai->thaidate('d F Y', strtotime($model->sick_date))
                

            ],
            
            //'detect_date',
            [
                //'label' => '',
                'attribute' => 'detect_date',
                'valueColOptions'=>['style'=>'width:40%'],
                'value' => Yii::$app->thai->thaidate('d F Y', strtotime($model->detect_date))
                

            ],
            //'report_date',
            // [
            //     //'label' => '',
            //     'attribute' => 'report_date',
            //     'valueColOptions'=>['style'=>'width:40%'],
            //     'value' => Yii::$app->thai->thaidate('d F Y', strtotime($model->report_date)).' เวลา '.$model->report_time
                

            // ],
            // 'report_time',
            // 'reporter_name',
            // 'reporter_phone',
            [
                'attribute' => 'ผู้แจ้ง',
                'value' =>$model->reporter_name.' เบอร์โทร '. $model->reporter_phone
            ],
            // 'receiver_name',
            // 'receiver_phone',
            [
                'attribute' => 'ผู้รับแจ้ง / วันเวลา',
                'value' =>$model->receiver_name.' เบอร์โทร '. $model->receiver_phone .' / '.Yii::$app->thai->thaidate('d F Y', strtotime($model->report_date)).' เวลา '.$model->report_time
            ],
            //'admit_hosp',
            [
                'attribute' => 'สถานที่รักษา (Admit ห้องแยกโรค)',
                'value' => 
            ArrayHelper::getValue($model->getHosArray($model->admit_hosp), $model->admit_hosp, 'ไม่ระบุ')

            ],
            // 'sample_place',
            // 'sample_type',
            // 'pcr_result',
            // 'pcr_date',
            // 'pcr_time',
            'discharge_result',
            'final_dx',
            'discharge_date',
            'follow_status',
            //'tracking_status',
        ],
    'deleteOptions'=>[
                        'url'=>['delete', 'id' => $model->id],
                        'data'=>[
                            'confirm'=>'Are you sure you want to delete this item?',
                            'method'=>'post',
                        ],
                    ],
                    'enableEditMode'=>false,
                ]) ?>
    <p>
        <?= Html::a('เพิ่มรายงานส่ง lab', ['pui-lab/create', 'pui_code' => $model->pui_code], ['class' => 'btn btn-success']) ?>
    </p>

<?= GridView::widget([
        'dataProvider' => $dataProviderPuiLab,
       // 'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            'pui_code',
            //'referal_no',
            'sample_place',
            'sample_type',
            'pcr_send_date',
            'pcr_result',
            //'pcr_date',
            //'pcr_time',
            //'note:ntext',

            [
                'class' => 'yii\grid\ActionColumn',
                'controller' => 'pui-lab'
            ],
        ],
    ]); ?>
  <!-- corona count section ending here --> 
                            </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- corona count section ending here --> 
