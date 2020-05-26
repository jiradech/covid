<?php

use yii\helpers\Html;
//use yii\widgets\DetailView;
use kartik\detail\DetailView;
use yii\helpers\ArrayHelper;
/* @var $this yii\web\View */
/* @var $model app\models\Followup */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Followups', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
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



    <p>
        <?= Html::a('เพิ่มรายงานติดตามเฝ้าระวัง 14 วัน', ['create', 'cid' => $cid, 'person_id' => $person_id], ['class' => 'btn btn-success']) ?>
        <?= Html::a('แก้ไขข้อมูล', ['update', 'id' => $model->id, 'person_id' => $person_id], ['class' => 'btn btn-primary']) ?>

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
           // 'id',
            'cid',
            //'report_date',
             [
                //'label' => '',
                'attribute' => 'report_date',
                'valueColOptions'=>['style'=>'width:40%'],
                'value' => Yii::$app->thai->thaidate('d F Y', strtotime($model->report_date)) 

            ],
            
            //'q_fever',
            [
                'attribute'=>'q_fever', 
                'format'=>'raw',
                'value'=>$model->q_fever ? '<span class="label label-success">ใช่</span>' : '<span class="label label-danger">ไม่ใช่</span>',
            ],
            'temp',
            //'q_sick_sign',
            [
                'attribute'=>'q_sick_sign', 
                'format'=>'raw',
                'value'=>$model->q_sick_sign ? '<span class="label label-success">ใช่</span>' : '<span class="label label-danger">ไม่ใช่</span>',
            ],
            //'note:ntext',
            [
                'attribute'=>'note',
                 
                'format'=>'raw',
                'value'=>'<span class="text-justify"><em>' . $model->note . '</em></span>',
                'type'=>DetailView::INPUT_TEXTAREA, 
                
                'options'=>['rows'=>4],
                'valueColOptions'=>['style'=>'width:40%'],
            ],
            'reporter_name',
            'reporter_phone',
            //'last_update',
            [
                //'label' => '',
                'attribute' => 'last_update',
                'valueColOptions'=>['style'=>'width:40%'],
               
                    'value' => Yii::$app->thai->thaidate('d F Y', strtotime($model->last_update)) 
            ],
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

   

                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- corona count section ending here --> 
