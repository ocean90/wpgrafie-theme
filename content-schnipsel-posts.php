<?php
while ( have_posts() ) :
	the_post();
	?>
	<article <?php post_class( 'row list-posts list-post-big' ); ?>>
		<header>
			<a href="<?php the_permalink(); ?>" class="no-link-style">
				<?php
				the_post_thumbnail( 'article-image-middle' );
				?>
			</a>
			<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
		</header>
		<div class="excerpt">
			<?php the_excerpt(); ?>
		</div>
		<footer class="meta">
			<span class="date">— <?php the_modified_date(); ?></span>
			<a href="<?php the_permalink(); ?>" class="more">Weiterlesen</a>
		<footer>
	</article>
<?php
endwhile;
