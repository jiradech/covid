<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\CountryriskSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="countryrisk-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'countryid') ?>

    <?= $form->field($model, 'countryname') ?>

    <?= $form->field($model, 'riskgroup') ?>

    <?= $form->field($model, 'epidemicgroup') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
