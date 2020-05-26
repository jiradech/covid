<?php

/* @var $this yii\web\View */

$this->registerJsFile($this->theme->baseUrl.'/assets/js/survey.js', ['depends' => [\yii\web\JqueryAsset::className()]]);



$this->title = 'My Yii Application';
$this->params['breadcrumbs'][] = 'Home';
?>
<style>

.required-asterisk, .required-message {
  color: rgb(196,59,29);
  font-weight: 400;
  font-size: 1.5rem;
  font-style: normal;
  color: #c43b1d;
}

.required-message {
  margin-top: 5px;
}

.required-asterisk {
  margin-left: 5px;
}

.text {
  border: 1px solid #bbb;
  padding: 0.4em 0.6em;
}

.question {
    margin-top: 10px;
    margin-bottom: 20px;
}

.answer {
	margin-left: 60px;
}

.question-container {
	padding: 30px;
}
</style>
        <!-- Page Header Section Start Here -->
        <section class="page-header" style="padding: 150px 0 36px;">
            <div class="page-header-shape">
                <img src="<?php echo $this->theme->baseUrl ?>/assets/images/banner/home-2/01.jpg" alt="banner-shape">
            </div>
            <div class="container">

                <div class="page-title">

                		
                    <h2>ระดับความเสี่ยงและคำแนะนำในการปฏิบัติตน COVID19

                    </h2>


                	


                </div>


            </div>
        </section>
		<!-- Page Header Section Ending Here -->
		
		<!-- Button trigger modal -->



		<!-- corona count section start here -->
        <section class="corona-count-section pt-0 padding-tb">
            <div class="container">
				<div class="corona-wrap">
					<div class="corona-count-top">
						
<div class="question-container"></div>
<a id="backBtn" href="#" class="lab-btn">« Back</a>
<a id="nextBtn" href="#" class="lab-btn">Continue »</a>
					</div>
					
				
				</div>
            </div>
        </section>
        <!-- corona count section ending here -->

