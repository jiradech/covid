<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
//use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\widgets\DepDrop;

use kartik\select2\Select2;
use kartik\datecontrol\DateControl;
use app\models\Nation;
use app\models\Province;
use app\models\PersonPui;
use app\models\Hospital;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\PersonPui */
/* @var $form yii\widgets\ActiveForm */
?>
<!-- <style>
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
</style> -->
<div class="person-pui-form">

    <?php $form = ActiveForm::begin(); ?>
           <div class="panel panel-primary">
            <div class="panel-heading">
                
                <h4 class="panel-title"><i class="fa fa-user"></i>ข้อมูลผู้ป่วย</h4>
                
            </div>
            <div class="panel-body">
    
    <div class="row">
       <div class="col-md-1"><?= $form->field($model, 'sort_order')->textInput() ?></div>
        <div class="col-md-3"><?= $form->field($model, 'pui_code')->textInput(['maxlength' => true]) ?></div>
        
        <div class="col-md-2"><?= $form->field($model, 'referal_no')->textInput(['maxlength' => true]) ?></div>
        <div class="col-md-4"><?= $form->field($model, 'pui_case')->radioList(['PUI' => 'PUI', 'PUI CONTACT' => 'PUI CONTACT', 'Health Care Worker' => 'Health Care Worker']) ?></div>
        <div class="col-md-2">
        <?= $form->field($model, 'pui_type')->dropdownList(array('Q'=>'Q', 'A'=>'A', 'P'=>'P', 'M'=>'M', 'S'=>'S'),['prompt'=>'ระบุ ประเภท PUI']); ?></div>
    </div>
    <div class="row">
        <div class="col-md-2"><?= $form->field($model, 'cid')->textInput(['maxlength' => true]) ?></div>
        <div class="col-md-3"><?= $form->field($model, 'full_name')->textInput(['maxlength' => true]) ?></div>
        <div class="col-md-2"><?= $form->field($model, 'sex')->radioList(array('ชาย'=>'ชาย', 'หญิง'=>'หญิง')); ?></div>
        <div class="col-md-1"><?= $form->field($model, 'age')->textInput() ?></div>
        <div class="col-md-4"><?= $form->field($model, 'nation')->widget(Select2::classname(), [
        
        'data' => ArrayHelper::map(Nation::find()
            ->orderBy([
          // 'usertype'=>SORT_ASC,
           'nationrisk' => SORT_DESC,'nationcode'=>SORT_ASC,
        ])
          //  ->orderBy('nationrisk')
            ->all(),'nationname','nationname'),
        'options' => ['placeholder' => 'ระบุสัญชาติ ...'],
        'pluginOptions' => [
            'allowClear' => true
                ],
            ]);
            ?></div>
    </div>
    
    <div class="row">
        <div class="col-md-4"><?= $form->field($model, 'occupation')->textInput(['maxlength' => true]) ?></div>
        <div class="col-md-4"></div>
        <div class="col-md-4"></div>
    </div>

    <div class="row">
        <div class="col-md-4"></div>
        <div class="col-md-4"></div>
        <div class="col-md-4"></div>
    </div>

        </div>
      
    </div>
    

    <div class="panel panel-primary">
            <div class="panel-heading">
                
                <h4 class="panel-title"><i class="fa fa-user"></i>ข้อมูล</h4>
                
            </div>
            <div class="panel-body">
        <div class="row">
        <div class="col-md-4"><h6>ภูมิลำเนา</h6></div>
 
    </div>
    <div class="row">
        <div class="col-md-3"><?= $form->field($model, 'addr_no')->textInput(['maxlength' => true]) ?></div>
        <div class="col-md-3">
            <?= $form->field($model, 'provincecode')->widget(Select2::classname(), [
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
        <div class="col-md-3">
            <?= $form->field($model, 'amphurcode')->widget(DepDrop::classname(), [
            'options'=>['id'=>'ddl-amphur'],
            'data'=> $amphur,
           // 'data'=> [],
            'pluginOptions'=>[
                'depends'=>['ddl-province'],
                'placeholder'=>'เลือกอำเภอ...',
                'url'=>Url::to(['/person-pui/get-amphur'])
            ]
        ]); ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'tamboncode')->widget(DepDrop::classname(), [
            'options'=>['id'=>'ddl-tambon'],
            'data' =>$district,
            'pluginOptions'=>[
                'depends'=>['ddl-province', 'ddl-amphur'],
                'placeholder'=>'เลือกตำบล...',
                'url'=>Url::to(['/person-pui/get-district'])
            ]
        ]); ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'villcode')->widget(DepDrop::classname(), [
            'data' =>$village,
            'pluginOptions'=>[
                'depends'=>['ddl-province', 'ddl-amphur','ddl-tambon'],
                'placeholder'=>'เลือกหมู่บ้าน...',
                'url'=>Url::to(['/person-pui/get-village'])
            ]
        ]); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4"><?= $form->field($model, 'sick_date')->widget(DateControl::classname(), ['type' => DateControl::FORMAT_DATE,]);?>  </div>
        <div class="col-md-4"><?= $form->field($model, 'detect_date')->widget(DateControl::classname(), ['type' => DateControl::FORMAT_DATE,]);?> </div>
        <div class="col-md-4">
            
            <?= $form->field($model, 'admit_hosp')->widget(Select2::classname(), [
        
        'data' => ArrayHelper::map(Hospital::find()
                                             ->select(['hoscode','concat(hoscode," ",hosname) as  hosname'])
                                            ->where(['provcode' => 27])
                                            ->andFilterWhere(['IN','hostype',[06,07,11,12]])
                                            //->orderBy('changwatname')
                                            //->groupBy(['admit_hosp'])
                                            ->all(),'hoscode','hosname'),
        'options' => [
            'id'=>'admit_hosp',
            'placeholder' => 'เลือก สถานที่ ...'],
        'pluginOptions' => [
            'allowClear' => true,
            //'tags' => true
                ],
            ]);
            ?>
         </div>
        
        
    </div>
    
    <div class="row">
        <div class="col-md-3"><?= $form->field($model, 'reporter_name')->textInput(['maxlength' => true]) ?></div>
        <div class="col-md-3"><?= $form->field($model, 'reporter_phone')->textInput(['maxlength' => true]) ?></div>
        
        
        
    </div>
    <div class="row">
        <div class="col-md-3"><?= $form->field($model, 'receiver_name')->widget(Select2::classname(), [
        
        'data' => ArrayHelper::map(PersonPui::find()
                                            // ->select(['admit_hosp','concat(00," ",admit_hosp) as  id'])
                                            //->where(['changwatcode' => 27])
                                            //->orderBy('changwatname')
                                            ->groupBy(['receiver_name'])
                                            ->all(),'receiver_name','receiver_name'),
        'options' => [
            'id'=>'receiver_name',
            'placeholder' => 'เลือกผู้รับแจ้ง...'],
        'pluginOptions' => [
            'allowClear' => true,
            'tags' => true
                ],
            ]);
            ?></div>
        <div class="col-md-3"><?= $form->field($model, 'receiver_phone')->textInput(['maxlength' => true]) ?></div>
        <div class="col-md-4">    <?= $form->field($model, 'report_date')->widget(DateControl::classname(), ['type' => DateControl::FORMAT_DATE,]);?>

    
    </div>
    <div class="col-md-2"><?= $form->field($model, 'report_time')->widget(DateControl::classname(), 
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
    
    

        </div>
      
    </div>

     <div class="panel panel-primary">
            <div class="panel-heading">
                
                <h4 class="panel-title"><i class="fa fa-user"></i> ผลการรักษา</h4>
                
            </div>
            <div class="panel-body">
    
    <div class="row">
        <div class="col-md-4"></div>
        <div class="col-md-4"></div>
        <div class="col-md-4"></div>
        
    </div>
    <div class="row">
        <div class="col-md-4"></div>
        <div class="col-md-4"></div>
        <div class="col-md-4"></div>
        
    </div>
     <div class="row">
        <div class="col-md-12"><?= $form->field($model, 'discharge_result')->textInput(['maxlength' => true]) ?></div>
        <div class="col-md-12"><?= $form->field($model, 'final_dx')->textInput(['maxlength' => true]) ?></div>
        <div class="col-md-3"><?= $form->field($model, 'discharge_date')->textInput(['maxlength' => true]) ?></div>
        <div class="col-md-3">
            
            <?= $form->field($model, 'follow_status')->widget(Select2::classname(), [
        
        'data' => PersonPui::getFollowstatusArray(),
                                            // ArrayHelper::map(PersonPui::find()
                                            // // ->select(['admit_hosp','concat(00," ",admit_hosp) as  id'])
                                            // //->where(['changwatcode' => 27])
                                            // //->orderBy('changwatname')
                                            // ->groupBy(['follow_status'])
                                            // ->all(),'follow_status','follow_status'),
        'options' => [
            'id'=>'follow_status',
            'placeholder' => 'เลือก สถานะ ...'],
        'pluginOptions' => [
            'allowClear' => true,
            'tags' => true
                ],
            ]);
            ?>   
            </div>
    </div>
        </div>
    </div>
     

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
 </div>

