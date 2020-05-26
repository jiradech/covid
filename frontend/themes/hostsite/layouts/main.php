<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use common\widgets\Alert;
use yii\helpers\Url;




AppAsset::register($this);
?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="th">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<title>HOSTSight - Contacts</title>



    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
	<?php $this->beginBody() ?>


<!-- Main Header -->

<nav id="navigation" class="site-header navigation navigation-justified header--sticky">
	<div class="container">
		<div class="navigation-header">
			<div class="navigation-logo">
				<a href="index.html">
					<picture>
						<source type="image/webp" srcset="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" data-srcset="template/hostsite/HTML/img/demo-content/logo-white.webp">
						<img class="lazyload" loading="lazy" src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" data-src="template/hostsite/HTML/img/demo-content/logo-white.png" alt="logo">
					</picture>
				</a>
			</div>
			<div class="navigation-button-toggler">
				<i class="hamburger-icon"></i>
			</div>
		</div>
		<div class="navigation-body">
			<div class="navigation-body-header">
				<div class="navigation-logo">
					<a href="index.html">
						<picture>
							<source type="image/webp" srcset="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" data-srcset="template/hostsite/HTML/img/demo-content/logo-white.webp">
							<img class="lazyload" loading="lazy" src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" data-src="template/hostsite/HTML/img/demo-content/logo-white.png" alt="logo">
						</picture>
					</a>
				</div>
				<span class="navigation-body-close-button">&#10005;</span>
			</div>
			<ul class="navigation-menu">
				<li class="navigation-item is-active">
					<a class="navigation-link" href="#">Hosting</a>
					<div class="navigation-megamenu">
						<div class="navigation-megamenu-container">
							<div class="navigation-row">
								<div class="navigation-col">
									<a href="02_wp_host.html" class="navigation-hosting-item border-primary-themes">
										<picture>
											<source type="image/webp" srcset="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" data-srcset="template/hostsite/HTML/img/demo-content/navigation-hostings/hosting1.webp">
											<img class="navigation-hosting-item-img lazyload"  loading="lazy" src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" data-src="template/hostsite/HTML/img/demo-content/navigation-hostings/hosting1.png" alt="hosting">
										</picture>
										WordPress Hosting
									</a>
								</div>
								<div class="navigation-col">
									<a href="03_shared_host.html" class="navigation-hosting-item border-red-themes">
										<picture>
											<source type="image/webp" srcset="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" data-srcset="template/hostsite/HTML/img/demo-content/navigation-hostings/hosting2.webp">
											<img class="navigation-hosting-item-img lazyload"  loading="lazy" src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" data-src="template/hostsite/HTML/img/demo-content/navigation-hostings/hosting2.png" alt="hosting">
										</picture>
										Shared Hosting
									</a>
								</div>
								<div class="navigation-col">
									<a href="04_vps_host.html" class="navigation-hosting-item border-orange-themes">
										<picture>
											<source type="image/webp" srcset="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" data-srcset="template/hostsite/HTML/img/demo-content/navigation-hostings/hosting3.webp">
											<img class="navigation-hosting-item-img lazyload"  loading="lazy" src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" data-src="template/hostsite/HTML/img/demo-content/navigation-hostings/hosting3.png" alt="hosting">
										</picture>
										VPS Hosting
									</a>
								</div>
								<div class="navigation-col">
									<a href="05_dedicated_server.html" class="navigation-hosting-item border-yellow-themes">
										<picture>
											<source type="image/webp" srcset="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" data-srcset="template/hostsite/HTML/img/demo-content/navigation-hostings/hosting4.webp">
											<img class="navigation-hosting-item-img lazyload"  loading="lazy" src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" data-src="template/hostsite/HTML/img/demo-content/navigation-hostings/hosting4.png" alt="hosting">
										</picture>
										Dedicated Server
									</a>
								</div>
								<div class="navigation-col">
									<a href="06_cloud_hosting.html" class="navigation-hosting-item border-blue-themes">
										<picture>
											<source type="image/webp" srcset="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" data-srcset="template/hostsite/HTML/img/demo-content/navigation-hostings/hosting5.webp">
											<img class="navigation-hosting-item-img lazyload"  loading="lazy" src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" data-src="template/hostsite/HTML/img/demo-content/navigation-hostings/hosting5.png" alt="hosting">
										</picture>
										Cloud Hosting
									</a>
								</div>
							</div>
						</div>
					</div>
				</li>
				<li class="navigation-item">
					<a class="navigation-link" href="#">Domain</a>
					<ul class="navigation-dropdown">
						<li class="navigation-dropdown-item">
							<a class="navigation-dropdown-link" href="07_domains.html">Domains</a>
						</li>
						<li class="navigation-dropdown-item">
							<a class="navigation-dropdown-link" href="08_domain_checking.html">Domain Checking</a>
						</li>
						<li class="navigation-dropdown-item">
							<a class="navigation-dropdown-link" href="#">
								Status Pages
								<span class="submenu-indicator"></span>
							</a>
							<ul class="navigation-dropdown">
								<li class="navigation-dropdown-item">
									<a class="navigation-dropdown-link" href="18_status_page.html">Status Page</a>
								</li>
								<li class="navigation-dropdown-item">
									<a class="navigation-dropdown-link" href="19_incident_history.html">Incident History</a>
								</li>
								<li class="navigation-dropdown-item">
									<a class="navigation-dropdown-link" href="20_operational.html">Operational</a>
								</li>
								<li class="navigation-dropdown-item">
									<a class="navigation-dropdown-link" href="21_degraded_performance.html">Degraded Performance</a>
								</li>
								<li class="navigation-dropdown-item">
									<a class="navigation-dropdown-link" href="22_planned_maintenance.html">Planned Maintenance</a>
								</li>
								<li class="navigation-dropdown-item">
									<a class="navigation-dropdown-link" href="23_subscribe_pop_up.html">Subscribe Popup</a>
								</li>
							</ul>
						</li>
					</ul>
				</li>
				<li class="navigation-item">
					<a class="navigation-link" href="#">Pages</a>
					<div class="navigation-megamenu navigation-megamenu-half">
						<div class="navigation-megamenu-container">
							<div class="navigation-row">
								<div class="navigation-col">
									<ul class="navigation-list">
										<li class="navigation-list-heading">
											Standard Pages
											<span class="navigation-list-subheading">Pages that every website needs.</span>
										</li>
										<li><a href="09_about.html">About Us</a></li>
										<li><a href="25_testimonials.html">Testimonials</a></li>
										<li><a href="27_pricing_tables.html">Pricing Packages</a></li>
										<li><a href="18_status_page.html">Status Page</a></li>
										<li><a href="16_blog_details.html">Post Details</a></li>
										<li><a href="24_error.html">Error 404</a></li>
									</ul>
								</div>
								<div class="navigation-col">
									<ul class="navigation-list">
										<li class="navigation-list-heading">
											Web Elements
											<span class="navigation-list-subheading">Awesome header and title style variations and many more.</span>
										</li>
										<li><a href="31_typography.html">Typography</a></li>
										<li><a href="26_tabs_and_accordions.html">Tabs & Accordions</a></li>
										<li><a href="30_tables.html">Tables</a></li>
										<li><a href="28_infographic.html">Infographic</a></li>
										<li><a href="32_buttons.html">Buttons</a></li>
										<li><a href="29_forms.html">Forms</a></li>

									</ul>
								</div>
							</div>
						</div>
					</div>
				</li>
				<li class="navigation-item">
					<a class="navigation-link" href="#">
						Support
					</a>
					<ul class="navigation-dropdown">
						<li class="navigation-dropdown-item">
							<a class="navigation-dropdown-link" href="14_submit_a_request.html">Submit a Request</a>
						</li>
						<li class="navigation-dropdown-item">
							<a class="navigation-dropdown-link" href="#">
								Knowledge Base
								<span class="submenu-indicator"></span>
							</a>
							<ul class="navigation-dropdown">
								<li class="navigation-dropdown-item">
									<a class="navigation-dropdown-link" href="10_knowledge_base.html">Getting Started</a>
								</li>
								<li class="navigation-dropdown-item">
									<a class="navigation-dropdown-link" href="11_knowledge_base_domains.html">Hosting</a>
								</li>
								<li class="navigation-dropdown-item">
									<a class="navigation-dropdown-link" href="#">
										Domains
										<span class="submenu-indicator submenu-indicator-left"></span>
									</a>
									<ul class="navigation-dropdown navigation-dropdown-left">
										<li class="navigation-dropdown-item">
											<a class="navigation-dropdown-link" href="#">Vue</a>
										</li>
										<li class="navigation-dropdown-item">
											<a class="navigation-dropdown-link" href="#">React</a>
										</li>
										<li class="navigation-dropdown-item">
											<a class="navigation-dropdown-link" href="#">Ember</a>
										</li>
										<li class="navigation-dropdown-item">
											<a class="navigation-dropdown-link" href="#">
												Angular
												<span class="submenu-indicator submenu-indicator-left"></span>
											</a>
											<ul class="navigation-dropdown navigation-dropdown-left">
												<li class="navigation-dropdown-item">
													<a class="navigation-dropdown-link" href="#">Angular</a>
												</li>
												<li class="navigation-dropdown-item">
													<a class="navigation-dropdown-link" href="#">Angular 2</a>
												</li>
											</ul>
										</li>
									</ul>
								</li>
								<li class="navigation-dropdown-item">
									<a class="navigation-dropdown-link" href="12_knowledge_base_domain_articles.html">General</a>
								</li>
								<li class="navigation-dropdown-item">
									<a class="navigation-dropdown-link" href="13_knowledge_base_domain_article_details.html">Articles</a>
								</li>
							</ul>
						</li>
					</ul>
				</li>
				<li class="navigation-item">
					<a class="navigation-link" href="15_blog.html">Blog</a>
				</li>
				<li class="navigation-item">
					<a class="navigation-link" href="17_contacts.html">Contacts</a>
				</li>
			</ul>
			<div class="navigation-body-section navigation-additional-menu">
				<a href="#" class="crumina-button button--green button--xs">Trial Period</a>
				<div class="navigation-search">
					<div class="link-modal-popup" data-toggle="modal" data-target="#popupSearch"></div>
					<svg class="crumina-icon">
						<use xlink:href="template/hostsite/HTML/svg-icons/sprite/icons.svg#icon-search"></use>
					</svg>
				</div>
				<div class="navigation-user-menu">
					<div class="link-modal-popup" data-toggle="modal" data-target="#userMenuPopup"></div>
					<svg class="crumina-icon">
						<use xlink:href="template/hostsite/HTML/svg-icons/sprite/icons.svg#icon-user-menu"></use>
					</svg>
				</div>
			</div>
		</div>
	</div>
</nav>

<!-- ... end Main Header -->


<!-- Popup Search -->

<div class="modal fade window-popup popup-search" id="popupSearch" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<div class="container">
					<div class="row">
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<div class="modal-close-btn-wrapper">
								<button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
								<svg class="crumina-icon">
									<use xlink:href="template/hostsite/HTML/svg-icons/sprite/icons.svg#icon-close"></use>
								</svg>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-body">
				<div class="navigation-search-popup">
					<div class="container">
						<div class="row">
							<div class="col-lg-8 col-md-12 col-sm-12 col-xs-12 m-auto">
								<h2 class="fw-medium text-white">WHAT ARE YOU LOOKING FOR?</h2>
								<form class="search-popup-form" >
									<div class="input-btn--inline">
										<input class="input--dark" type="text" placeholder="Choose your new web address…">
										<button type="button" class="crumina-button button--primary button--l">SEARCH</button>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- ... end Popup Search -->


<!-- User Menu Popup -->

<div class="modal fade window-popup user-menu-popup" id="userMenuPopup" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<div class="container">
					<div class="row">
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<div class="modal-close-btn-wrapper">
								<button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
								<svg class="crumina-icon">
									<use xlink:href="template/hostsite/HTML/svg-icons/sprite/icons.svg#icon-close"></use>
								</svg>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-body">
				<div class="user-menu">
					<div class="container">
						<div class="row">
							<div class="col-lg-4 col-md-6 col-sm-12 col-xs-12 m-auto">
								<a href="index.html" class="site-logo">
									<picture>
										<source type="image/webp" srcset="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" data-srcset="template/hostsite/HTML/img/demo-content/logo-colored.webp">
										<img class="lazyload" loading="lazy" src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" data-src="template/hostsite/HTML/img/demo-content/logo-colored.png" alt="logo" width="185">
									</picture>
								</a>
								<p class="fw-medium">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
								<form class="sign-in-form" >
									<h6 class="text-white">SIGN IN TO YOUR ACCOUNT</h6>
									<div class="form-item">
										<input class="input--dark input--squared" type="text" placeholder="Username or email">
									</div>
									<div class="form-item">
										<input class="input--dark input--squared" type="password" placeholder="Password">
									</div>
									<div class="form-item">
										<div class="remember-wrapper text-white">
											<div class="checkbox">
												<label>
													<input type="checkbox" name="optionsCheckboxes4">

													Remember Me
												</label>
											</div>
											<a href="#">Lost your password?</a>
										</div>
									</div>
									<div class="form-item">
										<button type="button" class="crumina-button button--primary button--l w-100">Sign In</button>
									</div>
								</form>

								<p class="text-white fw-medium">Sign In with social networks:</p>
								<ul class="socials">
									<li>
										<a href="#">
											<picture>
												<source type="image/webp" srcset="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" data-srcset="template/hostsite/HTML/img/theme-content/social-icons/facebook.webp">
												<img class="crumina-icon lazyload"  loading="lazy" src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" data-src="template/hostsite/HTML/img/theme-content/social-icons/facebook.png" alt="facebook">
											</picture>
										</a>
									</li>
									<li>
										<a href="#">
											<picture>
												<source type="image/webp" srcset="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" data-srcset="template/hostsite/HTML/img/theme-content/social-icons/twitter.webp">
												<img class="crumina-icon lazyload"  loading="lazy" src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" data-src="template/hostsite/HTML/img/theme-content/social-icons/twitter.png" alt="twitter">
											</picture>
										</a>
									</li>
									<li>
										<a href="#">
											<picture>
												<source type="image/webp" srcset="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" data-srcset="template/hostsite/HTML/img/theme-content/social-icons/google.webp">
												<img class="crumina-icon lazyload"  loading="lazy" src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" data-src="template/hostsite/HTML/img/theme-content/social-icons/google.png" alt="google">
											</picture>
										</a>
									</li>
								</ul>

								<button type="button" class="crumina-button button--grey button--l button--bordered w-100">CREATE AN ACCOUNT</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- ... end User Menu Popup -->


<div class="main-content-wrapper">

	<section class="crumina-stunning-header stunning-header-bg11 pb60">
		<div class="container">
			<div class="row align-items-center">
				<div class="col-lg-8 col-md-8 col-sm-12 col-xs-12 m-auto align-center">
					<div class="page-category">
						<a href="#" class="page-category-item text-white">Contacts</a>
					</div>
					<h1 class="page-title text-white">CONTACT INFORMATION</h1>
					<p class="page-text text-white">Volutpat est velit egestas dui id ornare arcu odio ut. Gravida in fermentum et sollicitudin ac orci. Massa ultricies mi quis hendrerit.</p>
					<a href="#" class="crumina-button button--white button--bordered button--l mt-4">SEND A MESSAGE</a>
				</div>
			</div>
		</div>
	</section>

	<div class="crumina-breadcrumbs breadcrumbs--dark-themes">
		<div class="container">
			<div class="row">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<?=
					Breadcrumbs::widget([
						'options' => ['class' => 'kt-breadcrumb'],
						'homeLink' => [
							'encode' => false,
							'label' => Yii::t('yii', '<span class="fa fa-home mr5"></span> Home'),
							'url' => Yii::$app->homeUrl,
							'template' => "{link}\n",
							'class' => 'breadcrumb-item'
						],
						'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
					])
					?>
				</div>
			</div>
		</div>
	</div>




	<?= Alert::widget() ?>

	<?= $content ?>




</div>

<!-- Footer -->

<footer id="site-footer" class="footer footer--dark footer--with-decoration">


	<div class="sub-footer bg-black">
		<div class="container">
			<div class="row">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center mb-0 mb-lg-0">

					<div class="copyright">
						<span>Copyright © 2019 <a href="index.html">Hostsight</a>, Designed by <a href="https://themeforest.net/user/themefire/portfolio">themefire</a> Developed by <a href="https://crumina.net/">Crumina</a> Only on <a href="https://themeforest.net/">Envato Market</a></span>
          </div>
          <div class="pull-left">
						<a href="index.html" class="site-logo">
							<picture>
								<source type="image/webp" srcset="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" data-srcset="template/hostsite/HTML/img/demo-content/logo-white.webp">
								<img class="lazyload" loading="lazy" src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" data-src="template/hostsite/HTML/img/demo-content/logo-white.png" alt="logo" width="185">
							</picture>
            </a>
        </div>
				</div>
			</div>
		</div>
	</div>

	<a class="back-to-top" href="#">
		<svg class="crumina-icon">
			<use xlink:href="template/hostsite/HTML/svg-icons/sprite/icons.svg#icon-to-top"></use>
		</svg>
	</a>
</footer>

<!-- ... end Footer -->




<script type="text/javascript">

	if ('loading' in HTMLImageElement.prototype) {
		const images = document.querySelectorAll('img[loading="lazy"]');
		images.forEach(img => {
			img.src = img.dataset.src;
		});
	}

</script>

<?php $this->endBody() ?>

</body>
</html>
<?php $this->endPage() ?>