<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\DepDrop;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use kartik\select2\Select2;
use kartik\datecontrol\DateControl;
//use kartik\datecontrol\Module;
//use kartik\datecontrol\DateControl;

use app\models\Province;
use app\models\Amphur;
use app\models\Tambon;
use app\models\Village;
use app\models\Sex;
use app\models\Nation;
use app\models\TypeHome;
use app\models\Countryrisk;
use app\models\RiskType;
/* @var $this yii\web\View */
/* @var $model app\models\Person */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="person-form">

    <?php $form = ActiveForm::begin(); ?>
    
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h4 class="panel-title"><i class="fa fa-user"></i> ข้อมูลทั่วไป</h4>
            </div>
            <div class="panel-body">
    
    <div class="row">
        <div class="col-md-4"><?= $form->field($model, 'cid')->textInput(['maxlength' => true]) ?></div>
        <div class="col-md-4"><?= $form->field($model, 'fname')->textInput(['maxlength' => true]) ?></div>
        <div class="col-md-4"><?= $form->field($model, 'lname')->textInput(['maxlength' => true]) ?></div>
    </div>
    <div class="row">
        <div class="col-md-2"><?= $form->field($model, 'age')->textInput() ?></div>
        <div class="col-md-2"><?= $form->field($model, 'sex')->radioList(
            ArrayHelper::map(Sex::find()->all(),
            'sex',
            'sexname'),
            [
                'id'=>'sex',
                //'prompt'=>'ระบุเพศ...'
       ]); ?> </div>
        <div class="col-md-4">
            <?= $form->field($model, 'nation')->widget(Select2::classname(), [
        'language' => 'de',
        'data' => ArrayHelper::map(Nation::find()->orderBy('nationname')->all(),'nationcode','nationname'),
        'options' => ['placeholder' => 'ระบุสัญชาติ ...'],
        'pluginOptions' => [
            'allowClear' => true
                ],
            ]);
            ?>
                
        </div>
        <div class="col-md-4"><?= $form->field($model, 'occupation')->textInput(['maxlength' => true]) ?></div>
    </div>
    <div class="row">
        <div class="col-md-4"><?= $form->field($model, 'addr_number')->textInput() ?></div>
        <div class="col-md-4">
            <?= $form->field($model, 'addr_province')->widget(Select2::classname(), [
        'language' => 'th',
        'data' => ArrayHelper::map(Province::find()
                                            ->where(['changwatcode' => 27])
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
            <?= $form->field($model, 'addr_ampur')->widget(DepDrop::classname(), [
            'options'=>['id'=>'ddl-amphur'],
            'data'=> $amphur,
           // 'data'=> [],
            'pluginOptions'=>[
                'depends'=>['ddl-province'],
                'placeholder'=>'เลือกอำเภอ...',
                'url'=>Url::to(['/person/get-amphur'])
            ]
        ]); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <?= $form->field($model, 'addr_tambon')->widget(DepDrop::classname(), [
            'options'=>['id'=>'ddl-tambon'],
            'data' =>$district,
            'pluginOptions'=>[
                'depends'=>['ddl-province', 'ddl-amphur'],
                'placeholder'=>'เลือกตำบล...',
                'url'=>Url::to(['/person/get-district'])
            ]
        ]); ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'addr_vill_no')->widget(DepDrop::classname(), [
            'data' =>$village,
            'pluginOptions'=>[
                'depends'=>['ddl-province', 'ddl-amphur','ddl-tambon'],
                'placeholder'=>'เลือกหมู่บ้าน...',
                'url'=>Url::to(['/person/get-village'])
            ]
        ]); ?>
        </div>
        <div class="col-md-4"><?= $form->field($model, 'phone_number')->textInput(['maxlength' => true]) ?></div>
    </div>

        </div>
      
    </div>




 <div class="panel panel-primary">
            <div class="panel-heading">
                <h4 class="panel-title"><i class="fa fa-user"></i> ข้อมูล</h4>
            </div>
            <div class="panel-body">
    <div class="row">
        <div class="col-md-4">
            

             <?= $form->field($model, 'date_in')->widget(DateControl::classname(), ['type' => DateControl::FORMAT_DATE,]);?> 
                
            </div>
            

        

     <div class="col-md-12"><?= $form->field($model, 'house_type')->radioList(
            ArrayHelper::map(TypeHome::find()->all(),
            'id',
            'type'),
            [
                'id'=>'id',
                //'prompt'=>'ระบุเพศ...'
       ]); ?></div> 
        <div class="col-md-4"><?= $form->field($model, 'c_family')->textInput() ?></div>
   



    
        <div class="col-md-12">
           <?= $form->field($model, 'q_from_risk_country')->radioList(
            ArrayHelper::map(RiskType::find()->all(),
            'id',
            'status'),
            [
                'id'=>'q_from_risk_country',
                //'prompt'=>'ระบุเพศ...'
       ]); ?>
            
        </div>
        <div class="col-md-12">
            <?= $form->field($model, 'q_close_to_case')->radioList(
            ArrayHelper::map(RiskType::find()->all(),
            'id',
            'status'),
            [
                'id'=>'q_close_to_case',
               
       ]); ?>
            </div>
        <div class="col-md-4">
           
            <?= $form->field($model, 'risk_from_risk_country')->widget(Select2::classname(), [
        'language' => 'de',
        'data' => ArrayHelper::map(Countryrisk::find()->all(),'countryid','countryname'),
        'options' => ['placeholder' => 'ระบุประเทศ ...'],
        'pluginOptions' => [
            'allowClear' => true
                ],
            ]);
            ?>    
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'risk_korea_worker')->radioList(
            ArrayHelper::map(RiskType::find()->all(),
            'id',
            'status'),
            [
                'id'=>'risk_korea_worker',
               
       ]); ?>
            
        </div>
        <div class="col-md-12"> 
            <?= $form->field($model, 'risk_cambodia_border')->radioList(
            ArrayHelper::map(RiskType::find()->all(),
            'id',
            'status'),
            [
                'id'=>'risk_cambodia_border',
               
       ]); ?>
                
            </div>
        <div class="col-md-12">
            <?= $form->field($model, 'risk_from_bangkok')->radioList(
            ArrayHelper::map(RiskType::find()->all(),
            'id',
            'status'),
            [
                'id'=>'risk_from_bangkok',
               
       ]); ?>
                
            </div>
        <div class="col-md-12">
            <?= $form->field($model, 'q_family_from_risk_country')->radioList(
            ArrayHelper::map(RiskType::find()->all(),
            'id',
            'status'),
            [
                'id'=>'q_family_from_risk_country',
               
       ]); ?>
                
            </div>
        <div class="col-md-12">
            <?= $form->field($model, 'q_close_to_foreigner')->radioList(
            ArrayHelper::map(RiskType::find()->all(),
            'id',
            'status'),
            [
                'id'=>'q_close_to_foreigner',
               
       ]); ?>
                
            </div>
        <div class="col-md-12">
            <?= $form->field($model, 'q_healthcare_staff')->radioList(
            ArrayHelper::map(RiskType::find()->all(),
            'id',
            'status'),
            [
                'id'=>'q_healthcare_staff',
               
       ]); ?>
                
            </div>
        <div class="col-md-12">
            <?= $form->field($model, 'q_close_to_group_fever')->radioList(
            ArrayHelper::map(RiskType::find()->all(),
            'id',
            'status'),
            [
                'id'=>'q_close_to_group_fever',
               
       ]); ?>
                
            </div>
        <div class="col-md-12">
            <?= $form->field($model, 'risk_place')->radioList(
            ArrayHelper::map(RiskType::find()->all(),
            'id',
            'status'),
            [
                'id'=>'risk_place',
               
       ]); ?>
            
        </div>
        <div class="col-md-12"> 
            <?= $form->field($model, 'risk_group_place')->radioList(
            ArrayHelper::map(RiskType::find()->all(),
            'id',
            'status'),
            [
                'id'=>'risk_group_place',
               
       ]); ?>
                
            </div>
        <div class="col-md-12">
            <?= $form->field($model, 'risk_case_place')->radioList(
            ArrayHelper::map(RiskType::find()->all(),
            'id',
            'status'),
            [
                'id'=>'risk_case_place',
               
       ]); ?>
                
            </div>
        <div class="col-md-12">
            <?= $form->field($model, 'note')->textarea(['rows' => 6]) ?>
                
            </div>
        
    </div>
            </div>
      
    </div>
<div class="panel panel-primary">
            <div class="panel-heading">
                <h4 class="panel-title"><i class="fa fa-user"></i> ข้อมูลผู้บันทึก</h4>
            </div>
            <div class="panel-body">
<div class="row">
        <div class="col-md-4"><?= $form->field($model, 'reporter_name')->textInput(['maxlength' => true]) ?></div>
        <div class="col-md-4"><?= $form->field($model, 'reporter_phone')->textInput(['maxlength' => true]) ?></div>
        <!-- <div class="col-md-4"><?= $form->field($model, 'date_stamp')->textInput() ?></div> -->
    </div>
</div>
    </div>
</div>
    

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
