<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use kartik\widgets\DepDrop;

use app\models\Province;

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

    <div class="row">
        <div class="col-md-4">
            <?= $form->field($model, 'changwatcode')->widget(Select2::classname(), [
        'language' => 'th',
        'data' => ArrayHelper::map(Province::find()
                                            //->where(['changwatcode' => 27])
                                            ->orderBy('changwatname')
                                            ->all(),'changwatcode','changwatname'),
        'options' => [
            'id'=>'ddl-province',
            'placeholder' => 'เลือกจังหวัด ...'],
        'pluginOptions' => [
            'allowClear' => true
                ],
            ]);
            ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'ampurcode')->widget(DepDrop::classname(), [
            'options'=>['id'=>'ddl-amphur', 'placeholder' => 'เลือกอำเภอ...'],
            'data'=> $amphur,
           // 'data'=> [],
            'type' => DepDrop::TYPE_SELECT2,
            'select2Options'=> ['pluginOptions' => ['allowClear' => true]],
            'pluginOptions'=>[
                 'allowClear' => true,
                'depends'=>['ddl-province'],
                'placeholder'=>'เลือกอำเภอ...',
                'url'=>Url::to(['/geo-village/get-amphur'])
            ]
        ]); ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'tamboncode')->widget(DepDrop::classname(), [
            'options'=>['id'=>'ddl-tambon', 'placeholder' => 'เลือกตำบล...'],
            'data' =>$tambon,
            'type' => DepDrop::TYPE_SELECT2,
            'select2Options'=> ['pluginOptions' => ['allowClear' => true]],
            'pluginOptions'=>[
                'allowClear' => true,
                'depends'=>['ddl-province', 'ddl-amphur'],
                'placeholder'=>'เลือกตำบล...',
                'url'=>Url::to(['/geo-village/get-tambon'])
            ]
        ]); ?>
        </div>
    </div>
        <div class="row">
        <div class="col-md-3"><?= $form->field($model, 'villagecode')->textInput(['maxlength' => true]) ?></div>
        <div class="col-md-4"><?= $form->field($model, 'villagename')->textInput(['maxlength' => true]) ?></div>
    </div>

  
    <?= $form->field($model, 'coordinates')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
