<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\datecontrol\DateControl;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use app\models\PuiLab;
/* @var $this yii\web\View */
/* @var $model app\models\PuiLab */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="pui-lab-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="panel panel-primary">
            <div class="panel-heading">
                
                <h4 class="panel-title"><i class="fa fa-user"></i> ผลการรักษา</h4>
                
            </div>
            <div class="panel-body">


    <div class="row">
        <div class="col-md-3"><?= $form->field($model, 'pui_code')->textInput(['maxlength' => true,'readonly' => true]) ?></div>
        <div class="col-md-3"></div>
        <div class="col-md-3"></div>
        <div class="col-md-3"></div>
        <div class="col-md-3"></div>
    </div>
    <div class="row">
       
        
        <div class="col-md-4"><?= $form->field($model, 'sample_place')->widget(Select2::classname(), [
        
        'data' => ArrayHelper::map(PuiLab::find()
                                            // ->select(['admit_hosp','concat(00," ",admit_hosp) as  id'])
                                            //->where(['changwatcode' => 27])
                                            //->orderBy('changwatname')
                                            ->groupBy(['sample_place'])
                                            ->all(),'sample_place','sample_place'),
        'options' => [
            'id'=>'sample_place',
            'placeholder' => 'เลือก สถานที่ส่งตรวจ...'],
        'pluginOptions' => [
            'allowClear' => true,
            'tags' => true
                ],
            ]);
            ?></div>
        <div class="col-md-4"><?= $form->field($model, 'sample_type')->widget(Select2::classname(), [
        
        'data' => ArrayHelper::map(PuiLab::find()
                                            // ->select(['admit_hosp','concat(00," ",admit_hosp) as  id'])
                                            //->where(['changwatcode' => 27])
                                            //->orderBy('changwatname')
                                            ->groupBy(['sample_type'])
                                            ->all(),'sample_type','sample_type'),
        'options' => [
            'id'=>'sample_type',
            'placeholder' => 'เลือกชนิดสิ่งส่งตรวจ...'],
        'pluginOptions' => [
            'allowClear' => true,
            'tags' => true
                ],
            ]);
            ?></div>
        <div class="col-md-4"><?= $form->field($model, 'pcr_send_date')->widget(DateControl::classname(), ['type' => DateControl::FORMAT_DATE,]);?></div>
        
    </div>
    <div class="row">
        <div class="col-md-4"><?= $form->field($model, 'pcr_result')->widget(Select2::classname(), [
        
        'data' => ArrayHelper::map(PuiLab::find()
                                            // ->select(['admit_hosp','concat(00," ",admit_hosp) as  id'])
                                            //->where(['changwatcode' => 27])
                                            //->orderBy('changwatname')
                                            ->groupBy(['pcr_result'])
                                            ->all(),'pcr_result','pcr_result'),
        'options' => [
            'id'=>'pcr_result',
            'placeholder' => 'เลือก สถานที่ส่งตรวจ...'],
        'pluginOptions' => [
            'allowClear' => true,
            'tags' => true
                ],
            ]);
            ?></div>
        <div class="col-md-4">
            <?= $form->field($model, 'pcr_date')->widget(DateControl::classname(), ['type' => DateControl::FORMAT_DATE,]);?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'pcr_time')->widget(DateControl::classname(), 
        ['type' => 'time',
    'ajaxConversion' => true,
    'autoWidget' => false,
    'widgetClass' => 'yii\widgets\MaskedInput',
    'displayFormat' => 'php:H:i',
    'saveFormat' => 'php:H:i:s',
    'saveTimezone' => 'Asia/Bangkok',
    'displayTimezone' => 'Asia/Bangkok',
    // 'saveOptions' => [
    //     'label' => 'Input saved as: ',
    //     'type' => 'text',
    //     'readonly' => true,
    //     'class' => 'hint-input text-muted'
    // ],
    'widgetOptions' => [
        'options' => [
            'class' => 'form-control'
        ],
        'mask' => '99:99'
    ],
    'language' => 'th'

        ]);?>
            
        </div>
        
    </div>
     <div class="row">
        <div class="col-md-12"><?= $form->field($model, 'note')->textarea(['rows' => 6]) ?></div>

    </div>

        </div>
    </div>

    

    

    

    

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
