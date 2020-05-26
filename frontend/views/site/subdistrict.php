<?php

/* @var $this yii\web\View */

use yii\helpers\Url;
use yii\web\View;

$this->registerJsFile('https://www.gstatic.com/charts/loader.js', []);

$this->title = 'My Yii Application';
$this->params['breadcrumbs'][] = 'Home';
?>

<style>
	.css-icon {}

	.white_ring {
		border: 3px solid white;
		-webkit-border-radius: 30px;
		height: 26px;
		width: 26px;
		-webkit-animation: pulsate 2s ease-out;
		-webkit-animation-iteration-count: infinite;
		/*opacity: 0.0*/
	}

	.red_ring {
		border: 3px solid #ff3372;
		-webkit-border-radius: 30px;
		height: 26px;
		width: 26px;
		-webkit-animation: pulsate 1.5s ease-out;
		-webkit-animation-iteration-count: infinite;
		/*opacity: 0.0*/
	}


	.yellow_ring {
		border: 3px solid #ffc932;
		-webkit-border-radius: 30px;
		height: 26px;
		width: 26px;
		-webkit-animation: pulsate 1.7s ease-out;
		-webkit-animation-iteration-count: infinite;
		/*opacity: 0.0*/
	}


	.green_ring {
		border: 3px solid #6aa728;
		-webkit-border-radius: 30px;
		height: 26px;
		width: 26px;
		-webkit-animation: pulsate 1.9s ease-out;
		-webkit-animation-iteration-count: infinite;
		/*opacity: 0.0*/
	}

	.badge-waiting {
		background-color: darkgray;
	}

	.badge-black {
		background-color: black;
	}

	.tooltip-inner {
		font-size: 12px;
	}

	.tooltip {
		font-size: 3rem;
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

		.badge {
			font-size: 16px;
			margin-right: 4px;
		}

		.corona-count-section .countcorona .countcorona-area {
			padding: 30px 0;
		}
	}

	dl,
	ol,
	ul {
		padding-left: 12px;
	}

	@media (max-width: 1200px) {
		#mapid {
			width: 100vw;
			height: 700px;
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
				<div class="row justify-content-center align-items-center">
					<div class="col-sm-4">
						<h5>สถานะประชากรเคลื่อนย้าย :<br>
							ต.<?= $gis[0]['tambonname'] ?></h5>
					</div>

					<div class="col-sm-2">
						<div class="corona-item">
							<div class="corona-inner">
								<div class="corona-thumb" style="margin: 0 auto;">
									<img src="<?php echo $this->theme->baseUrl ?>/assets/images/corona/02.png" alt="corona">
								</div>
								<div class="corona-content text-center">
									<p>กลุ่มเสี่ยงวันนี้</p>
									<h3 class="count-number"><?= $sumary[0]['today'] ?></h3>

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
									<h3 class="count-number"><?= $sumary[0]['total'] ?></h3>

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
									<p>ได้รับการเฝ้าระวัง</p>
									<h3 class="count-number"><?= $sumary[0]['total_followed'] ?></h3>

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
									<h3 class="count-number"><?= $sumary[0]['pass14days'] ?></h3>

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
								<a href="#" class="d-flex flex-wrap justify-content-between"><span><i class="icofont-double-right"></i>หมู่บ้าน</span><span id="vill">-</span></a>
							</li>
							<li>
								<a href="#" class="d-flex flex-wrap justify-content-between"><span><i class="icofont-double-right"></i>อำเภอ</span><span id="dist">-</span></a>
							</li>
							<li>
								<a href="#" class="d-flex flex-wrap justify-content-between"><span><i class="icofont-double-right"></i>จำนวนเฝ้าระวัง/ครบระยะเฝ้าระวัง</span><span id="detected">0/0</span></a>
							</li>
							<li>
								<a href="#" class="d-flex flex-wrap justify-content-between"><span><i class="icofont-double-right"></i>ส่งตรวจเชื้อ</span><span id="waitting_result">0</span></a>
							</li>
							<li>
								<a href="#" class="d-flex flex-wrap justify-content-between"><span><i class="icofont-double-right"></i>จำนวนผู้ติดเชื้อ</span><span id="comfirmed">0</span></a>
							</li>
							<li>
								<a href="#" class="d-flex flex-wrap justify-content-between"><span><i class="icofont-double-right"></i>จำนวนเสียชีวิต</span><span id="death">0</span></a>
							</li>
							<li>
								<a href="#" class="d-flex flex-wrap justify-content-between"><span><i class="icofont-double-right"></i>เสียชีวิตรายใหม่</span><span id="newdeath">0</span></a>
							</li>
							<li>
								<a href="#" class="d-flex flex-wrap justify-content-between"><span><i class="icofont-double-right"></i>จำนวนรักษาหาย</span><span id="recovered">0</span></a>
							</li>
							<li>
								<a href="#" class="d-flex flex-wrap justify-content-between"><span><i class="icofont-double-right"></i>อยู่ระหว่างการรักษา</span><span id="admit">0</span></a>
							</li>
							<li>
								<a href="#" class="d-flex flex-wrap justify-content-between"><span><i class="icofont-double-right"></i>ผู้ป่วยวิกฤต</span><span id="coma">0</span></a>
							</li>
						</ul>
					</div>





				</div>
				<div>
					<span class="badge badge-success">สีเขียว = มีประชากรเคลื่อนย้าย ไม่มี PUI</span>
					<span class="badge badge-warning">สีเหลือง = มี PUI</span>
					<span class="badge badge-danger">สีแดง = มี PUI Positive</span>
					<span class="badge">สีขาว = ไม่มีประชากรกลุ่มเสี่ยง</span>

				</div>

			</div>

			<div class="countcorona">
				<div class="countcorona-area">
					<div id="calendar_basic" style="width: 1110px; height: 200px;"></div>
				</div>
			</div>

			<div class="row">
				<div id="donutchart" style="height: 400px;" class="col-sm-6"></div>
				<div id="barchart" style="height: 400px;" class="col-sm-6"></div>
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


				var pin = L.marker([0, 0]).addTo(mymap);


				<?php
				foreach ($gis as $value) {
					if ($value['coordinates']) {
				?>

						var cssIcon = L.divIcon({
							className: 'css-icon',
							html: '<div class="green_ring"></div>',
							iconSize: [26, 26]
						});
						L.marker([<?= $value['coordinates'] ?>], {
								icon: cssIcon,
								vill: '<?= $value['vill_name'] ?>',
								dist: '<?= $value['tambonname'] ?>',
								detected: '<?= $value['detected'] ?>/<?= $value['pass14days'] ?>',
								waitting_result: '0',
								comfirmed: '0',
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
					$('#comfirmed').text(e.target.options.comfirmed);
					$('#death').text(e.target.options.death);
					$('#newdeath').text(e.target.options.newdeath);
					$('#recovered').text(e.target.options.recovered);
					$('#admit').text(e.target.options.admit);
					$('#coma').text(e.target.options.coma);

					// 	popup
					// 		.setLatLng(e.latlng)
					// 		.setContent("You clicked the map at " + e.latlng.toString())
					// 		.openOn(mymap);
				}

				//mymap.on('click', onMapClick);
			</script>


			<div class="countcorona">

				<div class="countcorona-area">
					<h4>กลุ่มเสี่ยงจำแนกรายหมู่บ้าน</h4>
					<table id="example0" class="table-bordered stripe row-border order-column">
						<thead>
							<tr>
								<th>ลำดับ</th>
								<th>หมู่บ้าน</th>
								<th>จำนวน<br>เฝ้าระวัง</th>
								<th>แรงงาน<br>เกาหลีใต้</th>
								<th>ข้ามแดน<br>กัมพูชา</th>
								<th>PUI/<br>ผู้สัมผัสเชื้อ</th>
								<th>กลับจาก กทม./<br>ปริมณฑล</th>
								<th>กลับจากเขตติดโรคฯ<br>และระบาดต่อเนื่อง</th>
								<th>อื่นๆ</th>

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
							$total_other = 0;

							$bar_data = "";
							foreach ($district as $value) {
								$i++;
								if ($value['detected']) {
									$total_detected += $value['detected'];
								}
								$total_risk_korea_worker += $value['risk_korea_worker'];
								$total_risk_cambodia_border += $value['risk_cambodia_border'];
								$total_q_close_to_case += $value['q_close_to_case'];
								$total_risk_from_bangkok += $value['risk_from_bangkok'];
								$total_q_from_risk_country += $value['q_from_risk_country'];
								$total_other += ($value['detected'] - ($value['risk_korea_worker'] + $value['risk_cambodia_border'] + $value['q_close_to_case'] + $value['risk_from_bangkok'] + $value['q_from_risk_country']));

								$bar_data .= "['" . $value['villagename'] . "', " . $value['risk_korea_worker'] . ", " . $value['risk_cambodia_border'] . ", " . $value['q_close_to_case'] . ", " . $value['risk_from_bangkok'] . ", " . $value['q_from_risk_country'] . ", " .
									($value['detected'] - ($value['risk_korea_worker'] + $value['risk_cambodia_border'] + $value['q_close_to_case'] + $value['risk_from_bangkok'] + $value['q_from_risk_country'])) . ",''],";
							?>
								<tr>
									<td><?= $i ?></td>
									<td><a href="<?= Url::to(['village', 'villagecode' => $value['villagecodefull'] ? $value['villagecodefull'] : '0']) ?>"><?= $value['villagename'] ?></a></td>
									<td><?= $value['detected'] ?></td>
									<th><?= $value['risk_korea_worker'] ?></th>
									<td><?= $value['risk_cambodia_border'] ?></td>
									<td><?= $value['q_close_to_case'] ?></td>
									<td><?= $value['risk_from_bangkok'] ?></td>
									<td><?= $value['q_from_risk_country'] ?></td>
									<td><?= ($value['detected'] - ($value['risk_korea_worker'] + $value['risk_cambodia_border'] + $value['q_close_to_case'] + $value['risk_from_bangkok'] + $value['q_from_risk_country'])) ?></td>
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
          ['Task', 'Hours per Day'],
          ['แรงงานเกาหลีใต้',     " . $total_risk_korea_worker . "],
          ['ข้ามแดนกัมพูชา',      " . $total_risk_cambodia_border . "],
          ['PUI/ผู้สัมผัสเชื้อ',  " . $total_q_close_to_case . "],
          ['กลับจาก กทม./ปริมณฑล', " . $total_risk_from_bangkok . "],
          ['กลับจากประเทศเขตติดโรคฯ และระบาดต่อเนื่อง',    " . $total_q_from_risk_country . "],
          ['อื่นๆ',    " . ($total_detected - ($total_risk_korea_worker + $total_risk_cambodia_border + $total_q_close_to_case + $total_risk_from_bangkok + $total_q_from_risk_country)) . "]
        ]);

        var options = {
          title: 'กลุ่มเสี่ยง',
          pieHole: 0.4,
        };

	      var data2 = google.visualization.arrayToDataTable([
	        ['หมู่บ้าน', 'แรงงานเกาหลีใต้', 'ข้ามแดนกัมพูชา', 'PUI/ผู้สัมผัสเชื้', 'กลับจาก กทม./ปริมณฑล', 'กลับจากประเทศเขตติดโรคฯ และระบาดต่อเนื่อง',
	         'อื่นๆ',  { role: 'annotation' } ],
	        " . $bar_data . "
	      ]);

	      var options2 = {
	        width: 500,
	        height: 400,
	        legend: { position: 'top', maxLines: 3 },
	        bar: { groupWidth: '75%' },
	        isStacked: true
	      };

        var chart = new google.visualization.PieChart(document.getElementById('donutchart'));
        chart.draw(data, options);


		var chart2 = new google.visualization.BarChart(document.getElementById('barchart'));
		chart2.draw(data2, options2);


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
      }
  
 ", View::POS_END, 'my-options');
							?>
						<tfoot>
							<tr>
								<th></th>
								<th>Total</th>
								<th><?= $total_detected ?></th>
								<th><?= $total_risk_korea_worker ?></th>
								<th><?= $total_risk_cambodia_border ?></th>
								<th><?= $total_q_close_to_case ?></th>
								<th><?= $total_risk_from_bangkok ?></th>
								<th><?= $total_q_from_risk_country ?></th>
								<th><?= $total_other ?></th>
							</tr>
						</tfoot>

						</tbody>
					</table>
				</div>
			</div>



			<div class="countcorona">
				<div class="countcorona-area">
					<h4>สถิติรายหมู่บ้าน</h4>
					<table id="example" class="table-bordered stripe row-border order-column">
						<thead>
							<tr>
								<th>หมู่บ้าน</th>
								<th>ตำบล</th>
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
									<td><?= $value['tambonname'] ?></td>
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



			<div class="countcorona">
				<div class="countcorona-area">
					<?= $this->render('/locate/_form14', [
							'model' => $model,
							'tambon' => $tambon,
							'village' => $village
                    ]) ?>
					<table id="table_person" class="table table-striped table-bordered">
						<thead>
							<tr>
								<th>ลำดับ</th>
								<th>ชื่อ-นามสกุล</th>
								<th>เพศ</th>
								<th>อายุ</th>
								<th>วันที่<br>เข้าพื้นที่</th>
								<th>ที่อยู่</th>
								<th>ข้อมูล</th>
								<th>ผู้ติดตาม<br>เฝ้าระวัง</th>
								<th>ผลการ<br>ติดตาม</th>
								<th>สถานะ<br>ปัจจุบัน</th>
								<th></th>
							</tr>
						</thead>

						<tbody>
							<?php
							$discharge = [
								'0' => 'ติดตามไม่ได้',
								'1' => 'ย้ายออกนอกพื้นที่',
								'2' => 'ยังอยู่ในพื้นที่ และทำ Home Quarantine',
								'3' => 'ยังอยู่ในพื้นที่ ฝ่าฝืนหรือไม่ทำ Home Quarantine',
								'4' => 'ยังอยู่ในพื้นที่ Local Quarantine',
								'5' => 'ส่ง รพ.'

							];


							$n = 0;
							$desc = "";
							foreach ($village_data as $value) {
								$n++;

								$desc = "";
								$desc .= "<ul>";
								if ($value['q_from_risk_country'] == '1') {
									$desc .= "<li>มีประวัติเดินทางไปประเทศกลุ่มเสี่ยงหรือพื้นที่เสี่ยงตามประกาศกรมในช่วง 14 วันก่อน</li>";
								}

								if ($value['q_close_to_case'] == '1') {
									$desc .= "<li>มีประวัติอยู่ใกล้ชิดกับผู้ป่วยยืนยัน COVID-19 (ใกล้กว่า 1 เมตร นานเกิน 5 นาที) ในช่วง 14 วันก่อน หรือ ไปสนามมวยลุมพินี หรือ ผับที่มีการพบผู้ติดเชื้อ</li>";
								}
								if ($value['risk_from_risk_country'] == '1') {
									$desc .= "<li>ท่านเดินทางกลับจากประเทศ </li>";
								}
								if ($value['risk_korea_worker'] == '1') {
									$desc .= "<li>แรงงานกลับจากประเทศเกาหลีใต้</li>";
								}
								if ($value['risk_cambodia_border'] == '1') {
									$desc .= "<li>เดินทางข้ามพรมแดนกัมพูชา</li>";
								}
								if ($value['risk_from_bangkok'] == '1') {
									$desc .= "<li>เดินทางกลับมาจากกรุงเทพ</li>";
								}
								if ($value['q_family_from_risk_country'] == '1') {
									$desc .= "<li>บุคคลในบ้านเดินทางไปประเทศกลุ่มเสี่ยงหรือพื้นที่เสี่ยงตามประกาศกรมในช่วง 14 วันก่อน</li>";
								}
								if ($value['q_close_to_foreigner'] == '1') {
									$desc .= "<li>ประกอบอาชีพใกล้ชิดกับชาวต่างชาติ</li>";
								}
								if ($value['q_healthcare_staff'] == '1') {
									$desc .= "<li>เป็นบุคลากรทางการแพทย์</li>";
								}
								if ($value['q_close_to_group_fever'] == '1') {
									$desc .= "<li>มีผู้ใกล้ชิดป่วยเป็นไข้หวัดพร้อมกัน มากกว่า 5 คน ในช่วง 14 วันก่อน</li>";
								}
								if ($value['risk_place'] == '1') {
									$desc .= "<li>เคยไปสถานที่เสี่ยงที่มีคนแออัดเบียดเสียด</li>";
								}
								if ($value['risk_group_place'] == '1') {
									$desc .= "<li>เคยไปร่วมกิจกรรมที่มีคนรวมกลุ่มกันเป็นจำนวนมากๆ</li>";
								}
								if ($value['risk_case_place'] == '1') {
									$desc .= "<li>ใกล้ชิดกับผู้ป่วยติดเชื้อหรือไปร่วมอยู่ในสถานที่ที่มีผู้ป่วยติดเชื้อ</li>";
								}

								if ($value['move_province'] != '27' & $value['move_province'] != NULL) {
									$desc .= "<li>มาจาก จ." . $value['changwatname'] . " อ." . $value['mampurname'] . " ต." . $value['tambonname'] . '</li>';
								}

								if ($value['note'] != '') {
									$desc .= "<li>(" . $value['note'] . ")</li>";
								}
								if ($value['remark'] != '') {
									$desc .= "<li>(Local: " . $value['remark'] . ")</li>";
								}

								$desc .= "</ul>";
							?>

								<tr>
									<td><?= $n ?></td>
									<td><?= $value['fname'] ?> <?= $value['lname'] ?> <?= $value['phone_number'] ?></td>
									<td><?= $value['sex'] ?></td>
									<td><?= $value['age'] ?></td>
									<td><?= Yii::$app->thai->thaidate('d F Y', strtotime($value['date_in'])) ?></td>
									<td><?= $value['addr_number'] ?> ม.<?= intval($value['addr_vill_no']) ?> บ.<?= $value['vill_name'] ?></td>
									<td><?= $desc ?></td>
									<td><?= $value['reporter_name'] ?> <?= $value['reporter_phone'] ?></td>
									<td class="text-nowrap">
										<?php
										$output = "";
										$person_gone = 0;
										for ($i = 1; $i <= 14; $i++) {
											$info_sign = "";
											if ($value['d' . $i . '_fever'] == '1') {
												$info_sign .= "มีไข้: " . $value['d' . $i . '_temp'] . "&#8451<br>";
											} else {
												$info_sign .= "ไม่มีไข้<br>";
											}

											if ($value['d' . $i . '_sick'] == '1') {
												$info_sign .= "มีอาการทางระบบทางเดินหายใจ";
											} else {
												$info_sign .= "ไม่มีอาการแสดงอื่น";
											}

											if ($person_gone == 1) {
											} elseif (($value['d' . $i . '_remark'] ==  '0' || $value['d' . $i . '_remark'] ==  '1') && $i > 1 && $value['d' . $i . '_remark']  == $value['status']) {
												$person_gone = 1;
												$output .= "<span class=\"badge badge-black popoverOption\" data-toggle=\"tooltip\" data-original-title=\"" . $value['d' . $i] . "<br>" . $discharge[$value['d' . $i . '_remark']] . "\" data-placement=\"top\" >" . $i . "</span>";
											} elseif ($value['d' . $i . '_fever'] == NULL & $value['d' . $i . '_ended'] == '1') {
												$output .= "<span class=\"badge popoverOption\" data-toggle=\"tooltip\" data-original-title=\"" . $value['d' . $i] . "<br>ไม่มีรายงานเฝ้าระวัง\" data-placement=\"top\" >" . $i . "</span>";
											} elseif ($value['d' . $i . '_fever'] == NULL & $value['d' . $i . '_ended'] == '0') {
												$output .= "<span class=\"badge badge-waiting popoverOption\" data-toggle=\"tooltip\" data-original-title=\"" . $value['d' . $i] . "<br>ยังไม่ถึงวันติดตาม\" data-placement=\"top\" >" . $i . "</span>";
											} elseif ($value['d' . $i . '_fever'] == '1' & $value['d' . $i . '_sick'] == '1') {
												$output .= "<span class=\"badge badge-danger popoverOption\" data-toggle=\"tooltip\" data-original-title=\"" . $value['d' . $i] . "<br><ul>" . $info_sign . "</ul>\"  data-placement=\"top\" >" . $i . "</span>";
											} elseif ($value['d' . $i . '_fever'] == '1' || $value['d' . $i . '_sick'] == '1') {
												$output .= "<span class=\"badge badge-warning popoverOption\" data-toggle=\"tooltip\" data-original-title=\"" . $value['d' . $i] . "<br><ul>" . $info_sign . "</ul>\" data-placement=\"top\" >" . $i . "</span>";
											} else {
												$output .= "<span class=\"badge badge-success popoverOption\" data-toggle=\"tooltip\" data-original-title=\"" . $value['d' . $i] . "<br>อาการปกติ\" data-placement=\"top\" >" . $i . "</span>";
											}
										}

										if ($value['pui_id'] != '') {
											$output.= "<br>PUI: ". $value['pcr_result']." (".$value['pcr_date'].")<br>Discharge:<br>".$value['discharge_result'];
										}

										echo $output;
										?>

									</td>
									<td class=" <?= $value['pass14days'] ? 'success' : 'new_case' ?>">
										<ul><?= $value['status'] ? '<li>' . $discharge[$value['status']] . '</li>' : '' ?><?= $value['pass14days'] ? '<li>
                                                ครบระยะเฝ้าระวัง</li>' : '' ?>
										</ul>
									</td>
									<td>
										<a href="<?= Url::to(['person/view', 'id' => $value['id']]) ?>" title="ดู" aria-label="ดู"><span class="glyphicon glyphicon-eye-open"></span></a>
									</td>
								</tr>

							<?php
							}

							?>


						</tbody>
					</table>
					<span class="badge badge-success">อาการปกติ</span> <span class="badge badge-warning">มีอาการไข้ หรืออาการแสดงอย่างหนึ่งอย่างใด</span> <span class="badge badge-danger">มีอาการไข้ ร่วมกับอาการแสดงอื่นๆ</span> <span class="badge badge-waiting">ยังไม่ถึงวันเฝ้าระวัง</span> <span class="badge">ไม่มีรายงานเฝ้าระวัง</span>
				</div>
			</div>






		</div>
	</div>
</section>
<!-- corona count section ending here -->