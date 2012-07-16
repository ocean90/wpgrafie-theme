<?php
while ( have_posts() ) :
	the_post();

	?>
	<article id="search-result-post-<?php echo $post->ID; ?>" <?php post_class( 'row list-post-big list-posts' ); ?>>
		<header>
			<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
		</header>
		<div class="excerpt">
      <p><?php echo get_post_meta( $post->ID, '_wpseo_edit_description', true ); ?></p>
		</div>
		<footer class="meta">
			<a href="<?php the_permalink(); ?>" class="more">Weiterlesen</a>
		</footer>
	</article>
	<?php

endwhile;
?>
