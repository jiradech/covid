<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\web\JsExpression;
use kartik\datecontrol\DateControl;
use yii\helpers\Url;

use app\models\PersonSqc;
use app\models\RiskType;
/* @var $this yii\web\View */
/* @var $model app\models\Followup */
/* @var $form yii\widgets\ActiveForm */
$formatJs = <<< 'JS'
var formatRepo = function (repo) {
    if (repo.loading) {
        return repo.text;
    }

    var avatar_url = repo.avatar_url;
    if (avatar_url == null) {
    avatar_url = "unknown_user.png";
    }

    var markup =
'<div class="row">' +
    '<div class="col-sm-12">' +
        '<img src="http://203.157.145.19/images/avatars/' + avatar_url + '" class="img-circle" style="width:30px" />' +
        '<b style="margin-left:5px">' + repo.fname + ' ' + repo.lname + ' <i class="fa fa-briefcase"></i> :: ' + repo.cid + '</b>' +
    '</div>' +
'</div>';

    return '<div style="overflow:hidden;">' + markup + '</div>';
};
var formatRepoSelection = function (repo) {
    if (repo.fname) {
        return repo.fname + " " + repo.lname ;
    } else {
        return repo.text;
    }

}
JS;

// Register the formatting script
$this->registerJs($formatJs, $this::POS_HEAD);

// script to parse the results into the format expected by Select2
$resultsJs = <<< JS
function (data, params) {
    params.page = params.page || 1;
    return {
        results: data.items,
        pagination: {
            more: (params.page * 30) < data.total_count
        }
    };
}
JS;
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
<div class="followup-form">

    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'person_id')->hiddenInput(['value'=> $model->person_id])->label(false);?>
    <div class="panel panel-primary">
            <div class="panel-heading">
                <h4 class="panel-title"><i class="fa fa-user"></i> ข้อมูลติดดามบุคคลเลื่อนย้ายเข้าพื้นที่</h4>
            </div>
            <div class="panel-body">
<div class="row">
        <div class="col-md-4">
            <?= $form->field($model, 'cid')->widget(Select2::classname(), [
                'readonly' => !$model->isNewRecord,
                'disabled' => !$model->isNewRecord,
    // <?=Select2::widget([
               // 'model' => $model,
                'name' => 'cid',
                'value' => '',
                'initValueText' =>$fullname,
                'options' => ['placeholder' => 'ค้นหา ...',
                                    'id'=>'cid',

                                ],
                'pluginOptions' => [
                    'allowClear' => true,
                    'minimumInputLength' => 3,
                    'ajax' => [
                        'url' => \yii\helpers\Url::to(['user-list']),
                        'dataType' => 'json',
                        'delay' => 250,
                        'data' => new JsExpression('function(params) { return {q:params.term, page: params.page}; }'),
                        'processResults' => new JsExpression($resultsJs),
                        'cache' => true
                    ],
                    'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                    'templateResult' => new JsExpression('formatRepo'),
                    'templateSelection' => new JsExpression('formatRepoSelection'),
                ],
            ]);?>
            

            </div>
        <div class="col-md-4">
            <?= $form->field($model, 'report_date')->widget(DateControl::classname(), ['type' => DateControl::FORMAT_DATE,]);?>
                
            </div>
        <div class="col-md-12">
            <?= $form->field($model, 'temp')->textInput(['maxlength' => true]) ?>


           
       </div>
        <div class="col-md-12"><?= $form->field($model, 'q_sick_sign')->radioList(
            ArrayHelper::map(RiskType::find()->all(),
            'id',
            'status'),
            [
                'id'=>'q_sick_sign',
               
       ]); ?></div>
       <div class="col-md-12 flex-wrapper">
     

       <?= $form->field($model, 'remark')->radioList(array(
        '0'=>'ติดตามไม่ได้', 
        '1'=>'ย้ายออกนอกพื้นที่',
        '2'=>'ยังอยู่ในพื้นที่',
        '3' => 'ส่ง รพ.'
      //  '2'=>'ยังอยู่ในพื้นที่ และทำ Home Quarantine', 
      //  '3' => 'ยังอยู่ในพื้นที่ ฝ่าฝืนหรือไม่ทำ Home Quarantine', 
       // '4' => 'ยังอยู่ในพื้นที่ Local Quarantine', 
        //'5' => 'ส่ง รพ.'
    ), ['separator'=>'<br/><br/>']); ?>



    </div>
        <div class="col-md-12"><?= $form->field($model, 'note')->textarea(['rows' => 6]) ?></div>
    </div>
</div>
    </div>

    <div class="panel panel-primary">
            <div class="panel-heading">
                <h4 class="panel-title"><i class="fa fa-user"></i> ข้อมูลผู้รายงาน</h4>
            </div>
            <div class="panel-body">
<div class="row">
        <div class="col-md-4"><?= $form->field($model, 'reporter_name')->textInput(['maxlength' => true]) ?></div>
        <div class="col-md-4"><?= $form->field($model, 'reporter_phone')->textInput(['maxlength' => true]) ?></div>
        
    </div>
</div>
    </div>
        

    <div class="form-group">
        <?= Html::submitButton('บันทึก', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
