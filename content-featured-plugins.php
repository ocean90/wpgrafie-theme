<?php
// Some plugins
$my_plugins = new WP_Query( array(
	'no_found_rows'  => 1,
	'posts_per_page' => 3,
	'post_type'      => array( 'page' ),
	'post_parent'    => 460

) );

if ( ! empty( $my_plugins ) ) {
	echo '<div class="row" id="my-plugins">';
	while ( $my_plugins->have_posts() ) {
		$my_plugins->the_post();
			printf(
				'<p><a href="%s" title="%s" class="no-link-style">%s<span>%s</span></a></p>',
				get_permalink(),
				get_the_title(),
				get_the_post_thumbnail( null, 'article-image-small'),
				get_the_title()
			);
	}
	echo '</div>';
}
