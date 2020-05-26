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
<div align='right'>
    <?= Html::a('บันทึกข้อมูลคุมไว้สังเกต', ['person-sqc/create'], ['class' => 'btn btn-primary']) ?>
        
    </div>


<div class="locate-form">

    <?php $form = ActiveForm::begin(['action' => ['site/getindays']]); ?>


    <?= $form->field($model, 'province')->hiddenInput(['value'=> '27'])->label(false);?>

    <div class="row">
        <div class="col-md-3">
            <?= $form->field($model, 'lost_days')->widget(Select2::classname(), [
                'data' => [
                    '1' => 'เข้าพื้นที่ในช่วง 14 วัน',
                    '2' => 'เข้าพื้นที่ในช่วง 1 เดือน',
                    '3' => 'เข้าพื้นที่ในช่วง 2 เดือน',
                    '4' => 'เข้าพื้นที่ในช่วง 3 เดือน',
                    '5' => 'ทั้งหมด'
                ],
                'options' => [
                    'id' => 'ddl-lost_day',
                    'placeholder' => 'ระบุเงื่อนไข'
                ],
                'pluginOptions' => [
                    'allowClear' => false
                ],
            ])->label('เงื่อนไข');
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

</div>