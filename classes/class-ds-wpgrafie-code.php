<?php
class DS_wpGrafie_Plugin extends DS_wpGrafie_Theme {
	private $plugin_slug;
	private $cache_key;
	public $plugin_data;
	private $plugin_raw_data;

	public function __construct() {

		$this->plugin_slug = $this->get_plugin_slug();
		$this->cache_key = $this->get_cache_key();
		$this->plugin_raw_data = $this->get_plugin_data();

		if ( empty( $this->plugin_raw_data ) )
			return false;

		$this->plugin_data = $this->get_nice_plugin_data();

		return $this->plugin_data;
	}

	private function get_api_response() {
		include_once ABSPATH . 'wp-admin/includes/plugin-install.php';

		$args = array(
			'slug' => $this->plugin_slug
		);
		$api = plugins_api( 'plugin_information', $args );

		if ( ! is_wp_error( $api ) )
			return $api;
		else
			return false;
	}

	private function get_plugin_slug() {
		if ( $this->is_english() )
			return get_post( get_queried_object()->post_parent )->post_name;
		else
			return get_queried_object()->post_name;
	}

	private function get_plugin_data() {
		if ( false === ( $data = get_transient( $this->cache_key ) ) ) {
			$response = $this->get_api_response();

			if ( ! $response )
				return false;

			set_transient( $this->cache_key, $response, 60 * 60 * 24 ); // 1 day

			return $response;

		} else {
			return $data;
		}
	}

	private function get_nice_plugin_data() {
		$data = new stdClass();

		$data->version          = $this->plugin_raw_data->version;
		$data->slug             = $this->plugin_slug;
		$data->download->link   = $this->plugin_raw_data->download_link;
		$data->download->count  = $this->is_english() ? number_format( $this->plugin_raw_data->downloaded ) : number_format( $this->plugin_raw_data->downloaded, 0, ',', '.' ) ;
		$data->compat->tested   = $this->plugin_raw_data->tested;
		$data->compat->requires = $this->plugin_raw_data->requires;
		$data->rating->count    = $this->plugin_raw_data->num_ratings;
		$data->rating->feedback = $this->is_english() ? $this->plugin_raw_data->rating . '%' : str_replace( '.', ',', $this->plugin_raw_data->rating ) . '%';
		$data->rating->rounded  = round( $this->plugin_raw_data->rating * 0.05, 2 );

		$date = $this->plugin_raw_data->last_updated;
		if( ! $this->is_english() ) {
			$date = explode( '-', $date );
			$date = "$date[2].$date[1].$date[0]";
		}
		$data->log->last_update = $date;
		$data->log->changes     = $this->plugin_raw_data->sections['changelog'];

		return $data;
	}

	private function get_switch_message() {
		if ( $this->is_english() ) {
			return sprintf(
				'<a class="switch-plugin-desc" href="%s">Zur deutschen Beschreibung wechseln.</a>',
				esc_url( get_permalink( get_post( get_queried_object()->post_parent )->ID ) )
			);
		} else {
			$child = get_pages( array(
				'child_of' => get_queried_object()->ID
			) );

			return sprintf(
				'<a class="switch-plugin-desc" href="%s">Switch to the English description.</a>',
				esc_url( get_permalink( $child[0]->ID ) )
			);
		}
	}

	public function switch_message() {
		echo $this->get_switch_message();
	}

	private function is_english() {
		return get_queried_object()->post_name == 'en';
	}

	private function get_cache_key() {
		return 'plugin_page_' . md5( $this->plugin_slug );
	}
}
