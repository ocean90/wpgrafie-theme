<?php
get_header();

while (have_posts()) :
	the_post();
?>
	<article id="article" <?php post_class(); ?>>
		<header id="article-header">
			<div class="row big-image" id="article-image">
				<div class="vignette"></div>
				<?php echo get_the_post_thumbnail( null, 'article-image-big', array( 'alt' => '', 'title' => '' ) ); ?>
			</div>
			<div class="row" id="article-title">
				<h2 itemprop="name"><?php the_title(); ?></h2>
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
			<div class="row">
				<div class="span9">
					<a data-icon="*" class="icon no-link-style thanks-link" href="/danke-sagen/">Danke sagen</a>
				</div>
			</div>
			<?php
			get_template_part( 'social' );
			get_template_part( 'related' );
			?>
		</footer>
	</article>
	<?php
	comments_template( '', true );
endwhile;

get_footer();
