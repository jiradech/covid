<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\FollowupSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Followups';
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

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Followup', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            'cid',
            'report_date',
            'q_fever',
            'q_sick_sign',
            //'note:ntext',
            //'reporter_name',
            //'reporter_phone',
            //'last_update',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


                            </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- corona count section ending here --> 
