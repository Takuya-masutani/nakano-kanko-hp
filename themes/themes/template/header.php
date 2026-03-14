<!DOCTYPE html>
<html <?php language_attributes(); ?> <?php blankslate_schema_type(); ?>>
	<head>
		<meta charset="<?php bloginfo( 'charset' ); ?>">
		<meta name="viewport" content="width=device-width">
		<meta name="robots" content="max-image-preview:large">
		<meta name="thumbnail" content="<?php echo home_url(); ?>/wp-content/uploads/site-thumbnail.png">

		<!-- Google tag (gtag.js) -->
		<script async src="https://www.googletagmanager.com/gtag/js?id=G-YW906N4E35"></script>
		<script>
			window.dataLayer = window.dataLayer || [];
			function gtag(){dataLayer.push(arguments);}
			gtag('js', new Date());

			gtag('config', 'G-YW906N4E35');
		</script>

		<link rel="preconnect" href="https://fonts.googleapis.com">
		<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
		<link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@100..900&family=Zen+Kaku+Gothic+New:wght@300;400;500;700;900&family=Zen+Maru+Gothic:wght@300;400;500;700;900&display=swap" rel="stylesheet">
		<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
		<script>
			href = location.href;
			var links = document.querySelectorAll(".nav-list > li > a");
			for (var i = 0; i < links.length; i++) {
				if (links[i].href == href) {
					document.querySelectorAll(".nav-list > li")[i].classList.add("current");
				}
			}
		</script>

		<!-- Swiper CSS -->
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
		<!-- Swiper JS -->
		<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

		<?php wp_head(); ?>
	</head>
	<body <?php body_class(); ?>>

		<?php if ( is_front_page() ) : ?>
		<div id="loading">
			<div class="loading-inner">
				<img src="<?php echo home_url(); ?>/wp-content/uploads/toplogo.png" alt="中野区観光協会 ブラナカ">
			</div>
		</div>
		<?php endif; ?>

		<?php wp_body_open(); ?>
		<div id="wrapper" class="hfeed">
			<header id="header">
				<div class="header-inner flex">
					<div class="site_logo">
						<?php if ( is_front_page() ) : ?>
						<h1><a href="<?php echo site_url(); ?>"><img src="<?php echo home_url(); ?>/wp-content/uploads/toplogo.png" alt="<?php bloginfo( 'name' ); ?>" width="200" height="50"></a></h1>
						<?php else: ?>
						<p><a href="<?php echo site_url(); ?>"><img src="<?php echo home_url(); ?>/wp-content/uploads/toplogo.png" alt="<?php bloginfo( 'name' ); ?>" width="50" height="50"></a></p>
						<?php endif; ?>
					</div>
					<nav id="menu">
						<?php wp_nav_menu( array( 'theme_location' => 'main-menu', 'link_before' => '<span itemprop="name">', 'link_after' => '</span>' ) ); ?>
					</nav>
					<button id="js-hamburger" class="sp_nav hamburger">
						<span></span>
						<span></span>
						<span></span>
					</button>
				</div>
				<?php echo do_shortcode('[gtranslate]'); ?>
			</header>
			<main id="content">
				<div id="container">