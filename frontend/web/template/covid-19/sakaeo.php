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
	
	.css-icon {

	}

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
	@-webkit-keyframes pulsate {
		    0% {-webkit-transform: scale(0.1, 0.1); opacity: 0.0;}
		    50% {opacity: 1.0;}
		    100% {-webkit-transform: scale(1.2, 1.2); opacity: 0.0;}
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
								<a href="self_screening.php" class="lab-btn style-2"><span>คุณมีอาการป่วยไหม?</span></a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</header>
		<!-- desktop menu ending here -->

        <!-- Page Header Section Start Here -->
        <section class="page-header" style="padding: 150px 0 0px;">
            <div class="page-header-shape">
                <img src="assets/images/banner/home-2/01.jpg" alt="banner-shape">
            </div>
            <div class="container">

                <div class="page-title">
                	<div class="col-md-4 col-8">
                		<img src="assets/images/banner/home-2/03.png" alt="banner-shape" style="pull-left">  
                	</div>
                	<div class="col-md-8 col-12">
                		
                    <h2>ชาวสระแก้วปลอดภัย หากร่วมใจสอดส่องเฝ้าระวัง

                    </h2>

                    <h4 style="color: white">ช่วยแจ้งให้เจ้าหน้าที่ทราบหากท่านพบเห็นผู้คนหรือญาติพี่น้องเดินทางกลับจากต่างประเทศ/ต่างจังหวัด เข้ามาในหมู่บ้านของท่าน เพื่อเจ้าหน้าที่จะสามารถดูแลเฝ้าระวังการเกิดโรคโควิด19 อย่างทันท่วงที</h4>
                    
                    <a href="village.php" class="lab-btn" style="margin-top: 30px;"><span style="font-size: 18px">แจ้งเจ้าหน้าที่ คลิกเลย!</span></a>


                	</div>
                	


                </div>


            </div>
        </section>
		<!-- Page Header Section Ending Here -->
		
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
											<h3 class="count-number">580</h3>
											
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
											<h3 class="count-number">68</h3>
											
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
											<h3 class="count-number">498</h3>
											
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="corona-count-bottom row">
						<div class="gmaps col-xl-8 col-md-8 col-12" id="mapid">
              



                        </div>						
						<div class="col-xl-4 col-md-4 col-12">
							<div class="widget widget-category">
                                <div class="widget-header">
                                    <h5>รายละเอียด</h5>
                                </div>
                                <ul class="widget-wrapper lab-ul">
                                    <li>
                                        <a href="#" class="d-flex flex-wrap justify-content-between"><span><i class="icofont-double-right"></i>หมู่บ้าน</span><span id="vill">บ้านคลองเจริญ ต.หนองหว้า</span></a>
                                    </li>
                                    <li>
                                        <a href="#" class="d-flex flex-wrap justify-content-between"><span><i class="icofont-double-right"></i>อำเภอ</span><span id="dist">เขาฉกรรจ์</span></a>
                                    </li>
                                    <li>
                                        <a href="#" class="d-flex flex-wrap justify-content-between"><span><i class="icofont-double-right"></i>จำนวนเฝ้าระวัง</span><span id="detected">18</span></a>
                                    </li>
                                    <li>
                                        <a href="#" class="d-flex flex-wrap justify-content-between"><span><i class="icofont-double-right"></i>จำนวนผู้ติดเชื้อ</span><span id="comfirmed">7</span></a>
                                    </li>
                                    <li>
                                        <a href="#" class="d-flex flex-wrap justify-content-between"><span><i class="icofont-double-right"></i>จำนวนเสียชีวิต</span><span id="death">9</span></a>
                                    </li>
                                    <li>
                                        <a href="#" class="d-flex flex-wrap justify-content-between"><span><i class="icofont-double-right"></i>เสียชีวิตรายใหม่</span><span id="newdeath">50</span></a>
                                    </li>
                                    <li>
                                        <a href="#" class="d-flex flex-wrap justify-content-between"><span><i class="icofont-double-right"></i>จำนวนรักษาหาย</span><span id="recovered">20</span></a>
                                    </li>
                                    <li>
                                        <a href="#" class="d-flex flex-wrap justify-content-between"><span><i class="icofont-double-right"></i>อยู่ระหว่างการรักษา</span><span id="admit">93</span></a>
                                    </li>
                                    <li>
                                        <a href="#" class="d-flex flex-wrap justify-content-between"><span><i class="icofont-double-right"></i>ผู้ป่วยวิกฤต</span><span id="coma">27</span></a>
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


   <script>

	var mymap = L.map('mapid', {
    scrollWheelZoom: false
}).setView([13.763396, -257.65686], 9);

	L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token=pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpejY4NXVycTA2emYycXBndHRqcmZ3N3gifQ.rJcFIG214AriISLbB6B5aw', {
		maxZoom: 18,
		attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, ' +
			'<a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, ' +
			'Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
		id: 'mapbox/streets-v11',
		tileSize: 512,
		zoomOffset: -1
	}).addTo(mymap);


		var pin = L.marker([13.864747, -257.788696]).addTo(mymap);

		var cssIcon = L.divIcon({
		  className: 'css-icon',
		  html: '<div class="green_ring"></div>'
		  ,iconSize: [26,26]
		});
		L.marker([13.864747, -257.788696], {icon: cssIcon, vill: 'บ้านคลองเจริญ ต.หนองหว้า', dist: 'เขาฉกรรจ์', detected: '43', comfirmed: '3', death: '0', newdeath: '0', recovered: '1', admit: '2', coma: '0'}).addTo(mymap)
		.on('click', onMapClick) ;
		

		var cssIcon = L.divIcon({
		  className: 'css-icon',
		  html: '<div class="yellow_ring"></div>'
		  ,iconSize: [26,26]
		});
		L.marker([13.847414, -257.387695], {icon: cssIcon, vill: 'บ้านคลองแก ต.คลองหินปูน', dist: 'วังน้ำเย็น', detected: '13', comfirmed: '0', death: '0', newdeath: '0', recovered: '0', admit: '0', coma: '0'}).addTo(mymap)
		.on('click', onMapClick) ;



		var cssIcon = L.divIcon({
		  className: 'css-icon',
		  html: '<div class="red_ring"></div>'
		  ,iconSize: [26,26]
		});
		L.marker([13.651325, -257.54837], {icon: cssIcon, vill: 'บ้านทุ่งสว่าง ต.หนองสังข์', dist: 'อรัญประเทศ', detected: '8', comfirmed: '2', death: '1', newdeath: '1', recovered: '0', admit: '1', coma: '0'}).addTo(mymap)
		.on('click', onMapClick) ;





		var cssIcon = L.divIcon({
		  className: 'css-icon',
		  html: '<div class="yellow_ring"></div>'
		  ,iconSize: [26,26]
		});
		L.marker([13.584591, -257.755737], {icon: cssIcon, vill: 'บ้านบึงพระราม ต.พระเพลิง', dist: 'เขาฉกรรจ์', detected: '9', comfirmed: '1', death: '0', newdeath: '0', recovered: '1', admit: '0', coma: '0'}).addTo(mymap)
		.on('click', onMapClick) ;

		var cssIcon = L.divIcon({
		  className: 'css-icon',
		  html: '<div class="yellow_ring"></div>'
		  ,iconSize: [26,26]
		});
		L.marker([13.790071, -257.379456], {icon: cssIcon, vill: 'บ้านบุกะสังข์ ต.หนองแวง', dist: '	วัฒนานคร', detected: '13', comfirmed: '3', death: '0', newdeath: '0', recovered: '0', admit: '3', coma: '1'}).addTo(mymap)
		.on('click', onMapClick) ;

		var cssIcon = L.divIcon({
		  className: 'css-icon',
		  html: '<div class="red_ring"></div>'
		  ,iconSize: [26,26]
		});
		L.marker([13.643318, -257.876587], {icon: cssIcon, vill: 'บ้านป่าไร่ ต.ป่าไร่', dist: 'อรัญประเทศ', detected: '16', comfirmed: '4', death: '1', newdeath: '0', recovered: '1', admit: '2', coma: '0'}).addTo(mymap)
		.on('click', onMapClick) ;


		var cssIcon = L.divIcon({
		  className: 'css-icon',
		  html: '<div class="yellow_ring"></div>'
		  ,iconSize: [26,26]
		});
		L.marker([13.627303, -257.486572], {icon: cssIcon, vill: 'บ้านผาสุก ต.หนองม่วง', dist: 'ตาพระยา', detected: '', comfirmed: '', death: '', newdeath: '', recovered: '', admit: '', coma: ''}).addTo(mymap)
		.on('click', onMapClick) ;

		var cssIcon = L.divIcon({
		  className: 'css-icon',
		  html: '<div class="yellow_ring"></div>'
		  ,iconSize: [26,26]
		});
		L.marker([13.680682, -257.508545], {icon: cssIcon, vill: 'บ้านหนองกระทุ่ม ต.เขาฉกรรจ์', dist: 'เขาฉกรรจ์', detected: '', comfirmed: '', death: '', newdeath: '', recovered: '', admit: '', coma: ''}).addTo(mymap)
		.on('click', onMapClick) ;

		var cssIcon = L.divIcon({
		  className: 'css-icon',
		  html: '<div class="red_ring"></div>'
		  ,iconSize: [26,26]
		});
		L.marker([13.615291, -257.431641], {icon: cssIcon, vill: 'บ้านหนองแก ต.ตาหลังใน', dist: 'วังน้ำเย็น', detected: '', comfirmed: '', death: '', newdeath: '', recovered: '', admit: '', coma: ''}).addTo(mymap)
		.on('click', onMapClick) ;



		var cssIcon = L.divIcon({
		  className: 'css-icon',
		  html: '<div class="yellow_ring"></div>'
		  ,iconSize: [26,26]
		});
		L.marker([13.856747, -257.327271], {icon: cssIcon, vill: 'บ้านเขาตาง๊อก ต.คลองไก่เถื่อน', dist: 'คลองหาด', detected: '', comfirmed: '', death: '', newdeath: '', recovered: '', admit: '', coma: ''}).addTo(mymap)
		.on('click', onMapClick) ;

		var cssIcon = L.divIcon({
		  className: 'css-icon',
		  html: '<div class="green_ring"></div>'
		  ,iconSize: [26,26]
		});
		L.marker([13.827412, -257.431641], {icon: cssIcon, vill: 'บ้านแสง์ ต.ทัพเสด็จ', dist: 'ตาพระยา', detected: '', comfirmed: '', death: '', newdeath: '', recovered: '', admit: '', coma: ''}).addTo(mymap)
		.on('click', onMapClick) ;

		var cssIcon = L.divIcon({
		  className: 'css-icon',
		  html: '<div class="red_ring"></div>'
		  ,iconSize: [26,26]
		});
		L.marker([13.768731, -257.420654], {icon: cssIcon, vill: 'บ้านโคกสะแบง ต.ท่าข้าม', dist: 'อรัญประเทศ', detected: '', comfirmed: '', death: '', newdeath: '', recovered: '', admit: '', coma: ''}).addTo(mymap)
		.on('click', onMapClick) ;



		var cssIcon = L.divIcon({
		  className: 'css-icon',
		  html: '<div class="yellow_ring"></div>'
		  ,iconSize: [26,26]
		});
		L.marker([13.64999, -257.536011], {icon: cssIcon, vill: 'บ้านโคกสูง ต.โคกสูง', dist: '	ตาพระยา', detected: '', comfirmed: '', death: '', newdeath: '', recovered: '', admit: '', coma: ''}).addTo(mymap)
		.on('click', onMapClick) ;

		var cssIcon = L.divIcon({
		  className: 'css-icon',
		  html: '<div class="white_ring"></div>'
		  ,iconSize: [26,26]
		});
		L.marker([13.662001, -257.498932], {icon: cssIcon, vill: 'บ้านโนนสังข์ ต.ท่าข้าม', dist: 'อรัญประเทศ', detected: '', comfirmed: '', death: '', newdeath: '', recovered: '', admit: '', coma: ''}).addTo(mymap)
		.on('click', onMapClick) ;

		var cssIcon = L.divIcon({
		  className: 'css-icon',
		  html: '<div class="red_ring"></div>'
		  ,iconSize: [26,26]
		});
		L.marker([13.631307, -257.559357], {icon: cssIcon, vill: 'บ้านใหม่คลองหินปูน ต.สระแก้ว', dist: 'เมือง', detected: '', comfirmed: '', death: '', newdeath: '', recovered: '', admit: '', coma: ''}).addTo(mymap)
		 //.bindPopup("This is popup content")
		 .on('click', onMapClick) ;



var clickedMarker;

function clickFeature(e) {
    if(clickedMarker) {
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
						  	<table id="example" class="table table-striped table-bordered">
								<thead>
									<tr>
										<th>หมู่บ้าน</th>
										<th>อำเภอ</th>
										<th>จำนวน<br>ผู้ติดเชื้อ</th>
										<th>ผู้ติดเชื้อ<br>รายใหม่</th>
										<th>จำนวน<br>เสียชีวิต</th>
										<th>เสียชีวิต<br>รายใหม่</th>
										<th>จำนวน<br>รักษาหาย</th>
										<th>อยู่ระหว่าง<br>การรักษา</th>
										<th>ผู้ป่วย,<br>วิกฤต</th>
										<th>อัตราป่วย/<br>แสน ปชก.</th>
									</tr>
								</thead>
								<tfoot>
									<tr>
										<th>Total</th>
										<th></th>
										<th>308,257</th>
										<th class="new-cases">3,267</th>
										<th class="new-deaths">13,068</th>
										<th>61</th>
										<th>95,828</th>
										<th>199,361</th>
										<td>9,943</td>
										<td>39.5</td>
									</tr>
								</tfoot>

								<tbody>
									<tr>
										<td><a href="#">บ้านทุ่งสว่าง ต.หนองสังข์ </a></td>
										<td>อรัญประเทศ</td>
										<td>80967</td>
										<td class="new_case">+39</td>
										<td>3248</td>
										<td class="new_death">+3</td>
										<td>71150</td>
										<td>6570</td>
										<td>2190</td>
										<td>56</td>
									</tr>
									<tr>
										<td><a href="#">บ้านโคกสูง ต.โคกสูง </a></td>
										<td>ตาพระยา</td>
										<td>41035</td>
										<td></td>
										<td>3405</td>
										<td></td>
										<td>4440</td>
										<td>33190</td>
										<td>2498</td>
										<td>680</td>
									</tr>
									<tr>
										<td><a href="#">บ้านบุกะสังข์ ต.หนองแวง </a></td>
										<td>วัฒนานคร</td>
										<td>18408</td>
										<td></td>
										<td>1284</td>
										<td></td>
										<td>5979</td>
										<td>11144</td>
										<td></td>
										<td>219</td>
									</tr>
									<tr>
										<td><a href="#">บ้านใหม่คลองหินปูน ต.สระแก้ว </a></td>
										<td>เมือง</td>
										<td>18077</td>
										<td></td>
										<td>831</td>
										<td></td>
										<td>1107</td>
										<td>16139</td>
										<td>999</td>
										<td>387</td>
									</tr>
									<tr>
										<td><a href="#">บ้านป่าไร่ ต.ป่าไร่ </a></td>
										<td>อรัญประเทศ</td>
										<td>15320</td>
										<td></td>
										<td>44</td>
										<td></td>
										<td>115</td>
										<td>15152</td>
										<td>2</td>
										<td>183</td>
									</tr>
									<tr>
										<td><a href="#">บ้านคลองเจริญ ต.หนองหว้า </a></td>
										<td>เขาฉกรรจ์</td>
										<td>14366</td>
										<td class="new_case">+577</td>
										<td>217</td>
										<td class="new_death">+10</td>
										<td>125</td>
										<td>14235</td>
										<td>64</td>
										<td>43</td>
									</tr>
									<tr>
										<td><a href="#">บ้านหนองกระทุ่ม ต.เขาฉกรรจ์ </a></td>
										<td>เขาฉกรรจ์</td>
										<td>10999</td>
										<td></td>
										<td>372</td>
										<td></td>
										<td>1295</td>
										<td>9328</td>
										<td>1122</td>
										<td>168</td>
									</tr>
									<tr>
										<td><a href="#">บ้านคลองแก ต.คลองหินปูน </a></td>
										<td>วังน้ำเย็น</td>
										<td>8652</td>
										<td class="new_case">+87</td>
										<td>94</td>
										<td class="new_death">+3</td>
										<td>2233</td>
										<td>6325</td>
										<td>68</td>
										<td>180</td>
									</tr>
									<tr>
										<td><a href="#">บ้านโคกสะแบง ต.ท่าข้าม </a></td>
										<td>อรัญประเทศ</td>
										<td>4222</td>
										<td></td>
										<td>43</td>
										<td></td>
										<td>15</td>
										<td>4165</td>
										<td></td>
										<td>488</td>
									</tr>
									<tr>
										<td><a href="#0">บ้านโนนสังข์ ต.ท่าข้าม </a></td>
										<td>อรัญประเทศ</td>
										<td>3270</td>
										<td></td>
										<td>144</td>
										<td></td>
										<td>65</td>
										<td>3060</td>
										<td>20</td>
										<td>56</td>
									</tr>
									<tr>
										<td><a href="#0">บ้านบึงพระราม ต.พระเพลิง </a></td>
										<td>เขาฉกรรจ์</td>
										<td>2460</td>
										<td></td>
										<td>76</td>
										<td></td>
										<td>2</td>
										<td>2382</td>
										<td>45</td>
										<td>144</td>
									</tr>
									<tr>
										<td><a href="#0">บ้านหนองแก ต.ตาหลังใน </a></td>
										<td>วังน้ำเย็น</td>
										<td>2196</td>
										<td class="new_case">+17</td>
										<td>6</td>
										<td></td>
										<td>9</td>
										<td>2181</td>
										<td>13</td>
										<td>244</td>
									</tr>
									<tr>
										<td><a href="#0">บ้านเขาตาง๊อก ต.คลองไก่เถื่อน </a></td>
										<td>คลองหาด</td>
										<td>1795</td>
										<td></td>
										<td>21</td>
										<td></td>
										<td>165</td>
										<td>1609</td>
										<td>130</td>
										<td>155</td>
									</tr>
									<tr>
										<td><a href="#0">บ้านแสง์ ต.ทัพเสด็จ </a></td>
										<td>ตาพระยา</td>
										<td>190</td>
										<td></td>
										<td>7</td>
										<td></td>
										<td>1</td>
										<td>1782</td>
										<td>27</td>
										<td>330</td>
									</tr>
									<tr>
										<td><a href="#0">บ้านผาสุก ต.หนองม่วง </a></td>
										<td>ตาพระยา</td>
										<td>1439</td>
										<td></td>
										<td>11</td>
										<td></td>
										<td>16</td>
										<td>1412</td>
										<td>21</td>
										<td>142</td>
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