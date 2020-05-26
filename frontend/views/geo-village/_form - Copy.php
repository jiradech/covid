<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\GeoVillage */
/* @var $form yii\widgets\ActiveForm */
?>
<style>
    .panel-body {
    padding: 15px;
    font-size: 14px;
}

.datepicker-dropdown {
    top: 0;
    left: 0;
    padding: 4px;
    font-size: 14px;
    z-index: 99999;
}

.btn {
        height: 50px;
        padding-left: 20px;
        padding-right: 20px;
    }

@media (max-width: 991px) {
    .datepicker-dropdown {
        font-size: 20px;
    }

    .btn {
        width: 100%;
        height: 50px;
    }
}  


label {
    display: inline-block;
    margin-bottom: .5rem;
    margin-right: 50px;
}

.datepicker-dropdown{z-index: 9999 !important;}

.control-label {
    font-size: 16px;
    color: black;
}
</style>
<div class="geo-village-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'villagecodefull')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'villagename')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'tambonname')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'tamboncode')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'ampurcode')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'id')->textInput() ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'subdistrict')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'district')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'province')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'display')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'visibility')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'changwatcode')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'coordinates')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
