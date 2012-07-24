<?php
class DS_wpGrafie_WP_Planet_Feed extends DS_wpGrafie_Theme {
	public static function init() {
		add_feed( 'wpd-planet', array( __CLASS__, 'feed_template' ) );
		add_action( 'post_submitbox_misc_actions', array( __CLASS__, 'add_checkbox' ) );
		add_action( 'save_post', array( __CLASS__, 'save_checkbox' ) );
		add_action( 'pre_get_posts', array( __CLASS__, 'feed_content' ) );
	}

	public static function add_checkbox() {
		global $post;

		if ( ! in_array( $post->post_type, array( 'post', 'schnipsel' ) ) )
			return false;

		$value = get_post_meta( $post->ID, 'ds-show-in-wpd-planet-feed', true );
		?>
		<div class="misc-pub-section">
			<label><input type="checkbox" name="ds-show-in-wpd-planet-feed" <?php checked( $value ); ?> value="1" />  Im WPD Planet anzeigen</label>
		</div>
		<?php
	}

	public static function save_checkbox( $post_id ) {
		if ( empty( $post_id ) || empty( $_POST['post_ID'] ) )
			return;

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
			return $post_id;

		if ( $_POST['post_ID'] != $post_id )
			return $post_id;

		if ( ! in_array( $_POST['post_type'], array( 'post', 'schnipsel' ) ) )
			return $post_id;

		if ( empty( $_POST['ds-show-in-wpd-planet-feed'] ) )
			delete_post_meta( $post_id, 'ds-show-in-wpd-planet-feed' );
		else
			add_post_meta( $post_id, 'ds-show-in-wpd-planet-feed', 1, true);

		return $post_id;
	}

	public static function feed_content( $query ) {
		// Bail if $posts_query is not an object or of incorrect class
		if ( ! is_object( $query ) || ( 'WP_Query' != get_class( $query ) ) )
			return;

		// Bail if filters are suppressed on this query
		if ( $query->get( 'suppress_filters' ) )
			return;

		if ( ! $query->is_feed( 'wpd-planet' ) )
			return;

		$query->set( 'post_type', array( 'post', 'schnipsel' ) );
		$query->set( 'meta_key', 'ds-show-in-wpd-planet-feed' );
	}

	public static function feed_template() {
		load_template( ABSPATH . WPINC . '/feed-rss2.php' );
	}

}
