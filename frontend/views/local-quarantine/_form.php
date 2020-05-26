<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use kartik\widgets\DepDrop;

use app\models\Province;

/* @var $this yii\web\View */
/* @var $model app\models\LocalQuarantine */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="local-quarantine-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'local_name')->textInput(['maxlength' => true]) ?>

    




    <div class="col-md-4">
            <?= $form->field($model, 'addr_province')->widget(Select2::classname(), [
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
            <?= $form->field($model, 'addr_amphur')->widget(DepDrop::classname(), [
            'options'=>['id'=>'ddl-amphur', 'placeholder' => 'เลือกอำเภอ...'],
            'data'=> $amphur,
           // 'data'=> [],
            'type' => DepDrop::TYPE_SELECT2,
            'select2Options'=> ['pluginOptions' => ['allowClear' => true]],
            'pluginOptions'=>[
                 'allowClear' => true,
                'depends'=>['ddl-province'],
                'placeholder'=>'เลือกอำเภอ...',
                'url'=>Url::to(['/local-quarantine/get-amphur'])
            ]
        ]); ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'addr_tambon')->widget(DepDrop::classname(), [
            'options'=>['id'=>'ddl-tambon', 'placeholder' => 'เลือกตำบล...'],
            'data' =>$tambon,
            'type' => DepDrop::TYPE_SELECT2,
            'select2Options'=> ['pluginOptions' => ['allowClear' => true]],
            'pluginOptions'=>[
                'allowClear' => true,
                'depends'=>['ddl-province', 'ddl-amphur'],
                'placeholder'=>'เลือกตำบล...',
                'url'=>Url::to(['/local-quarantine/get-tambon'])
            ]
        ]); ?>
        </div>
        <?= $form->field($model, 'addr_villno')->widget(DepDrop::classname(), [
                 'options'=>['id'=>'ddl-village'],
            'data' =>$village,
            'pluginOptions'=>[
                'depends'=>['ddl-province', 'ddl-amphur','ddl-tambon'],
                'placeholder'=>'เลือกหมู่บ้าน...',
                'url'=>Url::to(['/local-quarantine/get-village'])
            ]
        ]); ?>

    <!-- <?= $form->field($model, 'addr_villno')->textInput(['maxlength' => true]) ?> -->
    <?= $form->field($model, 'amphur')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'tambon')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'province')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'remark')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
