<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\LocalQuarantineSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="local-quarantine-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'local_name') ?>

    <?= $form->field($model, 'addr_villno') ?>

    <?= $form->field($model, 'addr_tambon') ?>

    <?= $form->field($model, 'addr_amphur') ?>

    <?php // echo $form->field($model, 'addr_province') ?>

    <?php // echo $form->field($model, 'amphur') ?>

    <?php // echo $form->field($model, 'tambon') ?>

    <?php // echo $form->field($model, 'province') ?>

    <?php // echo $form->field($model, 'remark') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
