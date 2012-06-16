<?php
/**
 * Template Name: Plugins
 */

get_header();

query_posts( array(
	'post_type'  => array( 'page' ),
	'post_parent'  => 460,
) );

?>

<div id="plugins-page" class="row">
<?php
while (have_posts()) :
	the_post();
	?>
	<div class="row plugin">
		<div class="span8">
			<a href="<?php the_permalink(); ?>" class="no-link-style"><?php echo get_the_post_thumbnail( null, 'article-image-middle', array( 'alt' => '', 'title' => '' ) ); ?></a>
		</div>
		<div class="span4">
			<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
			<p class="description"><?php echo get_post_meta( $post->ID, '_wpseo_edit_description', true ); ?></p>
		</div>
	</div>
	<?php

endwhile;
echo '</div>';
get_footer();
