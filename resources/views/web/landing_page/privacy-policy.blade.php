<!DOCTYPE html>
<html lang="en">
<head>
	<!-- set the encoding of your site -->
	<meta charset="utf-8">
	<!-- set the Compatible of your site -->
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<!-- set the page title -->
	<title>Mission Enviro</title>
		<!-- include the site Google Fonts stylesheet -->
	<link href="https://fonts.googleapis.com/css?family=Playfair+Display:400,700%7CRoboto:300,400,500,700,900&amp;display=swap" rel="stylesheet">
	<!-- include the site bootstrap stylesheet -->
	<link rel="stylesheet" href="{{ asset('assets/web/css/bootstrap.css') }}">
	<!-- include the site fontawesome stylesheet -->
	<link rel="stylesheet" href="{{ asset('assets/web/css/fontawesome.css') }}">
	<!-- include the site stylesheet -->
	<link rel="stylesheet" href="{{ asset('assets/web/css/style.css') }}">
	<!-- include theme plugins setting stylesheet -->
	<link rel="stylesheet" href="{{ asset('assets/web/css/plugins.css') }}">
	<!-- include theme color setting stylesheet -->
	<link rel="stylesheet" href="{{ asset('assets/web/css/color.css') }}">
	<!-- include theme responsive setting stylesheet -->
	<link rel="stylesheet" href="{{ asset('assets/web/css/responsive.css') }}">

	<style>
	.header-fix{
	
    width: 100%;
    height: 150px;
    top: 0px;
    z-index: 99;
	top:10px
}
.introBlock br {
    display: block; 
}
.pp{
color:#8e8e8e;
font-size:13px;
}
	@media (max-width: 991px){
.introBlock .imgHolder, .introBlock .imgHolder2, .introBlock .imgHolder3 {
    display: block;
	margin-top:20px;
}
.slick-slide img {
    width:150px;
	margin:0 auto;
}
.pageNav1 .navbar-nav .nLogo {
    display: block;
}
.header-fix {
    position: static !important;
	height:100px;
	background:#569311;
	border-bottom:5px solid #dec00a;
	}
	.land-logo{
width:100px !important;
}
.introBlock h1 {
    text-align: center;
    font-size: 30px;
    margin-top: 20px;
}	
.respo-center{
text-align:center;
}
.introBlock h1:before {
display:none;
}
}
.land-logo{
width:150px;
}


	</style>
</head>
<body>
	<!-- pageWrapper -->
	<div id="pageWrapper">
		<!-- pageHeader -->
		<div class="text-center header-fix"><a href="{{ route('landingPage.home') }}" class="nLogo"><img src="{{ asset('assets/web/images/enviro-logo.png') }}" alt="Botanical" class="land-logo" width=""></a></div>
		<!-- main -->
		<main>
			<section class="introBannerHolder d-flex w-100 bgCover" style="background-image: url({{ asset('assets/web/images/b-bg7.jpg') }});">
				<div class="container">
					<div class="row">
						<div class="col-12 pt-lg-23 pt-md-15 pt-sm-10 pt-6 text-center">
							<h1 class="headingIV fwEbold playfair mb-4">Privacy Policy</h1>
						</div>
					</div>
				</div>
			</section>
		<section class="contactSecBlock container pt-xl-23 pb-xl-24 pt-lg-20 pb-lg-10 pt-md-16 pb-md-8 py-10">
			<div class="row">
					
					<div class="col-12 col-lg-12 pr-4">
						<h2 class="headingII fwEbold playfair position-relative mb-6 pb-5">Privacy Policy</h2>
						<p class="mb-xl-14 mb-lg-10">
							GEM built the Mission Enviro app as a Free app. This SERVICE is provided by GEM at no cost and is intended for use as is.
							<br/>
							This page is used to inform visitors regarding my policies with the collection, use, and disclosure of Personal Information if anyone decided to use my Service.<br/>If you choose to use my Service, then you agree to the collection and use of information in relation to this policy. The Personal Information that I collect is used for providing and improving the Service. I will not use or share your information with anyone except as described in this Privacy Policy.<br/>The terms used in this Privacy Policy have the same meanings as in our Terms and Conditions, which is accessible at Mission Enviro unless otherwise defined in this Privacy Policy.<br/><strong>Information Collection and Use</strong><br/>For a better experience, while using our Service, I may require you to provide us with certain personally identifiable information, including but not limited to Phone Number, Email. The information that I request will be retained on your device and is not collected by me in any way.</p><div style="font-family: &quot;Helvetica Neue&quot;, Helvetica, Arial, sans-serif; font-size: medium;"><p>The app does use third party services that may collect information used to identify you.</p><p>Link to privacy policy of third party service providers used by the app</p><ul><li><a href="https://www.google.com/policies/privacy/" target="_blank" rel="noopener noreferrer">Google Play Services</a></li></ul></div><p style="font-family: &quot;Helvetica Neue&quot;, Helvetica, Arial, sans-serif; font-size: medium;"><strong>Log Data</strong><br/>I want to inform you that whenever you use my Service, in a case of an error in the app I collect data and information (through third party products) on your phone called Log Data. This Log Data may include information such as your device Internet Protocol (“IP”) address, device name, operating system version, the configuration of the app when utilizing my Service, the time and date of your use of the Service, and other statistics.<br/><strong>Cookies</strong><br/>Cookies are files with a small amount of data that are commonly used as anonymous unique identifiers. These are sent to your browser from the websites that you visit and are stored on your device's internal memory.<br/>This Service does not use these “cookies” explicitly. However, the app may use third party code and libraries that use “cookies” to collect information and improve their services. You have the option to either accept or refuse these cookies a…
						</p>
			
					</div>
				</div>
			</section>

				<div class="container-fluid px-xl-20 px-lg-14">
				<!-- subscribeSecBlock -->
				<section class="" >
					<img src="{{ asset('assets/web/images/download.jpg') }}" alt="image description" class="img-fluid">
				
				</section>
			</div>
			
		</main>
		<!-- footer -->
		<footer id="footer" class="container-fluid overflow-hidden px-lg-20">
			<div class="copyRightHolder text-center pt-lg-5 pb-lg-4 py-3">
				<p class="mb-0">Coppyright 2021 by <a href="javascript:void(0);">Mission Enviro App</a> - All right reserved</p>
				<a class="pp" href="{{route('landingPage.privacyPolicy')}}">Privacy Policy</a>
			</div>
		</footer>
	</div>

	<!-- include jQuery library -->
	<script src="{{ asset('assets/web/js/jquery-3.4.1.min.js') }}"></script>
	<!-- include bootstrap popper JavaScript -->
	<script src="{{ asset('assets/web/js/popper.min.js') }}"></script>
	<!-- include bootstrap JavaScript -->
	<script src="{{ asset('assets/web/js/bootstrap.min.js') }}"></script>
	<!-- include custom JavaScript -->
	<script src="{{ asset('assets/web/js/jqueryCustome.js') }}"></script>
</body>

</html>
