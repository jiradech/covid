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
use app\models\Ampur;
// use app\models\Tambon;
// use app\models\Village;
use app\models\Sex;
use app\models\Nation;
use app\models\TypeHome;
use app\models\Countryrisk;
use app\models\RiskType;
use app\models\LocalQuarantine;
use app\models\PersonHb;
/* @var $this yii\web\View */
/* @var $model app\models\Person */
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

<div class="person-form">

    <?php $form = ActiveForm::begin(); ?>
    
        <div class="panel panel-primary">
            <div class="panel-heading">
                
                <h4 class="panel-title"><i class="fa fa-user"></i> ข้อมูลส่วนตัว</h4>
                
            </div>
            <div class="panel-body">
    
    <div class="row">
        <div class="col-md-4"><?= $form->field($model, 'cid')->textInput(['maxlength' => true]) ?></div>
        <div class="col-md-4"><?= $form->field($model, 'fname')->textInput(['maxlength' => true]) ?></div>
        <div class="col-md-4"><?= $form->field($model, 'lname')->textInput(['maxlength' => true]) ?></div>
    </div>
    <div class="row">
        <div class="col-md-1"><?= $form->field($model, 'age')->textInput() ?></div>
        <div class="col-md-3">
        <?= $form->field($model, 'sex')->radioList(array('ชาย'=>'ชาย', 'หญิง'=>'หญิง')); ?>
            </div>
        <div class="col-md-4">
            <?= $form->field($model, 'nation')->widget(Select2::classname(), [
        'language' => 'th',
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
            ?>
                
        </div>
        <div class="col-md-4"><?= $form->field($model, 'occupation')->textInput(['maxlength' => true]) ?></div>
    </div>
    
    

          <div class="row">
        <div class="col-md-4"><h6>ที่พักอาศัย ระบุ</h6></div></div>


        <div class="row">
        <div class="col-md-3"><?= $form->field($model, 'addr_number')->textInput(['maxlength' => true]) ?></div>
        <div class="col-md-3">
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
        <div class="col-md-3">
            <?= $form->field($model, 'addr_ampur')->widget(DepDrop::classname(), [
            'options'=>['id'=>'ddl-amphur'],
            'data'=> $amphur,
           // 'data'=> [],
            'pluginOptions'=>[
                'depends'=>['ddl-province'],
                'placeholder'=>'เลือกอำเภอ...',
                'url'=>Url::to(['/person-travel/get-amphur'])
            ]
        ]); ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'addr_tambon')->widget(DepDrop::classname(), [
            'options'=>['id'=>'ddl-tambon'],
            'data' =>$tambon,
            'pluginOptions'=>[
                'depends'=>['ddl-province', 'ddl-amphur'],
                'placeholder'=>'เลือกตำบล...',
                'url'=>Url::to(['/person-travel/get-tambon'])
            ]
        ]); ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'addr_vill_no')->widget(DepDrop::classname(), [
                 'options'=>['id'=>'ddl-village'],
            'data' =>$village,
            'pluginOptions'=>[
                'depends'=>['ddl-province', 'ddl-amphur','ddl-tambon'],
                'placeholder'=>'เลือกหมู่บ้าน...',
                'url'=>Url::to(['/person-travel/get-village'])
            ]
        ]); ?>
        </div>
    </div>




<div class="row">
        <div class="col-md-4">
          <?= $form->field($model, 'phone_number')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-4">
           
        </div>
        <div class="col-md-4"></div>
    </div>







        </div>
      
    </div>


<div class="panel panel-primary">
            <div class="panel-heading">
                <h4 class="panel-title"><i class="fa fa-user"></i> ข้อมูลการเดินทาง</h4>
            </div>
            <div class="panel-body">
         <div class="row">
        <div class="col-md-4"><h6><b>ต้นทาง ระบุ</b></h6></div></div>
        <div class="row">


                 <div class="col-md-3">
            <?= $form->field($model, 'origin_province')->widget(Select2::classname(), [
        'language' => 'th',
        'data' => ArrayHelper::map(Province::find()
                                          //  ->where(['changwatcode' => 27])
                                            ->orderBy('changwatname')
                                            ->all(),'changwatcode','changwatname'),
        'options' => [
            'id'=>'ddl-oprovince',
            'placeholder' => 'เลือกจังหวัด ...'],
        'pluginOptions' => [
            'allowClear' => true
                ],
            ]);
            ?>
        
    </div></div>

                <div class="row">
        <div class="col-md-4"><h6><b>ปลายทาง ระบุ</b></h6></div></div>
        <div class="row">

<div class="col-md-3"><?= $form->field($model, 'move_number')->textInput(['maxlength' => true]) ?></div>
                 <div class="col-md-3">
            <?= $form->field($model, 'move_province')->widget(Select2::classname(), [
        'language' => 'th',
        'data' => ArrayHelper::map(Province::find()
                                          //  ->where(['changwatcode' => 27])
                                            ->orderBy('changwatname')
                                            ->all(),'changwatcode','changwatname'),
        'options' => [
            'id'=>'ddl-mprovince',
            'placeholder' => 'เลือกจังหวัด ...'],
        'pluginOptions' => [
            'allowClear' => true
                ],
            ]);
            ?>
        
    </div>





    <div class="col-md-3">
            <?= $form->field($model, 'move_ampur')->widget(DepDrop::classname(), [
            'options'=>['id'=>'ddl-mamphur', 'placeholder' => 'เลือกอำเภอ...'],
            'data'=> $mamphur,
            'type' => DepDrop::TYPE_SELECT2,
            'select2Options'=> ['pluginOptions' => ['allowClear' => true]],
           // 'data'=> [],
            'pluginOptions'=>[
                'allowClear' => true,
                'depends'=>['ddl-mprovince'],
                'placeholder'=>'เลือกอำเภอ...',
                'url'=>Url::to(['/person-travel/get-amphur'])
            ]
        ]); ?>
        </div>

      

        <div class="col-md-3">
            <?= $form->field($model, 'move_tambon')->widget(DepDrop::classname(), [
            'options'=>['id'=>'ddl-mtambon', 'placeholder' => 'เลือกตำบล...'],
            'data' =>$mtambon,
            'type' => DepDrop::TYPE_SELECT2,
            'select2Options'=> ['pluginOptions' => ['allowClear' => true]],
            'pluginOptions'=>[
                'allowClear' => true,
                'depends'=>['ddl-mprovince', 'ddl-mamphur'],
                'placeholder'=>'เลือกตำบล...',
                'url'=>Url::to(['/person-travel/get-tambon'])
            ]
        ]); ?>
        </div>
<div class="col-md-4">
            <?= $form->field($model, 'move_vill_no')->widget(DepDrop::classname(), [
                 'options'=>['id'=>'ddl-mvillage','placeholder' => 'เลือกหมู่บ้าน...'],
            'data' =>$mvillage,
            'type' => DepDrop::TYPE_SELECT2,
            'select2Options'=> ['pluginOptions' => ['allowClear' => true]],
            'pluginOptions'=>[
                'depends'=>['ddl-mprovince', 'ddl-mamphur','ddl-mtambon'],
                'placeholder'=>'เลือกหมู่บ้าน...',
                'url'=>Url::to(['/person-travel/get-village'])
            ]
        ]); ?>
        </div>

            </div>    

            <div class="row">
    <div class="col-md-4">
                
            </div>
            </div>

     <div class="row">
        <div class="col-md-4">
             <?= $form->field($model, 'date_in')->widget(DateControl::classname(), ['type' => DateControl::FORMAT_DATE,]);?>   
        </div>
        <div class="col-md-4">
                
        </div>
    </div>  
    <div class="row">
    <div class="col-md-12">
                <?= $form->field($model, 'objective')->textInput(['maxlength' => true]) ?>

            </div>
            </div>

</div>
    </div>




 <div class="panel panel-primary">
            <div class="panel-heading">
                <h4 class="panel-title"><i class="fa fa-user"></i> ข้อมูลในช่วงเวลา 14 วันก่อนเข้าพัก เคยมีประวัติต่างๆ ดังนี้</h4>
            </div>
            <div class="panel-body">



	



 <div class="row">
     
 <div class="col-md-4">

<?= $form->field($model, 'temp')->textInput(['maxlength' => true]) ?>
 </div>
 <div class="col-md-12"><?= $form->field($model, 'q_sick_sign')->radioList(
            ArrayHelper::map(RiskType::find()->all(),
            'id',
            'status'),
            [
                'id'=>'q_sick_sign',
               
       ]); ?></div>   

        <div class="col-md-4">
           
            <?= $form->field($model, 'risk_from_risk_country')->widget(Select2::classname(), [
        'language' => 'de',
        'data' => ArrayHelper::map(Countryrisk::find()->all(),'countryname','countryname'),
        'options' => ['placeholder' => 'ระบุประเทศ ...'],
        'pluginOptions' => [
            'allowClear' => true
                ],
            ]);
            ?>    
        </div>  
        <div class="col-md-12">          
        <?= $form->field($model, 'q_from_risk_country')->radioList(
            ArrayHelper::map(RiskType::find()->all(),
            'id',
            'status'),
            [
                'id'=>'risk_place',
               
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

    </div>
    <div class="row">
        <div class="col-md-12">
            
            
        </div>
        <div class="col-md-12"> 
            
                
            </div>
        <div class="col-md-12">
            
                
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
            <?= $form->field($model, 'q_close_to_foreigner')->radioList(
            ArrayHelper::map(RiskType::find()->all(),
            'id',
            'status'),
            [
                'id'=>'q_close_to_foreigner',
               
       ]); ?>
                
            </div>
        <div class="col-md-12">
            
                
            </div>
        <div class="col-md-12">
            
                
            </div>
        <div class="col-md-12">
            
            
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
            

            </div>





            <div class="col-md-12">
            <?= $form->field($model, 'note')->textarea(['rows' => 6]) ?>
                
            </div>
           







        
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
</div>
    

    <div class="form-group">
        <?= Html::submitButton('บันทึก', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
