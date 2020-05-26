<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\FollowupSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="followup-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'cid') ?>

    <?= $form->field($model, 'report_date') ?>

    <?= $form->field($model, 'q_fever') ?>

    <?= $form->field($model, 'q_sick_sign') ?>

    <?php // echo $form->field($model, 'note') ?>

    <?php // echo $form->field($model, 'reporter_name') ?>

    <?php // echo $form->field($model, 'reporter_phone') ?>

    <?php // echo $form->field($model, 'last_update') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
