<?php
class DS_wpGrafie_Theme_Schnipsel extends DS_wpGrafie_Theme {
	/**
	 * Admin script version.
	 *
	 * @since 2.0
	 *
	 * @var string
	 */
	private static $script_version = '0.1';

	/**
	 * Initialize class.
	 *
	 * @access public
	 * @return void
	 */
	public static function init() {
		add_action( 'init', array( __CLASS__, 'register_taxonomy' ) );
		add_action( 'init', array( __CLASS__, 'register_post_type' ) );
		//add_action( 'save_post', array( __CLASS__, 'metabox_save' ) );

		add_filter( 'wpseo_set_title', array( __CLASS__, 'wpseo_set_title' ) );

		add_action( 'wp_ajax_gist', array( __CLASS__, 'gist' ) );
		add_action( 'admin_print_scripts-post.php', array( __CLASS__, 'gist_js' ) );
		add_action( 'admin_print_scripts-post-new.php', array( __CLASS__, 'gist_js' ) );

		add_filter( 'the_content', array( __CLASS__, 'pre_process_shortcode' ), 1 );
	}

	public static function wpseo_set_title( $title ) {
		if ( is_tax() && self::is_taxonomy_assigned_to_post_type( 'schnipsel' ) )
			return str_replace( '%taxonomies%', get_taxonomy( get_query_var( 'taxonomy' ) )->labels->singular_name . ' / Schnipsel', $title );

		return $title;
	}

	public static function register_post_type() {
		$labels = array(
						'name' => 'Codeschnipsel',
						'singular_name' => 'Codeschnipsel',
						'add_new' => 'Eintragen',
						'add_new_item' => 'Codeschnipsel eintragen',
						'edit_item' => 'Codeschnipsel bearbeiten',
						'new_item' => 'Neuer Codeschnipsel',
						'view_item' => 'Codeschnipsel anzeigen',
						'search_items' => 'Codeschnipsel durchsuchen',
						'not_found' => 'Keine Codeschnipsel gefunden.',
						'not_found_in_trash' => 'Keine Codeschnipsel im Papierkorb vorhanden.',
						'all_items' => 'Alle Codeschnipsel',
						'menu_name' => 'Codeschnipsel'
					);

		$supports = array(
						'title',
						'editor',
						'comments',
						'excerpt',
						'custom-fields',
						'thumbnail'
					);

		$args = array(
					'label' => 'Schnipsel',
					'labels' => $labels,
					'description' => 'Codeschnipsel zu WordPress',
					'public' => true,
					//'exclude_from_search' => true, http://core.trac.wordpress.org/ticket/17592
					'menu_position' => 5,
					'has_archive' => true,
					'supports' => $supports,
					'register_meta_box_cb' => array( __CLASS__, 'register_meta_boxes' ),
					'rewrite' => true,
					'query_var' => true
				);

		register_post_type( 'schnipsel', $args );
	}

	public static function register_taxonomy() {
		$labels_1 = array(
						'name' => 'Sprachen',
						'singular_name' => 'Sprache',
						'search_items' => 'Sprachen durchsuchen',
						'popular_items' => 'Gängige Sprachen',
						'all_items' => 'Alle Sprachen',
						'edit_item' => 'Sprache bearbeiten',
						'update_item' => 'Sprache aktualisieren',
						'add_new_item' => 'Sprache hinzufügen',
						'separate_items_with_commas' => 'Sprachen mit einem Komma trennen',
						'add_or_remove_items' => 'Sprachen hinzufügen oder entfernen',
						'choose_from_most_used' => 'Sprache aus den Meistverwendeten wählen',
						'menu_name' => 'Sprachen',
		);
		$args_1 = array(
						'label' => 'Sprachen',
						'labels' => $labels_1,
						'public' => true,
						'show_in_nav_menus' => false,
						'show_ui' => true,
						'rewrite' => array(
											'slug' => 'schnipsel/sprache'
										)
					);

		register_taxonomy(
					'sprache',
					'schnipsel',
					$args_1
		);

		$labels_2 = array(
						'name' => 'Versionen',
						'singular_name' => 'Version',
						'search_items' => 'Versionen durchsuchen',
						'all_items' => 'Alle Versionen',
						'parent_item' => 'Versionszweig',
						'parent_item_colon' => 'Versionszweig:',
						'edit_item' => 'Version bearbeiten',
						'update_item' => 'Version aktualisieren',
						'add_new_item' => 'Version hinzufügen',
						'menu_name' => 'Versionen'

		);
		$args_2 = array(
						'label' => 'Versionen',
						'labels' => $labels_2,
						'public' => true,
						'show_in_nav_menus' => false,
						'show_ui' => true,
						'hierarchical' => true,
						'rewrite' => array(
											'slug' => 'schnipsel/wp-version',
											'hierarchical' => false
										)
					);

		register_taxonomy(
					'wp-version',
					'schnipsel',
					$args_2
		);

		$labels_3 = array(
						'name' => 'Schwierigkeitsgrade',
						'singular_name' => 'Schwierigkeitsgrad',
						'search_items' => 'Schwierigkeitsgrade durchsuchen',
						'parent_item' => '',
						'parent_item_colon' => '',
						'all_items' => 'Alle Schwierigkeitsgrade',
						'edit_item' => 'Schwierigkeitsgrad bearbeiten',
						'update_item' => 'Schwierigkeitsgrad aktualisieren',
						'add_new_item' => 'Schwierigkeitsgrad hinzufügen',
						'menu_name' => 'Schwierigkeitsgrade',
		);
		$args_3 = array(
						'label' => 'Schwierigkeitsgrad',
						'labels' => $labels_3,
						'public' => true,
						'show_in_nav_menus' => false,
						'show_ui' => true,
						'hierarchical' => true,
						'rewrite' => array(
											'slug' => 'schnipsel/schwierigkeitsgrad',
											'hierarchical' => false
										)
					);

		register_taxonomy(
					'schwierigkeitsgrad',
					'schnipsel',
					$args_3
		);

	}

	public static function register_meta_boxes() {
		add_meta_box(
				'schnipsel_gist',
				'Gist',
				array( __CLASS__, 'gist_metabox_cb' ),
				'schnipsel'
		);
	}

	public static function gist_metabox_cb( $data ) {
		wp_nonce_field( 'schnipsel_gist', 'schnipsel_gist_nonce' );

		$gist_id = get_post_meta( $data->ID, '_gist_id', true ) ;
		$gist_id = ! empty( $gist_id ) ? $gist_id : '';
		$gist_data = get_post_meta( $data->ID, '_gist_data', true );
		$gist_data = ! empty( $gist_data ) ? $gist_data : array();

		$files = '';
		if ( ! empty( $gist_data ) ) {
			foreach ( $gist_data as $file => $data )
				$files .= '<li><code>{' . $file . '}</code></li>';
		}
		?>

		<p>
			<label>Gist ID: <input type="text" class="small-text" style="width: 180px;" name="gist_id" id="gist_id" value="<?php echo esc_attr( $gist_id ); ?>" /></label>
			<input type="button" value="Fetch" id="gist_update" class="button" />
			<img src="<?php echo esc_url( admin_url( 'images/wpspin_light.gif' ) ); ?>" class="ajax-loading" id="gist-ajax-loading" alt="" />
		</p>
		<ul id="gist_files">
		<?php echo $files;?>
		</ul>
		<?php
	}

	public static function gist_metabox_save( $post_id ) {
		return;
	}

	/**
	 * Prüft, ob eine gegebene oder aktuell nachgefragte Taxonomy einem gegebenen Post Type zugeordnet ist.
	 *
	 * @author Dominik Schilling
	 * @license GPLv2
	 * @link http://wpgrafie.de/137/
	 *
	 * @version 0.1
	 * @param object|string $post_type
	 * @param string $taxonomy Optional. Standardwert ist null.
	 * @return bool True wenn Taxonomy dem Post Type zugeordnet ist, false wenn nicht und bei fehlerhafter Eingabe.
	 */
	public function is_taxonomy_assigned_to_post_type( $post_type, $taxonomy = null ) {
		if ( is_object( $post_type ) )
			$post_type = $post_type->post_type;

		if ( empty( $post_type ) )
			return false;

		$taxonomies = get_object_taxonomies( $post_type );

		if ( empty( $taxonomy ) )
			$taxonomy = get_query_var( 'taxonomy' );

		return in_array( $taxonomy, $taxonomies );
	}

	public function gist() {
		$post_id = $_POST['post_id'];
		$gist_id = $_POST['gist_id'];
		if ( $post_id == null || $gist_id == null )
			die( json_encode( array(
				'id' => $gist_id,
				'error' => 'Empty Post ID or Gist ID.'
			) ) );

		check_ajax_referer( 'schnipsel_gist' );


		$gist_body = wp_remote_retrieve_body(
			wp_remote_get(
				"https://api.github.com/gists/{$gist_id}",
				array( 'sslverify' => false )
			)
		);
		$gist_body = json_decode( $gist_body );

		if ( empty( $gist_body ) )
			die( json_encode( array(
				'id' => $gist_id,
				'error' => 'Empty Body'
			) ) );

		if ( ! empty( $gist_body->error ) ) {
			die( json_encode( array(
				'id' => $gist_id,
				'error' => $gist_body->error
			) ) );
		}

		update_post_meta( $post_id, '_gist_id', $gist_id );

		$gist_files = $gist_data = array();

		foreach ( $gist_body->files as $file ) {
			$gist_files[] = $file->filename;
			$gist_data[$file->filename] = array(
				'language' => $file->language,
				'raw_url'  => esc_url_raw( $file->raw_url ),
				'content'  => $file->content
			);
		}

		update_post_meta( $post_id, '_gist_data', $gist_data );

		die( json_encode( array(
			'id' => $gist_id,
			'files' => $gist_files
		) ) );
	}

	public static function pygmentize( $filename, $code, $language, $options = '' ) {
		require_once( ABSPATH . 'wp-admin/includes/file.php' );
		$tmp_file = wp_tempnam( $filename );
		$tmp_f_file = wp_tempnam( 'pygmentized_' . $filename );

		$language = strtolower( $language );

		$match = false;
		if ( 'php' ==  $language ) {
			$match = explode( "\n", $code, 2);
			if ( '<?php' != $match[0]) {
				$code = "<?php\n" . $code;
				$match = true;
			} else {
				$match = false;
			}
		}

		$file_handle = fopen( $tmp_file, "w" );
		fwrite( $file_handle, $code );
		fclose( $file_handle );

		$options = $options ? '-O ' . $options : '';

		$command = "pygmentize -f html $options -l $language -o $tmp_f_file $tmp_file";
		exec( $command );

		$file_handle = fopen( $tmp_f_file, "r" );
		$pygmentized = fread( $file_handle, filesize( $tmp_f_file ) );
		fclose( $file_handle );

		unlink( $tmp_file );
		unlink( $tmp_f_file );

		if ( $match && ! strpos( $options, 'linenos=table' ) ) {
			$pygmentized = preg_replace('/<span class=\"cp\">\&lt;\?php<\/span>\s+/s', '', $pygmentized, 1);
		}

		return $pygmentized;

	}

	// http://wpforce.com/prevent-wpautop-filter-shortcode/
	public static function pre_process_shortcode( $content ) {
		global $shortcode_tags;

		// Backup current registered shortcodes and clear them all out
		$orig_shortcode_tags = $shortcode_tags;
		$shortcode_tags = array();

		add_shortcode( 'code', array( __CLASS__, 'gist_shortcode' ) );

		// Do the shortcode (only the one above is registered)
		$content = do_shortcode( $content );

		// Put the original shortcodes back
		$shortcode_tags = $orig_shortcode_tags;

		return $content;
	}

	public static function gist_shortcode( $atts, $content = null ) {
		global $post;

		if ( empty( $atts['gist'] ) && empty( $content ) )
			return false;

		$meta = '';
		if ( ! empty( $atts['gist'] ) ) {
			$gist_files = get_post_meta( $post->ID, '_gist_data', true );
			$gist_id = get_post_meta( $post->ID, '_gist_id', true );

			if ( empty( $gist_files ) )
				return false;

			$gist_file = $gist_files[ $atts['gist'] ];
			$filename = $atts['gist'];
			$code = $gist_file['content'];
			$language = $gist_file['language'];
			$meta = sprintf(
				'<figcaption class="gist-meta">%s / %s / %s</figcaption>',
				$language,
				'<a target="_blank" href="' . esc_url( $gist_file['raw_url'] ) . '" title="Ohne Syntax-Highlight">Text</a>',
				'<a target="_blank" href="' . esc_url( 'https://gist.github.com/' . $gist_id . '#file_' . str_replace( '-', '_', $atts['gist'] ) ) . '">github<span class="dot">:</span><span class="gist">gist</span></a>'
			);
		} else {
			$code = $content;
			$filename = md5( $code );
			$language = $atts['language'];
		}

		$options = ! empty( $atts['options'] ) ? $atts['options'] : '';

		$pygmentized_code = self::pygmentize( $filename, $code, $language, $options );

		if ( empty( $pygmentized_code ) )
			return false;

		return sprintf(
			'<figure class="code-block">%s%s%s</figure>',
			$meta,
			$pygmentized_code,
			$meta
		);
	}

	public function gist_js() {
		global $current_screen;

		if ( 'schnipsel' != $current_screen->id )
			return;

		$dev = self::$dev ? '' : '.min';

		wp_register_script(
			self::$themeslug . '-admin' ,
			sprintf(
				'%s/js/jquery.%s.admin%s.js',
				get_stylesheet_directory_uri(),
				self::$themeslug,
				$dev
			),
			array( 'jquery' ),
			self::$script_version,
			true
		);

		wp_enqueue_script( self::$themeslug . '-admin' );
	}
}
?>
