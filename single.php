<?php
get_header();

while (have_posts()) :
	the_post();
?>
	<article id="article" <?php post_class(); ?>>
		<header id="article-header">
			<div class="row big-image" id="article-image">
				<div class="vignette"></div>
				<?php the_post_thumbnail( 'article-image-big' ); ?>
			</div>
			<div class="row" id="article-title">
				<h2><?php the_title(); ?></h2>
			</div>
		</header>
		<div class="row">
			<div class="span9" id="article-content">
				<p class="description" itemprop="description"><?php do_action('wpseo_the_desc'); ?></p>
				<?php the_content(); ?>
			</div>
			<aside class="span3" id="article-sidebar">
				<?php
				get_template_part( 'sidebar', 'author' );
				?>
			</aside>
		</div>
		<footer id="article-footer">
			<?php
			get_template_part( 'social' );
			?>
		</footer>
	</article>
	<?php
	comments_template( '', true );
endwhile;

get_footer();
