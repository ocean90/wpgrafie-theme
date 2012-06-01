<?php
$_blocked = array();

// Most comments
$most_comments = new WP_Query( array(
	'orderby'        => 'comment_count',
	'no_found_rows'  => 1,
	'posts_per_page' => 1,
	'post_type'      => array( 'post', 'schnipsel' )
) );

if ( ! empty( $most_comments ) ) {
	while ( $most_comments->have_posts() ) {
		$most_comments->the_post();
		$_blocked = array_merge( $_blocked, (array) get_the_ID() );

			echo '<div class="row">';
			echo '<h4>Am meisten kommentiert wurde…</h4>';
			printf(
				'<p><a href="%s" title="%s">%s</a></p>',
				get_permalink(),
				get_the_title(),
				get_the_title()
			);
			echo '</div>';
	}
}

// Last comment
$last_comment = get_comments( array(
	'number' => 5,
	'status' => 'approve',
	'type'   => 'comment'
) );

if( ! empty( $last_comment ) ) {
	foreach ($last_comment as $comment => $data ) {
		if ( in_array( $_blocked, (array) $data->comment_post_ID ) )
			continue;

		$post = get_post( $data->comment_post_ID );
		setup_postdata( $post );
		$_blocked = array_merge( $_blocked, (array) get_the_ID() );

		echo '<div class="row">';
		echo '<h4>Zuletzt kommentiert wurde…</h4>';
		printf(
			'<p><a href="%s" title="%s">%s</a></p>',
			get_permalink(),
			get_the_title(),
			get_the_title()
		);
		echo '</div>';

		break;
	}
}


// Last post update
$last_update = new WP_Query( array(
	'post__not_in'   => $_blocked,
	'no_found_rows'  => 1,
	'posts_per_page' => 5,
	'orderby' => 'modified'
) );

if ( ! empty( $last_update ) ) {
	while ( $last_update->have_posts() ) {
		$last_update->the_post();

		if ( $post->post_date == $post->post_modified )
			continue;

		echo '<div class="row">';
		echo '<h4>Zuletzt aktualisiert wurde…</h4>';
		printf(
			'<p><a href="%s" title="%s">%s</a></p>',
			get_permalink(),
			get_the_title(),
			get_the_title()
		);
		echo '</div>';

		break;
	}
}
