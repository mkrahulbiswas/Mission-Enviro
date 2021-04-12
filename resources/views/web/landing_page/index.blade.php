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
		.header-fix {
			position: absolute;
			width: 100%;
			height: 150px;
			top: 0px;
			z-index: 99;
			top: 10px
		}

		.introBlock br {
			display: block;
		}

		.pp {
			color: #8e8e8e;
			font-size: 13px;
		}

		@media (max-width: 991px) {

			.introBlock .imgHolder,
			.introBlock .imgHolder2,
			.introBlock .imgHolder3 {
				display: block;
				margin-top: 20px;
			}

			.slick-slide img {
				width: 150px;
				margin: 0 auto;
			}

			.pageNav1 .navbar-nav .nLogo {
				display: block;
			}

			.header-fix {
				position: static !important;
				height: 100px;
				background: #569311;
				border-bottom: 5px solid #dec00a;
			}

			.land-logo {
				width: 100px !important;
			}

			.introBlock h1 {
				text-align: center;
				font-size: 30px;
				margin-top: 20px;
			}

			.respo-center {
				text-align: center;
			}

			.introBlock h1:before {
				display: none;
			}
		}

		.land-logo {
			width: 150px;
		}

		@media (min-width: 1300px) {
			.introBlock .imgHolder {
				right: -205px;
				top: -74px;
				width: 58%;
			}
		}
	</style>
</head>

<body>


	<div id="pageWrapper">
		<!-- pageHeader -->

		<div class="text-center header-fix"><a href="{{ route('landingPage.home') }}" class="nLogo"><img src="{{ asset('assets/web/images/enviro-logo.png') }}" alt="Botanical" class="land-logo" width=""></a></div>
		<!-- main -->
		<!-- main -->
		<main>
			<!-- introBlock -->
			<section class="introBlock position-relative">
				<div class="slick-fade">
					<div>
						<div class="align w-100 d-flex align-items-center bgCover" style="background-image: url({{ asset('assets/web/images/b-bg.jpg') }});">
							<!-- holder -->
							<div class="container position-relative holder pt-xl-10 pt-0">
								<!-- py-12 pt-lg-30 pb-lg-25 -->
								<div class="row">
									<div class="col-12 col-xl-7">
										<div class="txtwrap pr-lg-10 respo-center">
											<h1 class="fwEbold position-relative pb-lg-8 pb-4 mb-xl-7 mb-lg-6">IF YOU
												BELIEVE,<br /> YOU CAN</h1>

											<img src="{{ asset('assets/web/images/Google-Play-App-Download-Icon.png') }}" alt="image description" width="250">
										</div>
									</div>
									<div class="imgHolder">
										<img src="{{ asset('assets/web/images/landig.png') }}" alt="image description" class="img-fluid w-100">
									</div>
								</div>
							</div>
						</div>
					</div>

				</div>

			</section>
			<!-- chooseUs-sec -->
			<section class="chooseUs-sec container pt-xl-22 pt-lg-20 pt-md-16 pt-10 pb-xl-12 pb-md-7 pb-2">
				<div class="row">
					<div class="col-12 col-lg-6 mb-lg-0 mb-4">
						<img src="{{ asset('assets/web/images/img01.jpg') }}" alt="image description" class="img-fluid">
					</div>
					<div class="col-12 col-lg-6 pr-4">
						<h2 class="headingII fwEbold playfair position-relative mb-6 pb-5">Why choose us ?</h2>
						<p class="mb-xl-14 mb-lg-10">
							Dear young citizens,<br />
							Today silently, the environment is slipping
							out of our hands and it is time to move from
							ignorance to awareness, and awareness to
							action. <br />
							This app is a platform to bring together
							our students - the Force for the Future, to
							help build your own awareness and to spread
							it to the community around us.<br /> It is time to
							realize that that if all of us do not start taking
							small steps regularly, the future generations
							may have to pay a very heavy price — it is
							visible all around us already — the alarming
							levels of air and sea pollution, the spiraling
							landfills bursting at the seams, the fast fading
							seasonal demarcations and sudden flash
							floods in overcrowded cities are clear warning
							signs going beep...beep...beep.<br /> All that is
							surely a wake-up call for us.
							It is action-time folks!! Change starts with
							YOU.<br />
							Looking forward to working together in all<br />
							EarK<br />
							o<br />
							GEM
						</p>

					</div>
				</div>
			</section>


			<!-- contactListBlock -->
			<div class="contactListBlock container overflow-hidden pt-xl-8 pt-lg-10 pt-md-8 pt-4 pb-xl-12 pb-lg-10 pb-md-4 pb-1">
				<div class="row">
					<div class="col-12 col-sm-6 col-lg-3 mb-4 mb-lg-0">
						<!-- contactListColumn -->
						<div class="contactListColumn border overflow-hidden py-xl-5 py-md-3 py-2 px-xl-6 px-md-3 px-3 d-flex">
							<span class="icon icon-van"></span>
							<div class="alignLeft pl-2">
								<strong class="headingV fwEbold d-block mb-1">Take a Green</strong>
								<p class="m-0">Pledge</p>
							</div>
						</div>
					</div>
					<div class="col-12 col-sm-6 col-lg-3 mb-4 mb-lg-0">
						<!-- contactListColumn -->
						<div class="contactListColumn border overflow-hidden py-xl-5 py-md-3 py-2 px-xl-6 px-md-3 px-3 d-flex">
							<span class="icon icon-gift"></span>
							<div class="alignLeft pl-2">
								<strong class="headingV fwEbold d-block mb-1">Enviro</strong>
								<p class="m-0">Calculator</p>
							</div>
						</div>
					</div>
					<div class="col-12 col-sm-6 col-lg-3 mb-4 mb-lg-0">
						<!-- contactListColumn -->
						<div class="contactListColumn border overflow-hidden py-xl-5 py-md-3 py-2 px-xl-4 px-md-2 px-3 d-flex">
							<span class="icon icon-recycle"></span>
							<div class="alignLeft pl-2">
								<strong class="headingV fwEbold d-block mb-1">Enviro</strong>
								<p class="m-0">Journalist</p>
							</div>
						</div>
					</div>
					<div class="col-12 col-sm-6 col-lg-3 mb-4 mb-lg-0">
						<!-- contactListColumn -->
						<div class="contactListColumn border overflow-hidden py-xl-5 py-md-3 py-2 px-xl-6 px-md-3 px-3 d-flex">
							<span class="icon icon-call"></span>
							<div class="alignLeft pl-2">
								<strong class="headingV fwEbold d-block mb-1">Support 24 / 7</strong>
								<p class="m-0">Customer support</p>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- productOfferSec -->
			<div class="productOfferSec container overflow-hidden py-xl-12 py-lg-10 py-md-8 py-5">
				<div class="row">
					<div class="col-12 col-sm-6 mb-sm-0 mb-2">
						<a href="#" class="w-100"><img src="{{ asset('assets/web/images/ad1.jpg') }}" alt="image description" class="img-fluid"></a>
					</div>
					<div class="col-12 col-sm-6">
						<a href="#" class="w-100"><img src="{{ asset('assets/web/images/ad1.jpg') }}" alt="image description" class="img-fluid"></a>
					</div>
				</div>
			</div>
			<!-- dealSecHolder -->


			<div class="container-fluid px-xl-20 px-lg-14">
				<!-- subscribeSecBlock -->
				<section class="">
					<img src="{{ asset('assets/web/images/download.jpg') }}" alt="image description" class="img-fluid">

				</section>
			</div>
			<!-- footerHolder -->

		</main>
		<!-- footer -->
		<footer id="footer" class="container-fluid overflow-hidden px-lg-20">
			<div class="copyRightHolder text-center pt-lg-5 pb-lg-4 py-3">
				<p class="mb-0">Coppyright 2021 by <a href="javascript:void(0);">Mission Enviro App</a> - All right reserved
				</p>
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
