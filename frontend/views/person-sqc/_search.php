<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\PersonSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="person-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'cid') ?>

    <?= $form->field($model, 'prename') ?>

    <?= $form->field($model, 'fname') ?>

    <?= $form->field($model, 'lname') ?>

    <?php // echo $form->field($model, 'age') ?>

    <?php // echo $form->field($model, 'sex') ?>

    <?php // echo $form->field($model, 'occupation') ?>

    <?php // echo $form->field($model, 'phone_number') ?>

    <?php // echo $form->field($model, 'date_in') ?>

    <?php // echo $form->field($model, 'addr_number') ?>

    <?php // echo $form->field($model, 'addr_vill_no') ?>

    <?php // echo $form->field($model, 'addr_tambon') ?>

    <?php // echo $form->field($model, 'addr_ampur') ?>

    <?php // echo $form->field($model, 'addr_province') ?>

    <?php // echo $form->field($model, 'nation') ?>

    <?php // echo $form->field($model, 'house_type') ?>

    <?php // echo $form->field($model, 'c_family') ?>

    <?php // echo $form->field($model, 'q_from_risk_country') ?>

    <?php // echo $form->field($model, 'q_close_to_case') ?>

    <?php // echo $form->field($model, 'risk_from_risk_country') ?>

    <?php // echo $form->field($model, 'risk_korea_worker') ?>

    <?php // echo $form->field($model, 'risk_cambodia_border') ?>

    <?php // echo $form->field($model, 'risk_from_bangkok') ?>

    <?php // echo $form->field($model, 'q_family_from_risk_country') ?>

    <?php // echo $form->field($model, 'q_close_to_foreigner') ?>

    <?php // echo $form->field($model, 'q_healthcare_staff') ?>

    <?php // echo $form->field($model, 'q_close_to_group_fever') ?>

    <?php // echo $form->field($model, 'risk_place') ?>

    <?php // echo $form->field($model, 'risk_group_place') ?>

    <?php // echo $form->field($model, 'risk_case_place') ?>

    <?php // echo $form->field($model, 'note') ?>

    <?php // echo $form->field($model, 'reporter_name') ?>

    <?php // echo $form->field($model, 'reporter_phone') ?>

    <?php // echo $form->field($model, 'date_stamp') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
