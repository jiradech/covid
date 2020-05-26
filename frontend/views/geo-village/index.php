<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\GeoVillageSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Geo Villages';
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
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Geo Village', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'villagecodefull',
            'villagename',
            'tambonname',
            'tamboncode',
            'ampurcode',
            //'id',
            //'name',
            //'description',
            //'subdistrict',
            //'district',
            //'province',
            //'display',
            //'visibility',
            //'changwatcode',
            //'coordinates',

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
