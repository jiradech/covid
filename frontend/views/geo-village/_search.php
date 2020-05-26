<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\GeoVillageSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="geo-village-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'villagecodefull') ?>

    <?= $form->field($model, 'villagename') ?>

    <?= $form->field($model, 'tambonname') ?>

    <?= $form->field($model, 'tamboncode') ?>

    <?= $form->field($model, 'ampurcode') ?>

    <?php // echo $form->field($model, 'id') ?>

    <?php // echo $form->field($model, 'name') ?>

    <?php // echo $form->field($model, 'description') ?>

    <?php // echo $form->field($model, 'subdistrict') ?>

    <?php // echo $form->field($model, 'district') ?>

    <?php // echo $form->field($model, 'province') ?>

    <?php // echo $form->field($model, 'display') ?>

    <?php // echo $form->field($model, 'visibility') ?>

    <?php // echo $form->field($model, 'changwatcode') ?>

    <?php // echo $form->field($model, 'coordinates') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
