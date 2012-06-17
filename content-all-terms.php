<?php

$terms = get_terms(
	array_merge(
		get_object_taxonomies( 'schnipsel' ),
		get_object_taxonomies( 'post' )
	),
	array(
		'number' => '30'
	)
) ;
shuffle($terms);
echo '<ul class="bubbles">';
foreach ( $terms as $term ) {
	printf(
		'<li><a href="%s" title="%s" rel="nofollow">%s</a></li> ',
		esc_url( get_term_link( $term->slug, $term->taxonomy ) ),
		esc_attr( $term->name ),
		$term->name
	);
}
echo '</ul>';
