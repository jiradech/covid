<?php

/* @var $this yii\web\View */

use yii\helpers\Url;
use yii\web\View;
use app\models\Covid19th;

$this->registerJsFile('https://www.gstatic.com/charts/loader.js', []);


$this->title = 'My Yii Application';
$this->params['breadcrumbs'][] = 'Home';

$modelCovidTH =  Covid19th::findOne(1);

$trend_data = "";
$cumulative_data = 0;
$d_value = 0;
$cumulative_pos = 0;
$pos_value = 0;

foreach ($trend as $value) {
	$d = 0;
	for ($x = $days; $x >= 0; $x--) {
		$d++;
		if ($value['d' . $d . '_case'] == '') {
			$d_value = 0;
		} else {
			$d_value = $value['d' . $d . '_case'];
		}

		if ($value['d' . $d . '_pos'] == '') {
			$pos_value = 0;
		} else {
			$pos_value = $value['d' . $d . '_pos'];
		}

		$cumulative_data += $d_value;
		$cumulative_pos += $pos_value;


		$trend_data .= "[new Date(" . $value['d' . $d . '_y'] . ", " . $value['d' . $d . '_m'] . ", " . $value['d' . $d . '_d'] . "),  " . $d_value . ",  " . $cumulative_data . "," . $pos_value . "," . $cumulative_pos . "],"; //$value['d'];
	}
}
?>


<style>
	.css-icon {}

	.white_ring {
		border: 3px solid white;
		-webkit-border-radius: 30px;
		height: 20px;
		width: 20px;
		-webkit-animation: pulsate 2s ease-out;
		-webkit-animation-iteration-count: infinite;
		/*opacity: 0.0*/
	}

	.red_ring {
		border: 12px solid #dc3b30;
		-webkit-border-radius: 30px;
		height: 20px;
		width: 20px;
		-webkit-animation: pulsate 1.5s ease-out;
		-webkit-animation-iteration-count: infinite;
		opacity: 1
	}


	.yellow_ring {
		border: 5px solid #ffc932;
		-webkit-border-radius: 30px;
		height: 8px;
		width: 8px;
		/* -webkit-animation: pulsate 1.7s ease-out;
	    -webkit-animation-iteration-count: infinite;  */
		opacity: 1
	}

	.orange_ring {
		border: 4px solid #f76d0b;
		-webkit-border-radius: 30px;
		height: 20px;
		width: 20px;
		-webkit-animation: pulsate 1.7s ease-out;
		-webkit-animation-iteration-count: infinite;
		opacity: 1
	}

	.green_ring {
		border: 5px solid #6aa728;
		-webkit-border-radius: 30px;
		height: 8px;
		width: 8px;
		/*	    -webkit-animation: pulsate 2.9s ease-out;
	    -webkit-animation-iteration-count: infinite; */
		opacity: 0.5
	}

	.badge {
		font-size: 11px;
	}

	.badge-pui {
		color: #fff;
		background-color: #f76d0b;
	}

	.table-wrapper {
		/*    border: 1px solid red;
    width: 100px;
    height: 50px;*/
		overflow: auto;
	}


	.widget.widget-archive .widget-wrapper li a,
	.widget.widget-category .widget-wrapper li p {
		color: #696969;
		padding: 10px 25px;
		margin: 0px;
		font-size: 14px;
	}

	@media (max-width: 1200px) {
		#mapid {
			width: 100vw;
			height: 700px;
		}
	}

	@media (max-width: 991px) {
		#mapid {
			width: 100vw;
			height: 80vw;
		}
	}


	@media (max-width: 991px) {
		.page-title {
			margin-top: -136px;
			padding-bottom: 30px;
		}

		.corona-count-section .countcorona .countcorona-area {
			padding: 30px 0;
		}
	}


	div#example2_length {
		display: inline-block;
		float: left;
	}

	@-webkit-keyframes pulsate {
		0% {
			-webkit-transform: scale(0.1, 0.1);
			opacity: 0.0;
		}

		50% {
			opacity: 1.0;
		}

		100% {
			-webkit-transform: scale(1.2, 1.2);
			opacity: 0.0;
		}
	}
</style>


<!-- Page Header Section Start Here -->
<section class="page-header" style="padding: 150px 0 0px;">
	<div class="page-header-shape">
		<img src="<?php echo $this->theme->baseUrl ?>/assets/images/banner/home-2/01.jpg" alt="banner-shape">
	</div>

</section>
<!-- Page Header Section Ending Here -->

<!-- corona count section start here -->
<section class="corona-count-section pt-0 padding-tb">
	<div class="container">
		<div class="corona-wrap">
			<div class="corona-count-top">
			<div class="row justify-content-center align-items-center head-top">
					<div class="col-xl-3 col-md-6 col-12">
						<div class="corona-item">
							<div class="corona-inner">
								<div class="corona-thumb">
									<img src="<?php echo $this->theme->baseUrl ?>/assets/images/corona/001.png" alt="corona">
								</div>
								<div class="corona-content">
									<p>ติดเชื้อสะสม</p>
									<h5><span>อ.<?= $gis[0]['ampurname'] ?></span> <?= number_format($covid_dis[0]['infect']) ?></h5>
									<h5><span>จ.สระแก้ว</span> <?= number_format($covid[0]['infect']) ?></h5>
									<h5><span>ประเทศ</span> <?=number_format($modelCovidTH->confirmed)?></h5>

								</div>
							</div>
						</div>
					</div>
					<div class="col-xl-3 col-md-6 col-12">
						<div class="corona-item">
							<div class="corona-inner">
								<div class="corona-thumb">
									<img src="<?php echo $this->theme->baseUrl ?>/assets/images/corona/002.png" alt="corona">
								</div>
								<div class="corona-content">
									<p>หายแล้ว</p>
									<h5><span>อ.<?= $gis[0]['ampurname'] ?></span> <?= number_format($covid_dis[0]['cure']) ?></h5>
									<h5><span>จ.สระแก้ว</span> <?= number_format($covid[0]['cure']) ?></h5>
									<h5><span>ประเทศ</span> <?=number_format($modelCovidTH->recovered)?></h5>

								</div>
							</div>
						</div>
					</div>
					<div class="col-xl-3 col-md-6 col-12">
						<div class="corona-item">
							<div class="corona-inner">
								<div class="corona-thumb">
									<img src="<?php echo $this->theme->baseUrl ?>/assets/images/corona/003.png" alt="corona">
								</div>
								<div class="corona-content">
									<p>รักษาอยู่ใน รพ.</p>
									<h5><span>อ.<?= $gis[0]['ampurname'] ?></span> <?= number_format($covid_dis[0]['treat']) ?></h5>
									<h5><span>จ.สระแก้ว</span> <?= number_format($covid[0]['treat']) ?></h5>
									<h5><span>ประเทศ</span> <?=number_format($modelCovidTH->hospitalized)?></h5>

								</div>
							</div>
						</div>
					</div>
					<div class="col-xl-3 col-md-6 col-12">
						<div class="corona-item">
							<div class="corona-inner">
								<div class="corona-thumb">
									<img src="<?php echo $this->theme->baseUrl ?>/assets/images/corona/004.png" alt="corona">
								</div>
								<div class="corona-content">
									<p>เสียชีวิต</p>
									<h5><span>อ.<?= $gis[0]['ampurname'] ?></span> <?= number_format($covid_dis[0]['dead']) ?></h5>
									<h5><span>จ.สระแก้ว</span> <?= number_format($covid[0]['dead']) ?></h5>
									<h5><span>ประเทศ</span> <?=number_format($modelCovidTH->deaths)?></h5>

								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="row justify-content-center align-items-center">
					<div class="col-sm-2">
						<h5>สถานะประชากรเคลื่อนย้าย :<br>
							อ.<?= $gis[0]['ampurname'] ?></h5>
					</div>

					<div class="col-sm-2">
						<div class="corona-item">
							<div class="corona-inner">
								<div class="corona-thumb" style="margin: 0 auto;">
									<img src="<?php echo $this->theme->baseUrl ?>/assets/images/corona/02.png" alt="corona">
								</div>
								<div class="corona-content text-center">
									<p>กลุ่มเสี่ยงวันนี้</p>
									<h3><?= number_format($sumary[0]['today']) ?></h3>

								</div>
							</div>
						</div>
					</div>

					<div class="col-sm-2">
						<div class="corona-item">
							<div class="corona-inner">
								<div class="corona-thumb" style="margin: 0 auto;">
									<img src="<?php echo $this->theme->baseUrl ?>/assets/images/corona/01.png" alt="corona">
								</div>
								<div class="corona-content text-center">
									<p>กลุ่มเสี่ยงสะสม</p>
									<h3><?= number_format($sumary[0]['total']) ?></h3>

								</div>
							</div>
						</div>
					</div>



					<div class="col-sm-2">
						<div class="corona-item">
							<div class="corona-inner">
								<div class="corona-thumb" style="margin: 0 auto;">
									<img src="<?php echo $this->theme->baseUrl ?>/assets/images/corona/03x.png" alt="corona">
								</div>
								<div class="corona-content text-center">
									<p>อยู่ระหว่างเฝ้าระวัง</p>
									<h3><?= number_format($sumary[0]['total_followed']-$sumary[0]['pass14days']) ?></h3>

								</div>
							</div>
						</div>
					</div>

					<div class="col-sm-2">
						<div class="corona-item">
							<div class="corona-inner">
								<div class="corona-thumb" style="margin: 0 auto;">
									<img src="<?php echo $this->theme->baseUrl ?>/assets/images/corona/05.png" alt="corona">
								</div>
								<div class="corona-content text-center">
									<p>มีไข้/มีอาการบ่งชี้</p>
									<a href="<?= Url::to(['sick-case']) ?>">
										<h3><?= number_format($sumary[0]['one_sign']) ?></h3>
									</a>

								</div>
							</div>
						</div>
					</div>

					<div class="col-sm-2">
						<div class="corona-item">
							<div class="corona-inner">
								<div class="corona-thumb" style="margin: 0 auto;">
									<img src="<?php echo $this->theme->baseUrl ?>/assets/images/corona/04.png" alt="corona">
								</div>
								<div class="corona-content text-center">
									<p>ครบระยะเฝ้าระวัง</p>
									<h3><?= number_format($sumary[0]['pass14days']) ?></h3>

								</div>
							</div>
						</div>
					</div>

				</div>
			</div>
			<div class="corona-count-bottom row">
				<div class="gmaps col-xl-8 col-md-8 col-xs-8 col-sm-8" id="mapid">


				</div>
				<div class="col-xl-4 col-md-4 col-xs-4 col-sm-4">
					<div class="widget widget-category">
						<div class="widget-header">
							<h5>รายละเอียด</h5>
						</div>
						<ul class="widget-wrapper lab-ul">
							<li>
								<p class="d-flex flex-wrap justify-content-between"><span><i class="icofont-double-right"></i>หมู่บ้าน</span><span id="vill">-</span></p>
							</li>
							<li>
								<p class="d-flex flex-wrap justify-content-between"><span><i class="icofont-double-right"></i>อำเภอ</span><span id="dist">-</span></p>
							</li>
							<li>
								<p class="d-flex flex-wrap justify-content-between"><span><i class="icofont-double-right"></i>จำนวนเฝ้าระวัง/ครบระยะเฝ้าระวัง</span><span id="detected">0/0</span></p>
							</li>
							<li>
								<p class="d-flex flex-wrap justify-content-between"><span><i class="icofont-double-right"></i>มีไข้หรืออาการแสดงบ่งชี้</span><span id="sick_result">0</span></p>
							</li>
							<li>
								<p class="d-flex flex-wrap justify-content-between"><span><i class="icofont-double-right"></i>ส่งตรวจเชื้อ</span><span id="waitting_result">0</span></p>
							</li>
							<li>
								<p class="d-flex flex-wrap justify-content-between"><span><i class="icofont-double-right"></i>จำนวนผู้ติดเชื้อ</span><span id="comfirmed">0</span></p>
							</li>
							<li>
								<p class="d-flex flex-wrap justify-content-between"><span><i class="icofont-double-right"></i>จำนวนเสียชีวิต</span><span id="death">0</span></p>
							</li>
							<li>
								<p class="d-flex flex-wrap justify-content-between"><span><i class="icofont-double-right"></i>เสียชีวิตรายใหม่</span><span id="newdeath">0</span></p>
							</li>
							<li>
								<p class="d-flex flex-wrap justify-content-between"><span><i class="icofont-double-right"></i>จำนวนรักษาหาย</span><span id="recovered">0</span></p>
							</li>
							<li>
								<p class="d-flex flex-wrap justify-content-between"><span><i class="icofont-double-right"></i>อยู่ระหว่างการรักษา</span><span id="admit">0</span></p>
							</li>
							<li>
								<p class="d-flex flex-wrap justify-content-between"><span><i class="icofont-double-right"></i>ผู้ป่วยวิกฤต</span><span id="coma">0</span></p>
							</li>
						</ul>
						<a href="#" class="lab-btn style-2" id="vill-info"><span>ข้อมูลเพิ่มเติม</span></a>
					</div>





				</div>
				<div>
					<span class="badge badge-success">สีเขียว = มีประชากรเคลื่อนย้าย ไม่มี PUI</span>
					<span class="badge badge-warning">สีเหลือง = มีไข้ หรือมีอาการแสดงบ่งชี้</span>
					<span class="badge badge-pui">สีแดง = มี PUI</span>
					<span class="badge badge-danger">สีแดง = มี PUI Positive</span>
					<span class="badge">สีขาว = ไม่มีประชากรกลุ่มเสี่ยง</span>

				</div>

			</div>

			<div class="countcorona">
				<div class="countcorona-area">
					<div id="calendar_basic" style="width: 1110px; height: 200px;"></div>
				</div>
			</div>


			<div class="countcorona">
				<div class="countcorona-area">
					<div id="donutchart" style="height: 400px;" class="col-sm-6"></div>
					<div id="barchart" style="height: 400px;" class="col-sm-6"></div>
				</div>

			</div>


			<div class="countcorona">
				<div class="countcorona-area">
					<div id="barchart2" style="height: 400px;" class="col-sm-5"></div>
					<div id="trendchart" style="height: 400px;" class="col-sm-7"></div>
				</div>

			</div>


			<script>
				var mymap = L.map('mapid', {
					scrollWheelZoom: false
				}).setView([13.750057, 102.376099], 9);

				L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token=pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpejY4NXVycTA2emYycXBndHRqcmZ3N3gifQ.rJcFIG214AriISLbB6B5aw', {
					maxZoom: 18,
					attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, ' +
						'<a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, ' +
						'Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
					id: 'mapbox/streets-v11',
					tileSize: 512,
					zoomOffset: -1
				}).addTo(mymap);





				var geojsonFeature = {
					"type": "FeatureCollection",
					"features": [
						<?php if ($ampurcodefull == '2701') { ?> {
								"type": "Feature",
								"properties": {
									"id": "2701",
									"type": "3",
									"name": " อ.เมืองสระแก้ว จ.สระแก้ว",
									"zone": "",
									"data": " - "
								},
								"geometry": {
									"type": "MultiPolygon",
									"coordinates": [
										[
											[
												[102.221398, 14.142681],
												[102.248847, 14.139371],
												[102.268913, 14.14578],
												[102.274262, 14.127809],
												[102.29074, 14.13412],
												[102.309937, 14.11963],
												[102.327628, 14.126472],
												[102.326828, 14.09424],
												[102.313919, 14.08549],
												[102.308114, 14.065571],
												[102.313331, 14.025301],
												[102.291741, 14.003132],
												[102.288589, 13.97],
												[102.270125, 13.96228],
												[102.253563, 13.933632],
												[102.213013, 13.933841],
												[102.212096, 13.91954],
												[102.19921, 13.91283],
												[102.183678, 13.873932],
												[102.160499, 13.85473],
												[102.160928, 13.841951],
												[102.178946, 13.822271],
												[102.17968, 13.791019],
												[102.199889, 13.769933],
												[102.224823, 13.766642],
												[102.233787, 13.752231],
												[102.230781, 13.710871],
												[102.209311, 13.709301],
												[102.188088, 13.695521],
												[102.152351, 13.696861],
												[102.144515, 13.72827],
												[102.090821, 13.76111],
												[102.04184, 13.741521],
												[102.035058, 13.702391],
												[102.000052, 13.71135],
												[101.965072, 13.682129],
												[101.946313, 13.677521],
												[101.939133, 13.70923],
												[101.943063, 13.736461],
												[101.920349, 13.752691],
												[101.943589, 13.764192],
												[101.948906, 13.800131],
												[101.944802, 13.808721],
												[101.926819, 13.814041],
												[101.921997, 13.831581],
												[101.929969, 13.852911],
												[101.905824, 13.881581],
												[101.9134, 13.93383],
												[101.952637, 13.933],
												[101.93914, 13.957991],
												[101.941308, 14.015323],
												[101.992798, 14.031361],
												[102.038201, 14.035461],
												[102.048737, 14.075111],
												[102.085586, 14.086612],
												[102.100799, 14.102171],
												[102.108177, 14.149561],
												[102.137268, 14.136021],
												[102.167412, 14.140039],
												[102.168747, 14.170912],
												[102.18477, 14.194351],
												[102.213516, 14.17081],
												[102.213218, 14.15102],
												[102.221398, 14.142681]
											]
										]
									]
								}
							},

						<?php }
						if ($ampurcodefull == '2702') { ?> {
								"type": "Feature",
								"properties": {
									"id": "2702",
									"type": "3",
									"name": " อ.คลองหาด จ.สระแก้ว",
									"zone": "",
									"data": " - "
								},
								"geometry": {
									"type": "MultiPolygon",
									"coordinates": [
										[
											[
												[102.330131, 13.65193],
												[102.341895, 13.645282],
												[102.336731, 13.61533],
												[102.327949, 13.586961],
												[102.314088, 13.574271],
												[102.315193, 13.551332],
												[102.304429, 13.54247],
												[102.300149, 13.51585],
												[102.314407, 13.512411],
												[102.319558, 13.47349],
												[102.358978, 13.474329],
												[102.362601, 13.403919],
												[102.346862, 13.34566],
												[102.361314, 13.311551],
												[102.349702, 13.29654],
												[102.33455, 13.31561],
												[102.31244, 13.28581],
												[102.285072, 13.302742],
												[102.26783, 13.333451],
												[102.23092, 13.34909],
												[102.211511, 13.374981],
												[102.215522, 13.399801],
												[102.231713, 13.410021],
												[102.251106, 13.44777],
												[102.209266, 13.486581],
												[102.207946, 13.503812],
												[102.195342, 13.52173],
												[102.16916, 13.542872],
												[102.162192, 13.559441],
												[102.167428, 13.567559],
												[102.14408, 13.58698],
												[102.137307, 13.616212],
												[102.198959, 13.5894],
												[102.244278, 13.622821],
												[102.276657, 13.61667],
												[102.301309, 13.633779],
												[102.300438, 13.642442],
												[102.311592, 13.650903],
												[102.330131, 13.65193]
											]
										]
									]
								}
							},
						<?php }
						if ($ampurcodefull == '2703') { ?> {
								"type": "Feature",
								"properties": {
									"id": "2703",
									"type": "3",
									"name": " อ.ตาพระยา จ.สระแก้ว",
									"zone": "",
									"data": " - "
								},
								"geometry": {
									"type": "MultiPolygon",
									"coordinates": [
										[
											[
												[102.938491, 14.16421],
												[102.942641, 14.14694],
												[102.927048, 14.13007],
												[102.925231, 14.10394],
												[102.902779, 14.081381],
												[102.899819, 14.049822],
												[102.913352, 14.01533],
												[102.868638, 14.002279],
												[102.817619, 13.96347],
												[102.805, 13.941082],
												[102.784531, 13.930491],
												[102.748131, 13.9651],
												[102.742187, 13.993881],
												[102.681961, 13.99613],
												[102.671288, 13.96329],
												[102.685195, 13.95578],
												[102.690781, 13.935911],
												[102.64972, 13.908933],
												[102.638618, 13.93148],
												[102.613587, 13.94779],
												[102.590241, 13.94921],
												[102.581016, 13.961471],
												[102.578689, 13.97082],
												[102.602371, 13.996192],
												[102.564011, 13.981671],
												[102.560027, 14.015072],
												[102.536529, 14.044701],
												[102.54013, 14.071702],
												[102.533821, 14.095261],
												[102.512482, 14.091291],
												[102.49984, 14.107482],
												[102.503678, 14.130519],
												[102.518914, 14.13995],
												[102.552207, 14.133639],
												[102.608459, 14.16973],
												[102.635293, 14.14754],
												[102.657822, 14.164],
												[102.686829, 14.14777],
												[102.69683, 14.12946],
												[102.736763, 14.13985],
												[102.764069, 14.13755],
												[102.793923, 14.17084],
												[102.801499, 14.15804],
												[102.813651, 14.170591],
												[102.831071, 14.156369],
												[102.83281, 14.16663],
												[102.864241, 14.14723],
												[102.871469, 14.148531],
												[102.878609, 14.17199],
												[102.900612, 14.162559],
												[102.932153, 14.175521],
												[102.938491, 14.16421]
											]
										]
									]
								}
							},
						<?php }
						if ($ampurcodefull == '2704') { ?> {
								"type": "Feature",
								"properties": {
									"id": "2704",
									"type": "3",
									"name": " อ.วังน้ำเย็น จ.สระแก้ว",
									"zone": "",
									"data": " - "
								},
								"geometry": {
									"type": "MultiPolygon",
									"coordinates": [
										[
											[
												[102.155289, 13.650331],
												[102.143729, 13.627701],
												[102.129058, 13.624271],
												[102.141373, 13.610922],
												[102.13861, 13.597849],
												[102.167266, 13.56786],
												[102.162192, 13.559441],
												[102.168975, 13.543152],
												[102.195342, 13.52173],
												[102.22068, 13.47574],
												[102.190467, 13.46413],
												[102.165162, 13.470671],
												[102.14199, 13.449081],
												[102.050437, 13.451731],
												[101.995118, 13.440789],
												[101.937668, 13.442619],
												[101.94085, 13.461281],
												[101.933693, 13.471031],
												[101.94072, 13.489321],
												[101.955696, 13.4933],
												[101.962859, 13.487381],
												[101.993416, 13.499871],
												[102.002708, 13.512972],
												[101.999038, 13.531091],
												[102.026413, 13.544513],
												[102.04122, 13.572601],
												[102.075364, 13.569551],
												[102.095719, 13.57591],
												[102.102372, 13.569861],
												[102.094078, 13.594291],
												[102.062897, 13.617431],
												[102.072121, 13.639919],
												[102.155289, 13.650331]
											]
										]
									]
								}
							},
						<?php }
						if ($ampurcodefull == '2705') { ?> {
								"type": "Feature",
								"properties": {
									"id": "2705",
									"type": "3",
									"name": " อ.วัฒนานคร จ.สระแก้ว",
									"zone": "",
									"data": " - "
								},
								"geometry": {
									"type": "MultiPolygon",
									"coordinates": [
										[
											[
												[102.391708, 14.143342],
												[102.424218, 14.156463],
												[102.437995, 14.142161],
												[102.453637, 14.142011],
												[102.470017, 14.130852],
												[102.484786, 14.139761],
												[102.501228, 14.13529],
												[102.50177, 14.102712],
												[102.512801, 14.091191],
												[102.533821, 14.095261],
												[102.54013, 14.071702],
												[102.53662, 14.04437],
												[102.560027, 14.015072],
												[102.564423, 13.985642],
												[102.555428, 13.964211],
												[102.524985, 13.937841],
												[102.546029, 13.901881],
												[102.514312, 13.87741],
												[102.476342, 13.882472],
												[102.465851, 13.8657],
												[102.436019, 13.857431],
												[102.403993, 13.81908],
												[102.397431, 13.80259],
												[102.415284, 13.766541],
												[102.428566, 13.747711],
												[102.449867, 13.74117],
												[102.461616, 13.72595],
												[102.433663, 13.718812],
												[102.43145, 13.708341],
												[102.398208, 13.694051],
												[102.386102, 13.669022],
												[102.367287, 13.659269],
												[102.351486, 13.66269],
												[102.340553, 13.644441],
												[102.313461, 13.651912],
												[102.276657, 13.61667],
												[102.244278, 13.622821],
												[102.240517, 13.614329],
												[102.194511, 13.59028],
												[102.160538, 13.60976],
												[102.192063, 13.624811],
												[102.160859, 13.687351],
												[102.209977, 13.709531],
												[102.22963, 13.709552],
												[102.234489, 13.749899],
												[102.225097, 13.766441],
												[102.199889, 13.769933],
												[102.17968, 13.791019],
												[102.178946, 13.822271],
												[102.160928, 13.841951],
												[102.160661, 13.855321],
												[102.183678, 13.873932],
												[102.19921, 13.91283],
												[102.212096, 13.91954],
												[102.213013, 13.933841],
												[102.253563, 13.933632],
												[102.270125, 13.96228],
												[102.288589, 13.97],
												[102.291741, 14.003132],
												[102.313331, 14.025301],
												[102.308114, 14.065571],
												[102.313919, 14.08549],
												[102.326828, 14.09424],
												[102.327628, 14.126472],
												[102.334479, 14.11939],
												[102.359192, 14.13054],
												[102.360992, 14.163489],
												[102.391708, 14.143342]
											]
										]
									]
								}
							},
						<?php }
						if ($ampurcodefull == '2706') { ?> {
								"type": "Feature",
								"properties": {
									"id": "2706",
									"type": "3",
									"name": " อ.อรัญประเทศ จ.สระแก้ว",
									"zone": "",
									"data": " - "
								},
								"geometry": {
									"type": "MultiPolygon",
									"coordinates": [
										[
											[
												[102.505371, 13.87761],
												[102.514312, 13.87741],
												[102.55156, 13.843],
												[102.656959, 13.735411],
												[102.582442, 13.698671],
												[102.550309, 13.658159],
												[102.57849, 13.626839],
												[102.625969, 13.60699],
												[102.576042, 13.601872],
												[102.564505, 13.576961],
												[102.528342, 13.562591],
												[102.48191, 13.570791],
												[102.440628, 13.558071],
												[102.422853, 13.56956],
												[102.394692, 13.565401],
												[102.36821, 13.576382],
												[102.360778, 13.563051],
												[102.341599, 13.557622],
												[102.335648, 13.538981],
												[102.365516, 13.502911],
												[102.359702, 13.474912],
												[102.322648, 13.471851],
												[102.31453, 13.512101],
												[102.300422, 13.515283],
												[102.301117, 13.52891],
												[102.315102, 13.550992],
												[102.314393, 13.575039],
												[102.329018, 13.589332],
												[102.346832, 13.659871],
												[102.377922, 13.663912],
												[102.394227, 13.676791],
												[102.40216, 13.69729],
												[102.43145, 13.708341],
												[102.433663, 13.718812],
												[102.46173, 13.726251],
												[102.449867, 13.74117],
												[102.424537, 13.752661],
												[102.397958, 13.80584],
												[102.436019, 13.857431],
												[102.465851, 13.8657],
												[102.477935, 13.882969],
												[102.505371, 13.87761]
											]
										]
									]
								}
							},
						<?php }
						if ($ampurcodefull == '2707') { ?> {
								"type": "Feature",
								"properties": {
									"id": "2707",
									"type": "3",
									"name": " อ.เขาฉกรรจ์ จ.สระแก้ว",
									"zone": "",
									"data": " - "
								},
								"geometry": {
									"type": "MultiPolygon",
									"coordinates": [
										[
											[
												[102.094352, 13.760711],
												[102.144515, 13.72827],
												[102.151756, 13.69719],
												[102.170937, 13.69251],
												[102.162293, 13.682211],
												[102.192063, 13.624811],
												[102.158828, 13.609381],
												[102.136949, 13.617452],
												[102.129058, 13.624271],
												[102.143729, 13.627701],
												[102.158432, 13.652411],
												[102.151711, 13.654289],
												[102.072274, 13.64004],
												[102.062897, 13.617431],
												[102.094078, 13.594291],
												[102.101959, 13.5693],
												[102.095719, 13.57591],
												[102.075364, 13.569551],
												[102.04122, 13.572601],
												[102.026413, 13.544513],
												[101.999038, 13.531091],
												[102.002708, 13.512972],
												[101.993416, 13.499871],
												[101.962859, 13.487381],
												[101.943931, 13.491582],
												[101.934379, 13.47447],
												[101.915597, 13.476371],
												[101.894661, 13.51846],
												[101.87388, 13.523472],
												[101.861748, 13.53855],
												[101.898713, 13.546931],
												[101.915367, 13.584211],
												[101.911727, 13.603601],
												[101.939628, 13.60005],
												[101.939904, 13.607052],
												[101.947938, 13.606051],
												[101.946671, 13.62299],
												[101.955826, 13.618541],
												[101.942497, 13.648999],
												[101.946313, 13.677521],
												[101.965072, 13.682129],
												[102.000052, 13.71135],
												[102.035058, 13.702391],
												[102.04184, 13.741521],
												[102.094352, 13.760711]
											]
										]
									]
								}
							},
						<?php }
						if ($ampurcodefull == '2708') { ?> {
								"type": "Feature",
								"properties": {
									"id": "2708",
									"type": "3",
									"name": " อ.โคกสูง จ.สระแก้ว",
									"zone": "",
									"data": " - "
								},
								"geometry": {
									"type": "MultiPolygon",
									"coordinates": [
										[
											[
												[102.588142, 13.951609],
												[102.613587, 13.94779],
												[102.635918, 13.93349],
												[102.64972, 13.908933],
												[102.690781, 13.935911],
												[102.685195, 13.95578],
												[102.671288, 13.96329],
												[102.681961, 13.99613],
												[102.742187, 13.993881],
												[102.748131, 13.9651],
												[102.784798, 13.930521],
												[102.76973, 13.85429],
												[102.725052, 13.791072],
												[102.733681, 13.772221],
												[102.718751, 13.772832],
												[102.656959, 13.735411],
												[102.517829, 13.872229],
												[102.515053, 13.87839],
												[102.546601, 13.904582],
												[102.527169, 13.925281],
												[102.524985, 13.937841],
												[102.555428, 13.964211],
												[102.564011, 13.981671],
												[102.597877, 13.99791],
												[102.578689, 13.97082],
												[102.588142, 13.951609]
											]
										]
									]
								}
							},
						<?php }
						if ($ampurcodefull == '2709') { ?> {
								"type": "Feature",
								"properties": {
									"id": "2709",
									"type": "3",
									"name": " อ.วังสมบูรณ์ จ.สระแก้ว",
									"zone": "",
									"data": " - "
								},
								"geometry": {
									"type": "MultiPolygon",
									"coordinates": [
										[
											[
												[102.22068, 13.47574],
												[102.240417, 13.464011],
												[102.251557, 13.446241],
												[102.231713, 13.410021],
												[102.215522, 13.399801],
												[102.210738, 13.376832],
												[102.231811, 13.348361],
												[102.275329, 13.32724],
												[102.271981, 13.32146],
												[102.296089, 13.288221],
												[102.293548, 13.264689],
												[102.275108, 13.254351],
												[102.242881, 13.263222],
												[102.20491, 13.257061],
												[102.189408, 13.301101],
												[102.153586, 13.29706],
												[102.13903, 13.33103],
												[102.13095, 13.334611],
												[102.119301, 13.276431],
												[102.109977, 13.263089],
												[102.073226, 13.254481],
												[102.058189, 13.239671],
												[102.02974, 13.235681],
												[102.036179, 13.28266],
												[102.012971, 13.289541],
												[101.986519, 13.337392],
												[101.990471, 13.350861],
												[101.97467, 13.38493],
												[101.976249, 13.406431],
												[101.937668, 13.442619],
												[101.995118, 13.440789],
												[102.050437, 13.451731],
												[102.14199, 13.449081],
												[102.165162, 13.470671],
												[102.190467, 13.46413],
												[102.22068, 13.47574]
											]
										]
									]
								}
							}
						<?php } ?>

					]
				};

				var myStyle = {
					"color": "#7c7c7c",
					"weight": 2,
					"opacity": 1,
					"fillColor": "none",
				};

				geojson = L.geoJSON(geojsonFeature, {
					style: myStyle
				}).addTo(mymap);

				mymap.fitBounds(geojson.getBounds());


				var pin = L.marker([0, 0]).addTo(mymap);



				<?php

				$ring_color = '';

				foreach ($gis as $value) {
					if ($value['coordinates']) {

						if ($value['pui_pos_count'] > 0) {
							$ring_color = 'red_ring';
						} elseif ($value['pui_count'] > 0) {
							$ring_color = 'orange_ring';
						} elseif ($value['q_fever'] > 0 || $value['q_sick_sign'] > 0) {
							$ring_color = 'yellow_ring';
						} else {
							$ring_color = 'green_ring';
						}


				?>

						var cssIcon = L.divIcon({
							className: 'css-icon',
							html: '<div class="<?= $ring_color ?>"></div>',
							iconSize: [<?= $value['pui_pos_count'] > 0 || $value['pui_count'] > 0 ? '20,20' : '8,8' ?>]
						});



						L.marker([<?= $value['coordinates'] ?>], {
								icon: cssIcon,
								vill_code: '<?= $value['villagecodefull'] ?>',
								vill: '<?= $value['vill_name'] ?>',
								dist: '<?= $value['ampurname'] ?>',
								detected: '<?= $value['detected'] ?>/<?= $value['pass14days'] ?>',
								sick_result: '<?= $value['one_sign'] ?>',
								waitting_result: '<?= $value['pui_count'] ?>',
								comfirmed: '<?= $value['pui_pos_count'] ?>',
								death: '0',
								newdeath: '0',
								recovered: '0',
								admit: '0',
								coma: '0'
							}).addTo(mymap)
							.on('click', onMapClick);



				<?php
					}
				}

				?>





				var clickedMarker;

				function clickFeature(e) {
					if (clickedMarker) {
						clickedMarker.setIcon(arms);
					}
					var layer = e.target;
					e.target.setIcon(stop);
					clickedMarker = e.target;

					info.update(layer.feature.properties);
				}

				function onEachFeature(feature, layer) {
					layer.on({
						click: clickFeature
					});
				}


				// var popup = L.popup();

				function onMapClick(e) {
					//var popup = e.target.getPopup();
					//var content = popup.getContent();

					//console.log(content);
					//console.log(e.target.options.title);
					var lat = (e.latlng.lat);
					var lng = (e.latlng.lng);
					var newLatLng = new L.LatLng(lat, lng);
					pin.setLatLng(newLatLng);
					pin.zIndexOffset = 1000;


					$('#vill').text(e.target.options.vill);

					$('#dist').text(e.target.options.dist);
					$('#detected').text(e.target.options.detected);
					$('#sick_result').text(e.target.options.sick_result);
					$('#waitting_result').text(e.target.options.waitting_result);
					$('#comfirmed').text(e.target.options.comfirmed);
					$('#death').text(e.target.options.death);
					$('#newdeath').text(e.target.options.newdeath);
					$('#recovered').text(e.target.options.recovered);
					$('#admit').text(e.target.options.admit);
					$('#coma').text(e.target.options.coma);

					$('#vill-info').attr("href", "<?= Url::to(['village', 'villagecode' => '']) ?>" + e.target.options.vill_code);
					// 	popup
					// 		.setLatLng(e.latlng)
					// 		.setContent("You clicked the map at " + e.latlng.toString())
					// 		.openOn(mymap);
				}

				//mymap.on('click', onMapClick);
			</script>


<div class="countcorona">

<div class="countcorona-area">
	<h4>กลุ่มเสี่ยงจำแนกรายตำบล</h4>
	<div>


		<table id="total-summary" class="table-bordered stripe row-border order-column">
			<thead>
				<tr>

					<th></th>
					<th colspan="4" class="text-center">จำนวนเฝ้าระวัง<br>ทั้งหมด</th>
					<th colspan="4" class="text-center">แรงงาน<br>เกาหลีใต้</th>
					<th colspan="4" class="text-center">ข้ามแดน<br>กัมพูชา</th>
					<th colspan="4" class="text-center">PUI/<br>ผู้สัมผัสเชื้อ</th>
					<th colspan="4" class="text-center">กลับจาก กทม./<br>ปริมณฑล</th>
					<th colspan="4" class="text-center">กลับจากประเทศเขตติดโรคฯ<br>และระบาดต่อเนื่อง</th>
					<th colspan="2" class="text-center">อื่นๆ</th>
					<th></th>


				</tr>
				<tr>

					<th>ตำบล</th>
					<th class="text-center rotate">รายใหม่</th>
					<th class="text-center rotate">สะสม</th>
					<th class="text-center rotate">ครบ 14 วัน/จำหน่าย</th>
					<th class="text-center rotate">ยังเฝ้าระวัง</th>


					<th class="text-center">รายใหม่</th>
					<th class="text-center">สะสม</th>
					<th class="text-center">ครบ 14 วัน/จำหน่าย</th>
					<th class="text-center">ยังเฝ้าระวัง</th>

					<th class="text-center">รายใหม่</th>
					<th class="text-center">สะสม</th>
					<th class="text-center">ครบ 14 วัน/จำหน่าย</th>
					<th class="text-center">ยังเฝ้าระวัง</th>

					<th class="text-center">รายใหม่</th>
					<th class="text-center">สะสม</th>
					<th class="text-center">ครบ 14 วัน/จำหน่าย</th>
					<th class="text-center">ยังเฝ้าระวัง</th>

					<th class="text-center">รายใหม่</th>
					<th class="text-center">สะสม</th>
					<th class="text-center">ครบ 14 วัน/จำหน่าย</th>
					<th class="text-center">ยังเฝ้าระวัง</th>

					<th class="text-center">รายใหม่</th>
					<th class="text-center">สะสม</th>
					<th class="text-center">ครบ 14 วัน/จำหน่าย</th>
					<th class="text-center">ยังเฝ้าระวัง</th>

					<th class="text-center">รายใหม่</th>
					<th class="text-center">สะสม</th>

					<th class="text-center">มีไข้<br>หรืออาการบ่งชี้ *</th>


				</tr>
			</thead>


			<tbody>

				<?php
				$i = 0;
				$total_detected = 0;
				$total_risk_korea_worker = 0;
				$total_risk_cambodia_border = 0;
				$total_q_close_to_case = 0;
				$total_risk_from_bangkok = 0;
				$total_q_from_risk_country = 0;

				$total_risk_korea_worker_new = 0;
				$total_risk_cambodia_border_new = 0;
				$total_q_close_to_case_new = 0;
				$total_risk_from_bangkok_new = 0;
				$total_q_from_risk_country_new = 0;

				$total_risk_korea_worker_ended = 0;
				$total_risk_cambodia_border_ended = 0;
				$total_q_close_to_case_ended = 0;
				$total_risk_from_bangkok_ended = 0;
				$total_q_from_risk_country_ended = 0;

				$total_one_sign = 0;
				$total_other = 0;
				$total_newcase = 0;
				$total_newcase_ended = 0;

				$bar_data = "";
				$bar_data2 = "";

				foreach ($district as $value) {
					$i++;
					if ($value['detected']) {
						$total_detected += $value['detected'];
					}

					$total_newcase += $value['newcase'];
					$total_newcase_ended += $value['newcase_ended'];

					$total_risk_korea_worker += $value['risk_korea_worker'];
					$total_risk_cambodia_border += $value['risk_cambodia_border'];
					$total_q_close_to_case += $value['q_close_to_case'];
					$total_risk_from_bangkok += $value['risk_from_bangkok'];
					$total_q_from_risk_country += $value['q_from_risk_country'];

					$total_risk_korea_worker_new += $value['risk_korea_worker_new'];
					$total_risk_cambodia_border_new += $value['risk_cambodia_border_new'];
					$total_q_close_to_case_new += $value['q_close_to_case_new'];
					$total_risk_from_bangkok_new += $value['risk_from_bangkok_new'];
					$total_q_from_risk_country_new += $value['q_from_risk_country_new'];

					$total_risk_korea_worker_ended += $value['risk_korea_worker_ended'];
					$total_risk_cambodia_border_ended += $value['risk_cambodia_border_ended'];
					$total_q_close_to_case_ended += $value['q_close_to_case_ended'];
					$total_risk_from_bangkok_ended += $value['risk_from_bangkok_ended'];
					$total_q_from_risk_country_ended += $value['q_from_risk_country_ended'];


					$total_other += ($value['detected'] - ($value['risk_korea_worker'] + $value['risk_cambodia_border'] + $value['q_close_to_case'] + $value['risk_from_bangkok'] + $value['q_from_risk_country']));
					$total_one_sign += $value['one_sign'];

					$bar_data .= "['" . $value['tambonname'] . "', " . $value['risk_korea_worker'] . ", " . $value['risk_cambodia_border'] . ", " . $value['q_close_to_case'] . ", " . $value['risk_from_bangkok'] . ", " . $value['q_from_risk_country'] . ", " .
						($value['detected'] - ($value['risk_korea_worker'] + $value['risk_cambodia_border'] + $value['q_close_to_case'] + $value['risk_from_bangkok'] + $value['q_from_risk_country'])) . ",''],";
					
						$bar_data2 .= "['" . $value['tambonname'] . "', " . ($value['detected'] - $value['newcase_ended']) . ", " . $value['newcase_ended'] . ",''],";
				?>


					<tr>

					<td nowrap><a href="<?= Url::to(['subdistrict', 'tamboncodefull' => $value['tamboncodefull'] ? $value['tamboncodefull'] : '0']) ?>"><?= $value['tambonname'] ?></a></td>
						<td><?= number_format($value['newcase']) ?></td>
						<td><?= number_format($value['detected']) ?></td>
						<td><?= number_format($value['newcase_ended']) ?></td>
						<td><?= number_format($value['detected'] - $value['newcase_ended']) ?></td>


						<th><?= number_format($value['risk_korea_worker_new']) ?></th>
						<th><?= number_format($value['risk_korea_worker']) ?></th>
						<td><?= number_format($value['risk_korea_worker_ended']) ?></td>
						<td><?= number_format($value['risk_korea_worker'] - $value['risk_korea_worker_ended']) ?></td>

						<td><?= number_format($value['risk_cambodia_border_new']) ?></td>
						<td><?= number_format($value['risk_cambodia_border']) ?></td>
						<td><?= number_format($value['risk_cambodia_border_ended']) ?></td>
						<td><?= number_format($value['risk_cambodia_border'] - $value['risk_cambodia_border_ended']) ?></td>

						<td><?= number_format($value['q_close_to_case_new']) ?></td>
						<td><?= number_format($value['q_close_to_case']) ?></td>
						<td><?= number_format($value['q_close_to_case_ended']) ?></td>
						<td><?= number_format($value['q_close_to_case'] - $value['q_close_to_case_ended']) ?></td>

						<td><?= number_format($value['risk_from_bangkok_new']) ?></td>
						<td><?= number_format($value['risk_from_bangkok']) ?></td>
						<td><?= number_format($value['risk_from_bangkok_ended']) ?></td>
						<td><?= number_format($value['risk_from_bangkok'] - $value['risk_from_bangkok_ended']) ?></td>

						<td><?= number_format($value['q_from_risk_country_new']) ?></td>
						<td><?= number_format($value['q_from_risk_country']) ?></td>
						<td><?= number_format($value['q_from_risk_country_ended']) ?></td>
						<td><?= number_format($value['q_from_risk_country'] - $value['q_from_risk_country_ended']) ?></td>

						<td>0</td>
						<td><?= number_format(($value['detected'] - ($value['risk_korea_worker'] + $value['risk_cambodia_border'] + $value['q_close_to_case'] + $value['risk_from_bangkok'] + $value['q_from_risk_country']))) ?></td>
						<td><?= number_format($value['one_sign']) ?></td>
					</tr>

				<?php
				}

				$calendar_data = "";
				foreach ($calendar as $value) {
					$calendar_data .= $value['d'];
				}

				$this->registerJs(" 

google.charts.load(\"47\", {packages:[\"corechart\"]});
google.charts.load(\"47\", {packages:[\"calendar\"]});

google.charts.setOnLoadCallback(drawChart);
function drawChart() {
	
var data = google.visualization.arrayToDataTable([
['กลุ่มเสี่ยง', 'จำนวน'],
['แรงงานเกาหลีใต้',     " . $total_risk_korea_worker . "],
['ข้ามแดนกัมพูชา',      " . $total_risk_cambodia_border . "],
['PUI/ผู้สัมผัสเชื้อ',  " . $total_q_close_to_case . "],
['กลับจาก กทม./ปริมณฑล', " . $total_risk_from_bangkok . "],
['กลับจากประเทศเขตติดโรคฯ และระบาดต่อเนื่อง',    " . $total_q_from_risk_country . "],
['อื่นๆ',    " . ($total_detected - ($total_risk_korea_worker + $total_risk_cambodia_border + $total_q_close_to_case + $total_risk_from_bangkok + $total_q_from_risk_country)) . "]
]);

var options = {
title: 'กลุ่มเสี่ยงภาพรวม จ.สระแก้ว',
pieHole: 0.4,
};

var data2 = google.visualization.arrayToDataTable([
['อำเภอ', 'แรงงานเกาหลีใต้', 'ข้ามแดนกัมพูชา', 'PUI/ผู้สัมผัสเชื้', 'กลับจาก กทม./ปริมณฑล', 'กลับจากประเทศเขตติดโรคฯ และระบาดต่อเนื่อง',
'อื่นๆ',  { role: 'annotation' } ],
" . $bar_data . "
]);

var options2 = {
	title: \"กลุ่มเสี่ยงแยกประเภท รายอำเภอ\",
	width: '100%',
	height: 400,
	legend: { position: 'top', maxLines: 3 },
	bar: { groupWidth: '75%' },
	isStacked: true
};

var chart = new google.visualization.PieChart(document.getElementById('donutchart'));
chart.draw(data, options);


var chart2 = new google.visualization.BarChart(document.getElementById('barchart'));
chart2.draw(data2, options2);


var data3 = google.visualization.arrayToDataTable([
	['อำเภอ', 'ยังเฝ้าระวัง','ครบระยะเฝ้าระวัง', { role: 'style' } ],
	" . $bar_data2 . "
]);

var options3 = {
	title: \"สัดส่วนประชากรเฝ้าระวัง รายอำเภอ\",
	width: '100%',
	height: 400,
	legend: { position: 'top', maxLines: 3 },
	bar: { groupWidth: '75%' },
	isStacked: true,
	series: {
		0:{color:'rgb(255, 153, 0)'},
		1:{color:'rgb(16, 150, 24)'},
		2:{color:'#888'},
		3:{color:'#AAA'},
		4:{color:'#EEE'}
	}
};

var chart3 = new google.visualization.BarChart(document.getElementById('barchart2'));
chart3.draw(data3, options3);




var dataTable = new google.visualization.DataTable();
dataTable.addColumn({ type: 'date', id: 'Date' });
dataTable.addColumn({ type: 'number', id: 'Won/Loss' });
dataTable.addRows([

" . $calendar_data . "
]);

var chart = new google.visualization.Calendar(document.getElementById('calendar_basic'));

var options = {
title: \"จำนวนประชากรเคลื่อนย้ายตามวันที่เข้าพื้นที่\",
height: 200,
calendar: {
   cellSize: 19,
  monthLabel: {
	fontName: '\"Mitr\", sans-serif',
	fontSize: 12,
  },
}
};

chart.draw(dataTable, options);

//Trend การส่งเชื้อ, ติดเชื้อ
			var chartDiv = document.getElementById('trendchart');

			var trenddata = new google.visualization.DataTable();
			trenddata.addColumn('date', 'Month');
			trenddata.addColumn('number', \"ส่งตรวจเชื้อ\");
			trenddata.addColumn('number', \"ส่งตรวจเชื้อสะสม\");
			trenddata.addColumn('number', \"Positive\");
			trenddata.addColumn('number', \"Positive สะสม\");

			trenddata.addRows([
			
				" . $trend_data . "
			]);

			var materialOptions = {
				title: 'อัตราการพบผู้ป่วยรายใหม่',
				chart: {
				  title: 'อัตราการพบผู้ป่วยรายใหม่'
				},
				chartArea:{left:40,top:50,right: 10, bottom: 60},
				width: '100%',
				height: 400,
				series: {
				  // Gives each series an axis name that matches the Y-axis below.
				  0: {axis: 'Temps'},
				  1: {axis: 'Daylight'}
				},
				axes: {
				  // Adds labels to each axis; they don't have to match the axis names.
				  y: {
					Temps: {label: 'Temps (Celsius)'},
					Daylight: {label: 'Daylight'}
				  }
				},
				legend: { position: 'bottom' },
				crosshair: { trigger: 'both', orientation: 'vertical' },
				series: {
					0:{color:'rgb(0, 153, 198)'},
					1:{color:'rgb(51, 102, 204)'},
					2:{color:'rgb(255, 153, 0)'},
					3:{color:'rgb(220, 57, 18)'},
				}
			};

			var materialChart = new google.visualization.LineChart(chartDiv);
			materialChart.draw(trenddata, materialOptions);

}



", View::POS_END, 'my-options');

				?>
			<tfoot>
				<tr>

					<th>Total</th>

					<th><?= number_format($total_newcase) ?></th>
					<th><?= number_format($total_detected) ?></th>
					<th><?= number_format($total_newcase_ended) ?></th>
					<th><?= number_format($total_detected - $total_newcase_ended) ?></th>

					<th><?= number_format($total_risk_korea_worker_new) ?></th>
					<th><?= number_format($total_risk_korea_worker) ?></th>
					<th><?= number_format($total_risk_korea_worker_ended) ?></th>
					<th><?= number_format($total_risk_korea_worker - $total_risk_korea_worker_ended) ?></th>

					<th><?= number_format($total_risk_cambodia_border_new) ?></th>
					<th><?= number_format($total_risk_cambodia_border) ?></th>
					<th><?= number_format($total_risk_cambodia_border_ended) ?></th>
					<th><?= number_format($total_risk_cambodia_border - $total_risk_cambodia_border_ended) ?></th>

					<th><?= number_format($total_q_close_to_case_new) ?></th>
					<th><?= number_format($total_q_close_to_case) ?></th>
					<th><?= number_format($total_q_close_to_case_ended) ?></th>
					<th><?= number_format($total_q_close_to_case - $total_q_close_to_case_ended) ?></th>

					<th><?= number_format($total_risk_from_bangkok_new) ?></th>
					<th><?= number_format($total_risk_from_bangkok) ?></th>
					<th><?= number_format($total_risk_from_bangkok_ended) ?></th>
					<th><?= number_format($total_risk_from_bangkok - $total_risk_from_bangkok_ended) ?></th>

					<th><?= number_format($total_q_from_risk_country_new) ?></th>
					<th><?= number_format($total_q_from_risk_country) ?></th>
					<th><?= number_format($total_q_from_risk_country_ended) ?></th>
					<th><?= number_format($total_q_from_risk_country - $total_q_from_risk_country_ended) ?></th>

					<th>0</th>
					<th><?= number_format($total_other) ?></th>
					<th><?= number_format($total_one_sign) ?></th>

				</tr>
			</tfoot>

			</tbody>
		</table>
	</div>

	* จากรายงานติดตามครั้งล่าสุดในช่วงเฝ้าระวัง 14 วัน<br>
	"รายใหม่" หมายถึง รายใหม่ที่เข้าพื้นที่มาในวันที่ <?= $district[0]['yesterday'] ?>
</div>
</div>



			<div class="countcorona">
				<div class="countcorona-area">
					<h4>กลุ่มเสี่ยงจำแนกรายหมู่บ้าน</h4>
					<table id="example" class="table-bordered stripe row-border order-column">
						<thead>
							<tr>
								<th>หมู่บ้าน</th>
								<th>อำเภอ</th>
								<th>จำนวน<br>เฝ้าระวัง</th>
								<th>จำนวน<br>ส่งตรวจเชื้อ</th>
								<th>จำนวน<br>ผู้ติดเชื้อ</th>
								<th>ผู้ติดเชื้อ<br>รายใหม่</th>
								<th>จำนวน<br>เสียชีวิต</th>
								<th>เสียชีวิต<br>รายใหม่</th>
								<th>จำนวน<br>รักษาหาย</th>
								<th>อยู่ระหว่าง<br>การรักษา</th>
								<th>ผู้ป่วย<br>วิกฤต</th>

							</tr>
						</thead>


						<tbody>

							<?php
							$total_detected = 0;
							foreach ($gis as $value) {
								if ($value['detected']) {
									$total_detected += $value['detected'];
								}

							?>
								<tr>
									<td><a href="<?= Url::to(['village', 'villagecode' => $value['villagecodefull'] ? $value['villagecodefull'] : '0']) ?>"><?= $value['vill_name'] ?></a></td>
									<td><?= $value['ampurname'] ?></td>
									<td><?= $value['detected'] ?></td>
									<th>0</th>
									<td>0</td>
									<td>0</td>
									<td>0</td>
									<td>0</td>
									<td>0</td>
									<td>0</td>
									<td>0</td>
								</tr>

							<?php
							}

							?>
						<tfoot>
							<tr>
								<th>Total</th>
								<th></th>
								<th><?= $total_detected ?></th>
								<th>0</th>
								<th>0</th>
								<th>0</th>
								<th>0</th>
								<th>0</th>
								<th>0</th>
								<th>0</th>
								<td>0</td>
							</tr>
						</tfoot>

						</tbody>
					</table>
				</div>
			</div>








		</div>
	</div>
</section>
<!-- corona count section ending here -->