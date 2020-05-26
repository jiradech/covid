<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		
		<!-- google fonts -->

		<link href="https://fonts.googleapis.com/css?family=Mitr&display=swap" rel="stylesheet">
		<link rel="shortcut icon" type="image/x-icon" href="assets/images/x-icon/01.png">

		<link rel="stylesheet" href="assets/css/animate.css">
		<link rel="stylesheet" href="https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap.min.css">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css">
		<link rel="stylesheet" href="assets/css/bootstrap.min.css">
		<link rel="stylesheet" href="assets/css/all.min.css">
		<link rel="stylesheet" href="assets/css/icofont.min.css">
		<link rel="stylesheet" href="assets/css/lightcase.css">
		<link rel="stylesheet" href="assets/css/swiper.min.css">
		<link rel="stylesheet" href="assets/css/style.css">
		
		<link rel="stylesheet" href="https://unpkg.com/leaflet@1.6.0/dist/leaflet.css"
   integrity="sha512-xwE/Az9zrjBIphAcBb3F6JVqxf46+CDLwfLMHloNu6KEQCAWi6HcDUbeOfBIptF7tcCzusKFjFw2yuvEpDL9wQ=="
   crossorigin=""/>

   <script src="https://unpkg.com/leaflet@1.6.0/dist/leaflet.js"
   integrity="sha512-gZwIG9x3wUXg2hdXF6+rVkLF/0Vi9U8D2Ntg4Ga5I5BZpVkVxlJWbSQtXPSiUTtC0TjtGOmxa1AJPuV0CPthew=="
   crossorigin=""></script>


<style>
.fade {
    opacity: 1;
    -webkit-transition: opacity .15s linear;
    -o-transition: opacity .15s linear;
    transition: opacity .15s linear;
}

ul.timeline {
    list-style-type: none;
    position: relative;
}
ul.timeline:before {
    content: ' ';
    background: #d4d9df;
    display: inline-block;
    position: absolute;
    left: 29px;
    width: 2px;
    height: 100%;
    z-index: 400;
}
ul.timeline > li {
    margin: 20px 0;
    padding-left: 60px;
}
ul.timeline > li:before {
    content: ' ';
    background: white;
    display: inline-block;
    position: absolute;
    border-radius: 50%;
    border: 3px solid #22c0e8;
    left: 20px;
    width: 20px;
    height: 20px;
    z-index: 400;
}
</style>



		<title>Covid-19 Sakaeo</title>
	</head>

	<body>

		<?php
$case = 'NaN';
$recovered = 'NaN';
$admit = 'NaN';
$death = 'NaN';


$curl = curl_init();
 
curl_setopt_array($curl, array(
CURLOPT_URL => "https://opend.data.go.th/get-ckan/datastore_search?resource_id=93f74e67-6f76-4b25-8f5d-b485083100b6&limit=50000",
CURLOPT_RETURNTRANSFER => true,
CURLOPT_ENCODING => "",
CURLOPT_MAXREDIRS => 10,
CURLOPT_TIMEOUT => 30,
CURLOPT_FOLLOWLOCATION => true,
CURLOPT_MAXREDIRS => 3,
CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
CURLOPT_CUSTOMREQUEST => "GET",
CURLOPT_HTTPHEADER => array(
"api-key: WqOC92X457Hen1j9a7a8LMzmHlqJuUrW"
)
));
 
$response = curl_exec($curl);
$err = curl_error($curl);
 
curl_close($curl);
 
if ($err) {
//echo "cURL Error #:" . $err;
} else {
	$obj = json_decode($response,true);


	$case = 0;
	$recovered = 0;
	$admit = 0;
	$death = 0;

	foreach($obj['result']['records'] as $value){
		$case++;
		if ($value['Province'] == 'สระแก้ว') {
			$admit++;
		}
  		//echo $value['nation']; //change accordingly
	}


//echo var_dump($obj)  ;
}
?>

		<!-- Mobile Menu Start Here -->
		<div class="mobile-menu seo-bg">
			<nav class="mobile-header">
				<div class="header-logo">
					<a href="index.html"><img src="assets/images/logo/01.png" alt="logo"></a>
				</div>
				<div class="header-bar">
					<span></span>
					<span></span>
					<span></span>
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
							<a href="index.html"><img src="assets/images/logo/02.png" alt="logo"></a>
                        </div>
						<div class="main-area">

							<div class="row justify-content-center align-items-center" style="flex-wrap: nowrap;">

							<div class="col-xl-4 col-md-4 col-4">
								<h5 style="color: greenyellow;">จำนวนผู้ติดเชื้อล่าสุด:</h5>
							</div>
							<div class="col-xl-4 col-md-4 col-4">
								<div class="corona-item">
									<div class="corona-inner">
										<div class="corona-content">
											<p style="color: greenyellow;">ทั่วประเทศ</p>
											<h3 class="count-number" style="color: aqua;"><?=$case?></h3>
										</div>
									</div>
								</div>
							</div>
							<div class="col-xl-4 col-md-4 col-4">
								<div class="corona-item">
									<div class="corona-inner">
	
										<div class="corona-content">
											<p style="color: greenyellow;">จ.สระแก้ว</p>
											<h3 class="count-number" style="color: aqua;"><?=$admit?></h3>
											
										</div>
									</div>
								</div>
							</div>

						</div>
							
							
							<div class="header-btn">
								<a href="#" class="lab-btn style-2"><span>คุณมีอาการป่วยไหม?</span></a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</header>
		<!-- desktop menu ending here -->

        <!-- Page Header Section Start Here -->
        <section class="page-header" style="padding: 150px 0 36px;">
            <div class="page-header-shape">
                <img src="assets/images/banner/home-2/01.jpg" alt="banner-shape">
            </div>
            <div class="container">

                <div class="page-title">

                		
                    <h2>บ้านคลองเจริญ ต.หนองหว้า อ.เขาฉกรรจ์

                    </h2>


                	


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
					<div class="corona-count-top">
						<div class="row justify-content-center align-items-center">
							<div class="col-xl-3 col-md-6 col-12">
								<h5>สถานะประชากรเคลื่อนย้าย :</h5>
							</div>
							<div class="col-xl-3 col-md-6 col-12">
								<div class="corona-item">
									<div class="corona-inner">
										<div class="corona-thumb">
											<img src="assets/images/corona/01.png" alt="corona">
										</div>
										<div class="corona-content">
											<p>คนใหม่เข้าอาศัยสะสม</p>
											<h3 class="count-number">14</h3>
											
										</div>
									</div>
								</div>
							</div>
							<div class="col-xl-3 col-md-6 col-12">
								<div class="corona-item">
									<div class="corona-inner">
										<div class="corona-thumb">
											<img src="assets/images/corona/02.png" alt="corona">
										</div>
										<div class="corona-content">
											<p>คนใหม่เข้าอาศัยวันนี้</p>
											<h3 class="count-number">2</h3>
											
										</div>
									</div>
								</div>
							</div>
							<div class="col-xl-3 col-md-6 col-12">
								<div class="corona-item">
									<div class="corona-inner">
										<div class="corona-thumb">
											<img src="assets/images/corona/03x.png" alt="corona">
										</div>
										<div class="corona-content">
											<p>ได้รับการเฝ้าระวัง</p>
											<h3 class="count-number">16</h3>
											
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					
					<div class="countcorona">
						<div class="countcorona-area">
						  	<table id="example" class="table table-striped table-bordered">
								<thead>
									<tr>
										<th>ชื่อ-นามสกุล</th>
										<th>อายุ</th>
										<th>วันที่<br>เข้าพื้นที่</th>
										<th>ข้อมูล</th>
										<th>ผู้ติดตาม<br>เฝ้าระวัง</th>
										<th>ผลการ<br>ติดตาม</th>
										<th>สถานะ<br>ปัจจุบัน</th>
									</tr>
								</thead>

								<tbody>
									<tr>
										<td><a href="#">สายใจ ขันธ์ศิลา</a></td>
										<td>34</td>
										<td>80967</td>
										<td>ชาวต่างชาติเดินทางเข้าประเทศ จากฝรั่งเศส ปากีสถาน อังกฤษ สวีเดน นิวซีแลนด์</td>
										<td>อสม.อุเทน</td>
										<td><span class="badge badge-success">1</span><span class="badge badge-success">2</span><span class="badge badge-success">3</span><span class="badge badge-warning">4</span></td>
										<td class="new_case">กักตัว/เฝ้าระวัง</td>

									</tr>


									<tr>
										<td><a href="#">กรแก้ว รุ่งเรือง</a></td>
										<td>56</td>
										<td>41035</td>
										<td>ทำงานในที่แออัด หรือใกล้ชิดชาวต่างชาติ</td>
										<td>อสม.ศิริพร</td>
										<td><span class="badge badge-success" data-toggle="modal" data-target="#exampleModal">1</span><span class="badge badge-success">2</span><span class="badge badge-success">3</span></td>
										<td>ปกติ/เฝ้าระวัง</td>

									</tr>
									<tr>
										<td><a href="#">ธีราพร พันจุย</a></td>
										<td>73</td>
										<td>18408</td>
										<td>เดินทางกลับจากต่างประเทศ</td>
										<td>อสม.อุดม</td>
										<td><span class="badge badge-success">1</span><span class="badge badge-success">2</span><span class="badge badge-success">3</span><span class="badge badge-danger">4</span></td>
										<td class="new_death">รักษาตัวใน รพ.</td>

									</tr>
									<tr>
										<td><a href="#">อรอุมา ตะเภาพงษ์</a></td>
										<td>43</td>
										<td>18077</td>
										<td>สัมผัสใกล้ชิดผู้ป่วยก่อนหน้า</td>
										<td>อสม.อำพร</td>
										<td><span class="badge badge-success">1</span><span class="badge badge-success">2</span><span class="badge badge-success">3</span><span class="badge badge-success">4</span></td>
										<td>ปกติ/เฝ้าระวัง</td>

									</tr>
									<tr>
										<td><a href="#">ศรีนวล พรมทอง</a></td>
										<td>46</td>
										<td>15320</td>
										<td>รอสอบสวนโรค</td>
										<td>อสม.อัญชุรีย์</td>
										<td><span class="badge badge-success">1</span><span class="badge badge-success">2</span><span class="badge badge-success">3</span><span class="badge badge-warning">4</span></td>
										<td class="new_case">กักตัว/เฝ้าระวัง</td>

									</tr>
									<tr>
										<td><a href="#">ปาริชาต ภาประจง</a></td>
										<td>54</td>
										<td>14366</td>
										<td>เดินทางกลับจากปอยเปต กัมพูชา</td>
										<td>อสม.รัตนา</td>
										<td><span class="badge badge-success">1</span><span class="badge badge-success">2</span><span class="badge badge-success">3</span><span class="badge badge-success">4</span><span class="badge badge-success">5</span><span class="badge badge-success">6</span></td>
										<td>ปกติ/เฝ้าระวัง</td>

									</tr>
									<tr>
										<td><a href="#">ดารารัตน์ สมบัติวงษ์</a></td>
										<td>62</td>
										<td>10999</td>
										<td>กลับจากการทำงานที่ปอยเปต กัมพูชา</td>
										<td>อสม.ยุพิน</td>
										<td><span class="badge badge-success">1</span><span class="badge badge-success">2</span><span class="badge badge-success">3</span><span class="badge badge-success">4</span><span class="badge badge-success">5</span><span class="badge badge-success">6</span></td>
										<td>ปกติ/เฝ้าระวัง</td>

									</tr>
									
								</tbody>
						  	</table>
						</div>
					</div>
				</div>
            </div>
        </section>
        <!-- corona count section ending here -->
		
		<!-- Footer Section Start Here -->
		<footer style="background-image: url(assets/css/bg-image/footer-bg.jpg);">
			<div class="footer-top padding-tb">
				<div class="container">
					<div class="row">
						<div class="col-lg-3 col-md-6 col-12">
							<div class="footer-item first-set">
								<div class="footer-inner">
									<div class="footer-content">
										<div class="title">
											<h6>About Covid-19</h6>
										</div>
										<div class="content">
											<p>We believe in Simple Creative and Flexible Design Standards.</p>
											<h6>Headquarters:</h6>
											<p>795 Folsom Ave, Suite 600 San Francisco, CA 94107</p>
											<ul class="lab-ul">
												<li>
													<p><span>Phone:</span>(91) 8547 632521</p>
												</li>
												<li>
													<p><span>Email:</span><a href="#">info@covid-19.com</a></p>
												</li>
											</ul>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-lg-3 col-md-6 col-12">
							<div class="footer-item">
								<div class="footer-inner">
									<div class="footer-content">
										<div class="title">
											<h6>Navigate</h6>
										</div>
										<div class="content">
											<ul class="lab-ul">
												<li><a href="#"><i class="icofont-caret-right"></i>Advertisers</a></li>
												<li><a href="#"><i class="icofont-caret-right"></i>Developers</a></li>
												<li><a href="#"><i class="icofont-caret-right"></i>Resources</a></li>
												<li><a href="#"><i class="icofont-caret-right"></i>Company</a></li>
												<li><a href="#"><i class="icofont-caret-right"></i>Connect</a></li>
											</ul>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-lg-3 col-md-6 col-12">
							<div class="footer-item">
								<div class="footer-inner">
									<div class="footer-content">
										<div class="title">
											<h6>Social Contact</h6>
										</div>
										<div class="content">
											<ul class="lab-ul">
												<li><a href="#"><i class="icofont-facebook"></i>Facebook</a></li>
												<li><a href="#"><i class="icofont-twitter"></i>Twitter</a></li>
												<li><a href="#"><i class="icofont-instagram"></i>Instagram</a></li>
												<li><a href="#"><i class="icofont-youtube"></i>YouTube</a></li>
												<li><a href="#"><i class="icofont-xing"></i>Xing</a></li>
											</ul>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-lg-3 col-md-6 col-12">
							<div class="footer-item">
								<div class="footer-inner">
									<div class="footer-content">
										<div class="title">
											<h6>Privacy And Tos</h6>
										</div>
										<div class="content">
											<ul class="lab-ul">
												<li><a href="#"><i class="icofont-caret-right"></i>Advertiser Agreement</a></li>
												<li><a href="#"><i class="icofont-caret-right"></i>Acceptable Use Policy</a></li>
												<li><a href="#"><i class="icofont-caret-right"></i>Privacy Policy</a></li>
												<li><a href="#"><i class="icofont-caret-right"></i>Technology Privacy</a></li>
												<li><a href="#"><i class="icofont-caret-right"></i>Developer Agreement</a></li>
											</ul>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="footer-bottom">
				<div class="container">
					<div class="section-wrapper">
						<p>&copy; 2020 All Rights Reserved. Designed by <a href="https://themeforest.net/user/codexcoder">CodexCoder</a></p>
					</div>
				</div>
			</div>
		</footer>
		<!-- Footer Section Ending Here -->

		<!-- scrollToTop start here -->
		<a href="#" class="scrollToTop"><i class="icofont-swoosh-up"></i><span class="pluse_1"></span><span class="pluse_2"></span></a>
		<!-- scrollToTop ending here -->

		
		<script src="assets/js/jquery.js"></script>
		<script src="assets/js/fontawesome.min.js"></script>
		<script src="assets/js/waypoints.min.js"></script>
		<script src="assets/js/bootstrap.min.js"></script>
		<script src="assets/js/lightcase.js"></script>
		<script src="assets/js/isotope.pkgd.min.js"></script>
		<script src="assets/js/swiper.min.js"></script>
		<script src="assets/js/jquery.countdown.min.js"></script>
		<script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
		<script src="assets/js/functions.js"></script>
		
		<script>
			$(document).ready(function() {
				$("#example").DataTable();


		   	    var data = {
		          resource_id: '93f74e67-6f76-4b25-8f5d-b485083100b6', // the resource id
		          limit: 50000, // get 5 results
		          //q: 'jones', // query for 'jones'
		          //'api-key': 'WqOC92X457Hen1j9a7a8LMzmHlqJuUrW',
		        };
		        $.ajax({
		        	type: 'POST',
		          url: 'https://opend.data.go.th/get-ckan/datastore_search',
		          beforeSend: function(xhr){xhr.setRequestHeader('api-key', 'WqOC92X457Hen1j9a7a8LMzmHlqJuUrW');},
		          //headers: {'api-key:WqOC92X457Hen1j9a7a8LMzmHlqJuUrW': 'WqOC92X457Hen1j9a7a8LMzmHlqJuUrW'},
		          data: data,
		          crossDomain: true,
		          cache: false,
		          dataType: 'jsonp',
		          success: function(data) {
		            alert('Total results found: ' + data.result.total)
		          }
		        }); 	
        


			});
		</script>



	</body>
</html>