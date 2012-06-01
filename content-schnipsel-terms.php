<?php

$terms = get_terms( get_object_taxonomies( 'schnipsel' ) );
shuffle($terms);
echo '<ul class="bubbles">';
foreach ( $terms as $term ) {
	printf(
		'<li><a href="%s" title="%s">%s</a></li> ',
		esc_url( get_term_link( $term->slug, $term->taxonomy ) ),
		esc_attr( $term->name ),
		$term->name
	);
}
echo '</ul>';
