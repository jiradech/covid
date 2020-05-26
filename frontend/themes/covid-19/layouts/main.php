<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use common\widgets\Alert;
use yii\helpers\Url;




AppAsset::register($this);


$ampurcodefull = Yii::$app->getRequest()->getQueryParam('ampurcodefull');

$controller = Yii::$app->controller->id;
$action = Yii::$app->controller->action->id;



?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="th">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<link rel="shortcut icon" type="image/x-icon" href="<?php echo $this->theme->baseUrl ?>/assets/images/x-icon/01.png">


	<?= Html::csrfMetaTags() ?>
	<title>Covid-19 Sakaeo</title>
	<link href="https://fonts.googleapis.com/css?family=Mitr&display=swap" rel="stylesheet">

	<?php $this->head() ?>


	<link rel="stylesheet" href="https://unpkg.com/leaflet@1.6.0/dist/leaflet.css" integrity="sha512-xwE/Az9zrjBIphAcBb3F6JVqxf46+CDLwfLMHloNu6KEQCAWi6HcDUbeOfBIptF7tcCzusKFjFw2yuvEpDL9wQ==" crossorigin="" />

	<script src="https://unpkg.com/leaflet@1.6.0/dist/leaflet.js" integrity="sha512-gZwIG9x3wUXg2hdXF6+rVkLF/0Vi9U8D2Ntg4Ga5I5BZpVkVxlJWbSQtXPSiUTtC0TjtGOmxa1AJPuV0CPthew==" crossorigin=""></script>
</head>

<body>
	<?php $this->beginBody() ?>


	<?php
	// $case = 'NaN';
	// $recovered = 'NaN';
	// $admit = 'NaN';
	// $death = 'NaN';


	// $curl = curl_init();

	// curl_setopt_array($curl, array(
	// CURLOPT_URL => "https://opend.data.go.th/get-ckan/datastore_search?resource_id=93f74e67-6f76-4b25-8f5d-b485083100b6&limit=50000",
	// CURLOPT_RETURNTRANSFER => true,
	// CURLOPT_ENCODING => "",
	// CURLOPT_MAXREDIRS => 10,
	// CURLOPT_TIMEOUT => 30,
	// CURLOPT_FOLLOWLOCATION => true,
	// CURLOPT_MAXREDIRS => 3,
	// CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	// CURLOPT_CUSTOMREQUEST => "GET",
	// CURLOPT_HTTPHEADER => array(
	// "api-key: WqOC92X457Hen1j9a7a8LMzmHlqJuUrW"
	// )
	// ));

	// $response = curl_exec($curl);
	// $err = curl_error($curl);

	// curl_close($curl);

	// if ($err) {
	// //echo "cURL Error #:" . $err;
	// } else {
	// 	$obj = json_decode($response,true);


	// 	$case = 0;
	// 	$recovered = 0;
	// 	$admit = 0;
	// 	$death = 0;

	// 	foreach($obj['result']['records'] as $value){
	// 		$case++;
	// 		if ($value['Province'] == 'สระแก้ว') {
	// 			$admit++;
	// 		}
	//   		//echo $value['nation']; //change accordingly
	// 	}


	// //echo var_dump($obj)  ;
	// }
	?>

	<!-- Mobile Menu Start Here -->
	<div class="mobile-menu seo-bg">
		<nav class="mobile-header">
			<div class="header-logo">
				<a href="<?= Url::home() ?>"><img src="<?php echo $this->theme->baseUrl ?>/assets/images/logo/01.png" alt="logo"></a>
			</div>
			<div class="header-bar">
				<span></span>
				<span></span>
				<span></span>
			</div>
		</nav>

		<nav class="mobile-menu">
				<div class="mobile-menu-area">
					<div class="mobile-menu-area-inner">
					<ul class="lab-ul">
									<li><a href="<?= Url::home() ?>">Home</a>

									</li>
									<li<?=($controller == 'site' && $action == 'district' ? ' class="active"' : '')?>><a href="#">อำเภอ</a>
										<ul class="lab-ul">
											<li<?=($ampurcodefull == '2701' ? ' class="active"' : '')?>><a href="<?= Url::to(['site/district', 'ampurcodefull' => '2701'])?>">เมืองสระแก้ว</a></li>
                                            <li<?=($ampurcodefull == '2706' ? ' class="active"' : '')?>><a href="<?= Url::to(['site/district', 'ampurcodefull' => '2706'])?>">อรัญประเทศ</a></li>
                                            <li<?=($ampurcodefull == '2705' ? ' class="active"' : '')?>><a href="<?= Url::to(['site/district', 'ampurcodefull' => '2705'])?>">วัฒนานคร</a></li>
											<li<?=($ampurcodefull == '2703' ? ' class="active"' : '')?>><a href="<?= Url::to(['site/district', 'ampurcodefull' => '2703'])?>">ตาพระยา</a></li>
											<li<?=($ampurcodefull == '2704' ? ' class="active"' : '')?>><a href="<?= Url::to(['site/district', 'ampurcodefull' => '2704'])?>">วังน้ำเย็น</a></li>
											<li<?=($ampurcodefull == '2702' ? ' class="active"' : '')?>><a href="<?= Url::to(['site/district', 'ampurcodefull' => '2702'])?>">คลองหาด</a></li>
											<li<?=($ampurcodefull == '2707' ? ' class="active"' : '')?>><a href="<?= Url::to(['site/district', 'ampurcodefull' => '2707'])?>">เขาฉกรรจ์</a></li>
											<li<?=($ampurcodefull == '2708' ? ' class="active"' : '')?>><a href="<?= Url::to(['site/district', 'ampurcodefull' => '2708'])?>">โคกสูง</a></li>
											<li<?=($ampurcodefull == '2709' ? ' class="active"' : '')?>><a href="<?= Url::to(['site/district', 'ampurcodefull' => '2709'])?>">วังสมบูรณ์</a></li>
										</ul>
									</li>
									<li<?=($controller == 'site' && $action == 'quarantine' ? ' class="active"' : '')?>><a href="<?= Url::to(['site/quarantine']) ?>">ศูนย์กักตัว</a></li>
									<li<?=($controller == 'site' && $action == 'sick-case' ? ' class="active"' : '')?>><a href="<?= Url::to(['site/sick-case']) ?>">ผู้มีไข้/อาการบ่งชี้</a></li>
									<li<?=($action == 'lost-input' || $action == 'passdays' ? ' class="active"' : '')?>><a href="#">ข้อมูลเพื่อการปฏิบัติงาน</a>
										<ul class="lab-ul">
										<li<?=($controller == 'person' && $action == 'index' ? ' class="active"' : '')?>><a href="<?= Url::to(['person/index']) ?>">ค้นหารายชื่อบุคคลเฝ้าระวัง</a></li>
										<li<?=($controller == 'site' && $action == 'lost-input' ? ' class="active"' : '')?>><a href="<?= Url::to(['site/lost-input']) ?>">บุคคลขาดการติดตาม</a></li>
										<li<?=($controller == 'site' && $action == 'passdays' ? ' class="active"' : '')?>><a href="<?= Url::to(['site/passdays']) ?>">บุคคลอยู่ระหว่างเฝ้าระวัง/ครบระยะเฝ้าระวัง</a></li>
										<li<?=($controller == 'site' && $action == 'getindays' ? ' class="active"' : '')?>><a href="<?= Url::to(['site/getindays']) ?>">คุมไว้สังเกต</a></li>
										<li<?=($controller == 'person-hb' && $action == 'create' ? ' class="active"' : '')?>><a href="<?= Url::to(['person-hb/create']) ?>">โรงแรมและกิจการให้เช่าที่พัก</a></li>
										</ul>
									</li>
								</ul>
					</div>
				</div>
			</nav>
	</div>
	<!-- Mobile Menu Ending Here -->


	<!-- desktop menu start here -->
	<header class="header-section transparent-header" style="padding-top: 20px;">
		<div class="header-area">
			<div class="container">
				<div class="primary-menu">
					<div class="logo">
						<a href="<?= Url::home() ?>"><img src="<?php echo $this->theme->baseUrl ?>/assets/images/logo/02.png" alt="logo"></a>
					</div>
					<div class="main-area">

					<div class="main-menu">
								<ul class="lab-ul">
									<li><a href="<?= Url::home() ?>">Home</a>

									</li>
									<li<?=($controller == 'site' && $action == 'district' ? ' class="active"' : '')?>><a href="#">อำเภอ</a>
										<ul class="lab-ul">
											<li<?=($ampurcodefull == '2701' ? ' class="active"' : '')?>><a href="<?= Url::to(['site/district', 'ampurcodefull' => '2701'])?>">เมืองสระแก้ว</a></li>
                                            <li<?=($ampurcodefull == '2706' ? ' class="active"' : '')?>><a href="<?= Url::to(['site/district', 'ampurcodefull' => '2706'])?>">อรัญประเทศ</a></li>
                                            <li<?=($ampurcodefull == '2705' ? ' class="active"' : '')?>><a href="<?= Url::to(['site/district', 'ampurcodefull' => '2705'])?>">วัฒนานคร</a></li>
											<li<?=($ampurcodefull == '2703' ? ' class="active"' : '')?>><a href="<?= Url::to(['site/district', 'ampurcodefull' => '2703'])?>">ตาพระยา</a></li>
											<li<?=($ampurcodefull == '2704' ? ' class="active"' : '')?>><a href="<?= Url::to(['site/district', 'ampurcodefull' => '2704'])?>">วังน้ำเย็น</a></li>
											<li<?=($ampurcodefull == '2702' ? ' class="active"' : '')?>><a href="<?= Url::to(['site/district', 'ampurcodefull' => '2702'])?>">คลองหาด</a></li>
											<li<?=($ampurcodefull == '2707' ? ' class="active"' : '')?>><a href="<?= Url::to(['site/district', 'ampurcodefull' => '2707'])?>">เขาฉกรรจ์</a></li>
											<li<?=($ampurcodefull == '2708' ? ' class="active"' : '')?>><a href="<?= Url::to(['site/district', 'ampurcodefull' => '2708'])?>">โคกสูง</a></li>
											<li<?=($ampurcodefull == '2709' ? ' class="active"' : '')?>><a href="<?= Url::to(['site/district', 'ampurcodefull' => '2709'])?>">วังสมบูรณ์</a></li>
										</ul>
									</li>
									<li<?=($controller == 'site' && $action == 'quarantine' ? ' class="active"' : '')?>><a href="<?= Url::to(['site/quarantine']) ?>">ศูนย์กักตัว</a></li>
									<li<?=($controller == 'site' && $action == 'sick-case' ? ' class="active"' : '')?>><a href="<?= Url::to(['site/sick-case']) ?>">ผู้มีไข้/อาการบ่งชี้</a></li>
									<li<?=($action == 'lost-input' || $action == 'passdays' ? ' class="active"' : '')?>><a href="#">ข้อมูลเพื่อการปฏิบัติงาน</a>
										<ul class="lab-ul">
										<li<?=($controller == 'person' && $action == 'index' ? ' class="active"' : '')?>><a href="<?= Url::to(['person/index']) ?>">ค้นหารายชื่อบุคคลเฝ้าระวัง</a></li>
										<li<?=($controller == 'site' && $action == 'lost-input' ? ' class="active"' : '')?>><a href="<?= Url::to(['site/lost-input']) ?>">บุคคลขาดการติดตาม</a></li>
										<li<?=($controller == 'site' && $action == 'passdays' ? ' class="active"' : '')?>><a href="<?= Url::to(['site/passdays']) ?>">บุคคลอยู่ระหว่างเฝ้าระวัง/ครบระยะเฝ้าระวัง</a></li>
										<li<?=($controller == 'site' && $action == 'getindays' ? ' class="active"' : '')?>><a href="<?= Url::to(['site/getindays']) ?>">คุมไว้สังเกต</a></li>
										<li<?=($controller == 'person-hb' && $action == 'create' ? ' class="active"' : '')?>><a href="<?= Url::to(['person-hb/create']) ?>">โรงแรมและกิจการให้เช่าที่พัก</a></li>
										</ul>
										</ul>
									</li>
									

								</ul>
							</div>



					</div>
				</div>
			</div>
		</div>
	</header>
	<!-- desktop menu ending here -->



	<?= Alert::widget() ?>

	<?= $content ?>



	<!-- Footer Section Start Here -->
	<footer style="background-image: url(<?php echo $this->theme->baseUrl ?>/assets/css/bg-image/footer-bg.jpg);">

		<div class="footer-bottom">
			<div class="container">
				<div class="section-wrapper">
					<p>&copy; 2020 All Rights Reserved. Sakaeo Provincial Health Office</p>
					<li><a href="<?= Url::toRoute('/site/login'); ?>"><i class="icon ion-ios-folder-outline"></i> Login</a></li>
					<li><a href="<?= Url::toRoute('/site/logout') ?>" data-method="post"><i class="icon ion-power"></i> Sign Out</a></li>
				</div>
			</div>
		</div>
	</footer>
	<!-- Footer Section Ending Here -->

	<!-- scrollToTop start here -->
	<a href="#" class="scrollToTop"><i class="icofont-swoosh-up"></i><span class="pluse_1"></span><span class="pluse_2"></span></a>
	<!-- scrollToTop ending here -->





	<?php $this->endBody() ?>

</body>

</html>
<?php $this->endPage() ?>
<script>
	$(document).ready(function() {
		$('[data-toggle="tooltip"]').tooltip({
			html: true
		});
		$("#example0").DataTable({
			//pagingType: full_numbers,
			//scrollY:        300,

			paging: true,
			pageLength: 50,
			dom: 'Bfrtip',
			buttons: [
				'copy', 'excel', 'pdf'
			]

		});
		$("#example").DataTable({
			//pagingType: full_numbers,
			//scrollY:        300,
			paging: true,
			pageLength: 50,
			dom: 'Bfrtip',
			buttons: [
				'copy', 'excel', 'pdf'
			]

		});
		$("#total-summary").DataTable({
			scrollX: true,
			scrollCollapse: true,
			paging: false,
			fixedColumns: true,
			dom: 'Bfrtip',
			buttons: [
				'copy', 'excel', 'pdf'
			]
		});


		$("#table_person").DataTable({
			paging: true,
			pageLength: 50,
			dom: 'Bfrtip',
			buttons: [
				'copy', 'excel', 'pdf'
			]
		});



		//$('#popoverData').popover();
		//$('.popoverOption').popover({ trigger: "hover" });


	});
</script>