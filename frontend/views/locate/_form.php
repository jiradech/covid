<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\DepDrop;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use kartik\select2\Select2;
use app\models\Ampur;


/* @var $this yii\web\View */
/* @var $model app\models\Locate */
/* @var $form yii\widgets\ActiveForm */
?>



    <?php $form = ActiveForm::begin(); ?>


    <?= $form->field($model, 'province')->hiddenInput(['value'=> '27'])->label(false);?>

    <div class="row justify-content-center align-items-center">
        <div class="col-md-3">
            <?= $form->field($model, 'lost_days')->widget(Select2::classname(), [
                'data' => [
                    '0' => 'วันนี้',
                    '1' => '2 วันขึ้นไป',
                    '2' => '3 วันขึ้นไป',
                    '3' => '4 วันขึ้นไป',
                    '4' => '5 วันขึ้นไป',
                    '5' => '6 วันขึ้นไป',
                    '6' => '7 วันขึ้นไป',
                    '7' => '8 วันขึ้นไป',
                    '8' => '9 วันขึ้นไป',
                    '9' => '10 วันขึ้นไป',
                    '10' => '11 วันขึ้นไป',
                    '11' => '12 วันขึ้นไป',
                    '12' => '13 วันขึ้นไป',
                    '13' => '14 วันขึ้นไป',
                ],
                'options' => [
                    'id' => 'ddl-lost_day',
                    'placeholder' => 'ระบุจำนวนวัน...'
                ],
                'pluginOptions' => [
                    'allowClear' => false
                ],
            ])->label('ขาดการติดตาม');
            ?>
        </div>

        <div class="col-md-3">
            <?= $form->field($model, 'district')->widget(Select2::classname(), [
                'data' => ArrayHelper::map(Ampur::find()
                    ->where(['changwatcode' => 27])
                    //->orderBy('changwatname')
                    ->all(), 'ampurcodefull', 'ampurname'),
                'options' => [
                    'id' => 'ddl-amphur',
                    'placeholder' => 'เลือกอำเภอ ...'
                ],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]);
            ?>
        </div>


        <div class="col-md-3">
            <?= $form->field($model, 'subdistrict')->widget(DepDrop::classname(), [
                'options' => ['id' => 'ddl-tambon', 'placeholder' => 'ทุกตำบล'],
                'data' => $tambon,
                'type' => DepDrop::TYPE_SELECT2,
                'select2Options' => ['pluginOptions' => ['allowClear' => true]],
                'pluginOptions' => [
                    'allowClear' => true,
                    'depends' => ['ddl-amphur'],
                    'placeholder' => 'ทุกตำบล',
                    'url' => Url::to(['/person/get-tambon'])
                ]
            ]); ?>
        </div>


        <div class="col-md-3">
            <?= $form->field($model, 'village')->widget(DepDrop::classname(), [
                'options' => ['id' => 'ddl-village', 'placeholder' => 'ทุกหมู่บ้าน'],
                'data' => $village,
                'type' => DepDrop::TYPE_SELECT2,
                'select2Options' => ['pluginOptions' => ['allowClear' => true]],
                'pluginOptions' => [
                    'depends' => ['ddl-amphur', 'ddl-tambon'],
                    'placeholder' => 'ทุกหมู่บ้าน',
                    'url' => Url::to(['/person/get-village'])
                ]
            ]); ?>
        </div>




    </div>


    <div class="form-group">
        <?= Html::submitButton('Submit', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

