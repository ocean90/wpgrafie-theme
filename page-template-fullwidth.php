<?php
/**
 * Template Name: Full Width
 */

get_header();

while (have_posts()) :
	the_post();
?>
	<article id="article" <?php post_class( 'full-width' ); ?>>
		<header id="article-header">
			<div class="row" id="article-title">
				<h2><?php the_title(); ?></h2>
			</div>
		</header>
		<div class="row">
			<div class="span9" id="article-content">
				<?php the_content(); ?>
			</div>
		</div>
		<footer id="article-footer">
		</footer>
	</article>
	<?php
	comments_template( '', true );
endwhile;

get_footer();
