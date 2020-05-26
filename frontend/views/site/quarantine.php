<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Local Quarantine';
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








                    <h4>Local Quarantine</h4>
                    <table id="example0" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>ลำดับ</th>
                                <th>ศูนย์กักตัว</th>
                                <th>อำเภอ</th>
                                <th>จำนวนผู้กักตัว</th>
                                <th>หมายเหตุ</th>


                            </tr>
                        </thead>


                        <tbody>

                            <?php
                            $i = 0;
                            $total_case = 0;
                            foreach ($local as $value) {
                                $i++;

                                if ($value['c']) {
                                    $total_case  += $value['c'];
                                }

                            ?>

                                <tr>
                                    <td><?= $i ?></td>
                                    <td><a href="<?= Url::to(['local-quarantine/view', 'id' => $value['id']]) ?>"><?= $value['local_name'] ?></a></td>
                                    <td><?= $value['amphur'] ?></td>
                                    <th><?= $value['c'] ?></th>
                                    <td><?= $value['remark'] ?></td>

                                </tr>

                            <?php
                            }

                            ?>
                        <tfoot>
                            <tr>
                                <th></th>
                                <th>Total</th>
                                <th></th>
                                <th><?= $total_case ?></th>
                                <th></th>
                            </tr>
                        </tfoot>

                        </tbody>
                    </table>
                
                
                
                
                
                
                </div>
            </div>
        </div>
    </div>
</section>
<!-- corona count section ending here -->