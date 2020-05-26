<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\PersonPui */

$this->title = 'Update : ' . $model->full_name;
$this->params['breadcrumbs'][] = ['label' => 'Person Puis', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';

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

    <?= $this->render('_form', [
        'model' => $model,
        'amphur'=> $amphur,
        'district' =>$district,
        'village' =>$village,
    ]) ?>

						</div>
                    </div>
                </div>
            </div>
        </section>
        <!-- corona count section ending here --> 

