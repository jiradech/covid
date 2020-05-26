<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Person */

$this->title = 'บันทึกข้อมูลผู้ใช้บริการ / ผู้เข้าพัก';
$this->params['breadcrumbs'][] = ['label' => 'People', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<style>

.page-header {
	    padding: 150px 0 0px;
    }
    
      @media (max-width: 991px) {
	  .page-header {
	    padding: 46px 0 0px;
            }
        }
    
</style>

<!-- Page Header Section Start Here -->
        <section class="page-header">
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
        'tambon' => $tambon,
        'village' => $village,

        'mamphur' => $mamphur,
        'mtambon' => $mtambon,
         'mvillage' => $mvillage,
       	// 'district' =>[],
       	 
    ]) ?>

                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- corona count section ending here --> 
