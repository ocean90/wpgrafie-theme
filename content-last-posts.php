<?php
query_posts( array(
	'no_found_rows'  => 1,
	'post_type'      => array( 'post', 'schnipsel' )
) );

$i = $j = 0;
while ( have_posts() ) :
	the_post();
	$i++;
	if ( $i <= 2 ) :
	?>
	<article id="last-post-<?php echo $i; ?>" <?php post_class( 'row list-post-big list-posts' ); ?>>
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
	else :
		$j++;
		if ( $j % 2 != 0 ) echo '<div class="row">';
	?>
	<article id="last-post-<?php echo $i; ?>" <?php post_class( 'span6 list-post-small list-posts' ); ?>>
		<header>
			<a href="<?php the_permalink(); ?>" class="no-link-style">
				<?php
				the_post_thumbnail( 'article-image-small' );
				?>
			</a>
			<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
		</header>
		<div class="excerpt">
			<p><?php echo get_post_meta( $post->ID, '_wpseo_edit_description', true ); ?></p>
		</div>
		<footer class="meta">
			<span class="date">— <?php echo the_modified_date(); ?></span>
			<a href="<?php the_permalink(); ?>" class="more">Weiterlesen</a>
		<footer>
	</article>
	<?php
		if ( $j % 2 == 0 ) echo '</div>';
	endif;
endwhile;
?>
