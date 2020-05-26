<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Countryrisk */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="countryrisk-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'countryname')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'riskgroup')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'epidemicgroup')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
