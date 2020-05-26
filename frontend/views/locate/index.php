<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\LocateSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Locates';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="locate-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Locate', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'province',
            'district',
            'subdistrict',
            'village',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
