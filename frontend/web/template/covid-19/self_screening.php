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
		<script src="assets/js/survey.js" type="text/javascript"></script>

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