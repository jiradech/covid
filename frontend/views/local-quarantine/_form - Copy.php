<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\LocalQuarantine */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="local-quarantine-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'local_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'addr_villno')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'addr_tambon')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'addr_amphur')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'addr_province')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'amphur')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'tambon')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'province')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'remark')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
