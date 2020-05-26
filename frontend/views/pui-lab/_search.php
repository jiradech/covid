<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\PuiLabSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="pui-lab-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'pui_code') ?>

    <?= $form->field($model, 'referal_no') ?>

    <?= $form->field($model, 'sample_place') ?>

    <?= $form->field($model, 'sample_type') ?>

    <?php // echo $form->field($model, 'pcr_send_date') ?>

    <?php // echo $form->field($model, 'pcr_result') ?>

    <?php // echo $form->field($model, 'pcr_date') ?>

    <?php // echo $form->field($model, 'pcr_time') ?>

    <?php // echo $form->field($model, 'note') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
