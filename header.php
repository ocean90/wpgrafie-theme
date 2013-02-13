<?php ob_start( 'DS_wpGrafie_Minify::compress_html' ); ?>
<!DOCTYPE html>
<html <?php language_attributes(); ?> itemscope itemtype="http://schema.org/Blog">
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<meta charset="utf-8"/>
	<?php do_action( 'wpseo_the_meta' ); ?>
	<link rel="shortcut icon" href="/favicon.ico" />
	<link rel="alternate" href="<?php echo home_url( '/feed/' ); ?>" type="application/rss+xml" />
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
	<base>
	<?php wp_head(); ?>

	<!--[if lt IE 9]>
	<script src="<?php echo get_template_directory_uri(); ?>/js/html5.js" type="text/javascript"></script>
	<![endif]-->
</head>

<body <?php body_class( 'no-js' ); ?>>
	<div id="header-persistent"><div class="width-960"></div></div>
	<div id="header-collapse">
		<header id="banner" class="width-960">
			<h1 id="logo">wpGrafie</h1>
		</header>
	</div>
	<div id="content-collapse">
		<div id="content-container">
			<?php
			echo str_replace(
				'</nav>',
				'<a href="#" id="pull-navi">wpGrafie.de</a></nav>',
				wp_nav_menu( array(
					'theme_location'  => 'menu_top',
					'container'       => 'nav',
					'container_id'    => 'page-navigation',
					'container_class' => 'width-960 page-navigation',
					'echo'            => false
				) )
			);

			DS_wpGrafie_Theme::breadcrumb();
			?>

			<section id="entry" class="width-960">
