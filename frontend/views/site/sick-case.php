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

	.tooltip {
		font-size: 14px;
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

	@media (max-width: 991px) {
		#mapid {
			width: 100vw;
			height: 80vw;
		}

		.page-title {
			margin-top: -136px;
			padding-bottom: 30px;
		}

		.badge {
			font-size: 16px;
			margin-right: 4px;
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
<section class="page-header" style="padding: 150px 0 36px;">
	<div class="page-header-shape">
		<img src="<?php echo $this->theme->baseUrl ?>/assets/images/banner/home-2/01.jpg" alt="banner-shape">
	</div>
	<div class="container">

		<div class="page-title">


			<h3 style="color: white">
				ผู้มีไข้หรืออาการแสดงบ่งชี้

			</h3>





		</div>


	</div>
</section>
<!-- Page Header Section Ending Here -->

<!-- Button trigger modal -->


<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">

				<div class="container mt-5 mb-5">
					<div class="row">
						<div class="col-md-10 offset-md-1">
							<h4>การติดตาม</h4>
							<ul class="timeline">
								<li>
									<a target="_blank" href="https://www.totoprayogo.com/#">ปกติ</a>
									<a href="#" class="float-right">21 March, 2014</a>
									<ul class="lab-ul">
										<li><i class="icofont-caret-right"></i>Advertisers</li>
										<li><i class="icofont-caret-right"></i>Developers</li>
										<li><i class="icofont-caret-right"></i>Resources</li>
										<li><i class="icofont-caret-right"></i>Company</li>
										<li><i class="icofont-caret-right"></i>Connect</li>
									</ul>
								</li>
								<li>
									<a href="#">21 000 Job Seekers</a>
									<a href="#" class="float-right">4 March, 2014</a>
									<p>Curabitur purus sem, malesuada eu luctus eget, suscipit sed turpis. Nam pellentesque felis vitae justo accumsan, sed semper nisi sollicitudin...</p>
								</li>
								<li>
									<a href="#">Awesome Employers</a>
									<a href="#" class="float-right">1 April, 2014</a>
									<p>Fusce ullamcorper ligula sit amet quam accumsan aliquet. Sed nulla odio, tincidunt vitae nunc vitae, mollis pharetra velit. Sed nec tempor nibh...</p>
								</li>
							</ul>
						</div>
					</div>
				</div>

			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				<button type="button" class="btn btn-primary">Save changes</button>
			</div>
		</div>
	</div>
</div>


<!-- corona count section start here -->
<section class="corona-count-section pt-0 padding-tb">
	<div class="container">
		<div class="corona-wrap">








			<div class="countcorona">
				<div class="countcorona-area">
					<table id="example" class="table table-striped table-bordered">
						<thead>
							<tr>
								<th>ลำดับ</th>
								<th>อำเภอ</th>
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
									<td><?= $value['ampurname'] ?></td>
									<td><?= $value['fname'] ?> <?= $value['lname'] ?> <?= $value['phone_number'] ?></td>
									<td><?= $value['sex'] ?></td>
									<td><?= $value['age'] ?></td>
									<td><?= Yii::$app->thai->thaidate('d F Y', strtotime($value['date_in'])) ?></td>
									<td><?= $value['addr_number'] ?> ม.<?= intval($value['addr_vill_no']) ?> บ.<?= $value['vill_name'] ?> อ.<?= $value['ampurname'] ?></td>
									<td><?= $desc ?></td>
									<td><?= $value['reporter_name'] ?> <?= $value['reporter_phone'] ?></td>
									<td class="text-nowrap">

									<?php
	$output = "";
	$person_gone = 0;
    for($i = 1; $i <= 14; $i++) {
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

		} elseif (($value['d'.$i.'_remark'] ==  '0' || $value['d'.$i.'_remark'] ==  '1') && $i > 1 && $value['d'.$i.'_remark']  == $value['status']) {
			$person_gone = 1;
    		$output.= "<span class=\"badge badge-black popoverOption\" data-toggle=\"tooltip\" data-original-title=\"".$value['d'.$i]."<br>".$discharge[$value['d'.$i.'_remark']]."\" data-placement=\"top\" >".$i."</span>";

    	} elseif ($value['d'.$i.'_fever'] == NULL & $value['d'.$i.'_ended'] == '1') {
    		$output.= "<span class=\"badge popoverOption\" data-toggle=\"tooltip\" data-original-title=\"".$value['d'.$i]."<br>ไม่มีรายงานเฝ้าระวัง\" data-placement=\"top\" >".$i."</span>";

    	} elseif ($value['d'.$i.'_fever'] == NULL & $value['d'.$i.'_ended'] == '0'){
			$output.= "<span class=\"badge badge-waiting popoverOption\" data-toggle=\"tooltip\" data-original-title=\"".$value['d'.$i]."<br>ยังไม่ถึงวันติดตาม\" data-placement=\"top\" >".$i."</span>";

    	} elseif ($value['d'.$i.'_fever'] == '1' & $value['d'.$i.'_sick'] == '1'){
			$output.= "<span class=\"badge badge-danger popoverOption\" data-toggle=\"tooltip\" data-original-title=\"".$value['d'.$i]."<br><ul>".$info_sign."</ul>\"  data-placement=\"top\" >".$i."</span>";

		} elseif ($value['d'.$i.'_fever'] == '1' || $value['d'.$i.'_sick'] == '1'){
			$output.= "<span class=\"badge badge-warning popoverOption\" data-toggle=\"tooltip\" data-original-title=\"".$value['d'.$i]."<br><ul>".$info_sign."</ul>\" data-placement=\"top\" >".$i."</span>";

    	} else {
			$output.= "<span class=\"badge badge-success popoverOption\" data-toggle=\"tooltip\" data-original-title=\"".$value['d'.$i]."<br>อาการปกติ\" data-placement=\"top\" >".$i."</span>";
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
					<br>* จากรายงานติดตามครั้งล่าสุดในช่วงเฝ้าระวัง 14 วัน
				</div>
			</div>
		</div>
	</div>
</section>
<!-- corona count section ending here -->