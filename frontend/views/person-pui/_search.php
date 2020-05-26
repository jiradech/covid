<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\PersonpuiSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="person-pui-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'pui_code') ?>

    <?= $form->field($model, 'referal_no') ?>

    <?= $form->field($model, 'pui_case') ?>

    <?= $form->field($model, 'pui') ?>

    <?php // echo $form->field($model, 'pui_contact') ?>

    <?php // echo $form->field($model, 'full_name') ?>

    <?php // echo $form->field($model, 'cid') ?>

    <?php // echo $form->field($model, 'sex') ?>

    <?php // echo $form->field($model, 'age') ?>

    <?php // echo $form->field($model, 'nation') ?>

    <?php // echo $form->field($model, 'occupation') ?>

    <?php // echo $form->field($model, 'addr_no') ?>

    <?php // echo $form->field($model, 'addr_villno') ?>

    <?php // echo $form->field($model, 'addr_villname') ?>

    <?php // echo $form->field($model, 'addr_tambon') ?>

    <?php // echo $form->field($model, 'addr_amphur') ?>

    <?php // echo $form->field($model, 'addr_province') ?>

    <?php // echo $form->field($model, 'villcode') ?>

    <?php // echo $form->field($model, 'tamboncode') ?>

    <?php // echo $form->field($model, 'amphurcode') ?>

    <?php // echo $form->field($model, 'provincecode') ?>

    <?php // echo $form->field($model, 'sick_date') ?>

    <?php // echo $form->field($model, 'detect_date') ?>

    <?php // echo $form->field($model, 'report_date') ?>

    <?php // echo $form->field($model, 'report_time') ?>

    <?php // echo $form->field($model, 'reporter_name') ?>

    <?php // echo $form->field($model, 'reporter_phone') ?>

    <?php // echo $form->field($model, 'receiver_name') ?>

    <?php // echo $form->field($model, 'receiver_phone') ?>

    <?php // echo $form->field($model, 'admit_hosp') ?>

    <?php // echo $form->field($model, 'sample_place') ?>

    <?php // echo $form->field($model, 'sample_type') ?>

    <?php // echo $form->field($model, 'pcr_result') ?>

    <?php // echo $form->field($model, 'pcr_date') ?>

    <?php // echo $form->field($model, 'pcr_time') ?>

    <?php // echo $form->field($model, 'discharge_result') ?>

    <?php // echo $form->field($model, 'final_dx') ?>

    <?php // echo $form->field($model, 'discharge_date') ?>

    <?php // echo $form->field($model, 'follow_status') ?>

    <?php // echo $form->field($model, 'tracking_status') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
