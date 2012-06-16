<?php
$current_object = get_queried_object();
$terms = get_the_terms( $current_object->ID, 'category' );

if ( empty( $terms ) || is_wp_error( $terms ) )
	return;

$category__in = array();
foreach ($terms as $term)
	$category__in[] = $term->term_id;

$args = array(
	'post_type' => 'post',
	'order' => ( mt_rand( 0, 1 ) == 1 ) ? 'DESC' : 'ASC',
	'orderby' => 'rand',
	'ignore_sticky_posts' => 1,
	'post_status' => 'publish',
	'post__not_in' => array( $current_object->ID ),
	'posts_per_page' => 2,
	'category__in' => $category__in
);
$rel = new WP_Query( $args );

if ( ! $rel->have_posts() )
	return;

$output = '';
while ( $rel->have_posts() ) :
	$rel->the_post();
	$output .= '<div class="span6"><div>';
	$output .= '<a href="' . get_permalink() . '" class="no-link-style">';
	if( has_post_thumbnail() )
		$output .= get_the_post_thumbnail( null, 'article-image-small', array( 'class' => 'related-post-thumb', 'alt' => '', 'title' => '' ) );
	else
		$output .= '<span class="img"></span>';
	$output .= '<span>'. get_the_title() . '</span>';
	$output .= '</a>';
	$output .= '</div></div>';
endwhile;

wp_reset_postdata();
?>
<div id="related-posts-row" class="row">
	<div class="span9">
		<div class="row">
			<h3 id="related-title" class="icon" data-icon="ä">Themenähnliche Artikel</h3>
		</div>
		<div class="row">
			<?php echo $output; ?>
		</div>
	</div>
	<div class="span3 spanempty"></div>
</div>
