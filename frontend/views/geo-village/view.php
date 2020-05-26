<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model frontend\models\GeoVillage */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Geo Villages', 'url' => ['index']];
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
        <?= Html::a('Update', ['update', 'id' => $model->villagecodefull], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->villagecodefull], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'villagecodefull',
            'villagename',
            'tambonname',
            'tamboncode',
            'ampurcode',
            'id',
            'name',
            'description',
            'subdistrict',
            'district',
            'province',
            'display',
            'visibility',
            'changwatcode',
            'coordinates',
        ],
    ]) ?>

<!-- corona count section ending here --> 
                            </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- corona count section ending here --> 
