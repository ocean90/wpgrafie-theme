<?php
/**
 * @version 2.0
 */
class DS_wpGrafie_Theme {
	/**
	 * The theme slug.
	 *
	 * @since 2.0
	 *
	 * @var string
	 */
	protected static $themeslug = 'wpgrafie-v2';

	/**
	 * Debug flag
	 *
	 * @since 2.0
	 *
	 * @var boolean
	 */
	protected static $dev;

	/**
	 * Additional classes which will be autoloaded.
	 *
	 * @since 2.0
	 *
	 * @var array
	 */
	private static $classes = array(
		// Theme related
		'DS_wpGrafie_Theme_Widgets',
		'DS_wpGrafie_Comments',
		'DS_wpGrafie_Theme_Script_Styles',
		// Frontend stuff
		'DS_wpGrafie_Plugin',
		'DS_wpGrafie_Rewrite',
		'DS_wpGrafie_Minify',
		'DS_WP_Breadcrumb',
		// Backend stuff
		'DS_wpGrafie_WP_Planet_Feed',
		'DS_wpGrafie_Theme_Schnipsel',
	);

	/**
	 * Initialize the main class.
	 *
	 * @since 0.1
	 *
	 * @return void
	 */
	public static function init() {
		self::$dev = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG;

		spl_autoload_register( array( __CLASS__, 'autoload' ) );

		// Register actions
		add_action( 'after_setup_theme', array( __CLASS__, 'init_actions' ) );

		// Remove some actions
		add_action( 'init', array( __CLASS__, 'remove_actions' ), 9999 );

		// Register menus
		add_action( 'after_setup_theme', array( __CLASS__, 'init_menus' ) );

		// Register post images
		add_action( 'after_setup_theme', array( __CLASS__, 'init_post_thumbnails' ) );

		// Register post formats
		add_action( 'after_setup_theme', array( __CLASS__, 'init_post_formats' ) );

		// Register filters
		add_action( 'after_setup_theme', array( __CLASS__, 'init_filters' ) );
	}

	/**
	 * Initialize post formats.
	 *
	 * @since 1.0
	 *
	 * @return void
	 */
	public static function init_post_formats() {
		add_theme_support( 'post-formats', array(
			'aside',
			'gallery',
			'image',
			'link',
			'quote',
			'status',
			'video',
		) );
	}

	public static function init_actions() {
		// Register the custom post type
		add_action( 'init', array( 'DS_wpGrafie_Theme_Schnipsel', 'init' ), 1 );

		// Register pagination base
		add_action( 'init', array( 'DS_wpGrafie_Rewrite', 'set_pagination_base' ) );

		// Register script and styles
		add_action( 'wp_enqueue_scripts', array( 'DS_wpGrafie_Theme_Script_Styles', 'init_scripts' ) );
		add_action( 'wp_enqueue_scripts', array( 'DS_wpGrafie_Theme_Script_Styles', 'init_styles' ) );

		// Register the WP Planet Feed
		add_action( 'init', array( 'DS_wpGrafie_WP_Planet_Feed', 'init') );

		add_action( 'wp_head', array( __CLASS__, 'social_meta' ) );
	}

	public static function remove_actions() {
		/* Clean up */
		remove_action( 'wp_head',	'feed_links',						2		);
		remove_action( 'wp_head',	'feed_links_extra',					3		);
		remove_action( 'wp_head',	'rsd_link'									);
		remove_action( 'wp_head',	'wlwmanifest_link'							);
		remove_action( 'wp_head',	'index_rel_link'							);
		remove_action( 'wp_head',	'parent_post_rel_link',				10, 0	);
		remove_action( 'wp_head',	'start_post_rel_link',				10, 0	);
		remove_action( 'wp_head',	'adjacent_posts_rel_link_wp_head',	10, 0	);
		remove_action( 'wp_head',	'locale_stylesheet'							);
		remove_action( 'wp_head',	'noindex',							1		);
		remove_action( 'wp_head',	'wp_generator'								);
		remove_action( 'wp_head',	'wp_shortlink_wp_head',				10, 0	);
		remove_action( 'wp_head',	'rel_canonical'								);

		// Facebook
		remove_action( 'wp_head', 'fb_add_og_protocol' );
		remove_action( 'wp_footer', 'fb_root' );
		remove_action( 'wp_head', 'fb_js_sdk_setup' );
		remove_action( 'wp_enqueue_scripts', 'fb_style' );
	}

	/**
	 * Initialize actions and filters.
	 *
	 * @access public
	 * @static
	 * @return void
	 */
	public static function init_filters() {
		// Filter some queries
		add_filter( 'pre_get_posts', array( __CLASS__, 'search_query' ) );
		add_filter( 'pre_get_posts', array( __CLASS__, 'include_post_type_in_feed' ) );

		// Add a custom class for the comment status
		add_filter( 'comment_class', array( __CLASS__, 'add_custom_comment_class' ) );

		// Be friendly
		add_filter( 'get_comment_author_link', array( __CLASS__, 'remove_nofollow_from_comment_author_link' ) );

		// More user info
		add_filter( 'user_contactmethods', array( __CLASS__, 'user_contactmethods' ) );

		// Replace sad HTML with HTML5
		add_filter( 'img_caption_shortcode', array( __CLASS__, 'img_caption_shortcode' ), 10, 3 );
	//	add_filter( 'image_send_to_editor', array( __CLASS__, 'image_send_to_editor' ),	10, 8 );

		// Remove p-tag around an image
		add_filter( 'the_content', array( __CLASS__, 'unautop_for_images' ), 99 );

		add_filter( 'post_class', array( __CLASS__, 'remove_hentry_microformat' ) );

		// Init the max width of the content
		if ( ! isset( $GLOBALS['content_width'] ) )
			$GLOBALS['content_width'] = 714;
	}

	public static function social_meta() {
		if ( is_feed() or is_trackback() )
			return;

		$meta = '';
		$meta .= sprintf( '<meta property="http://ogp.me/ns#locale" content="%s">', 'de_DE' );
		$meta .= sprintf( '<meta property="http://ogp.me/ns#site_name" content="%s">', 'wpGrafie.de' );

		if( is_singular() ) {
			$meta .= sprintf( '<meta property="http://ogp.me/ns#type" content="%s">', 'article' );
			$meta .= sprintf( '<meta property="http://ogp.me/ns#url" content="%s">', esc_url( get_permalink() ) );
			$meta .= sprintf( '<meta property="http://ogp.me/ns#title" content="%s">', esc_attr( get_the_title() ) ) ;
			$meta .= sprintf( '<meta property="http://ogp.me/ns#description" content="%s">', esc_attr( get_post_meta( get_the_ID(), '_wpseo_edit_description', true ) ) );
			$meta .= sprintf( '<meta property="http://ogp.me/ns/article#published_time" content="%s">', esc_attr( get_the_date( 'c' ) ) );
			$meta .= sprintf( '<meta property="http://ogp.me/ns/article#modified_time" content="%s">', esc_attr( get_the_modified_date( 'c' ) ) );

			$category = get_the_category();
			if ( ! empty( $category[0] ) )
				$meta .= sprintf( '<meta property="http://ogp.me/ns/article#section" content="%s">', esc_attr( $category[0]->cat_name ) );

			$tags = get_the_tags();
			if ( ! empty( $tags ) )
				foreach ( $tags as $tag )
					$meta .= sprintf( '<meta property="http://ogp.me/ns/article#tag" content="%s">', esc_attr( $tag->name ) );


			if ( has_post_thumbnail() ) {
				$image = wp_get_attachment_image_src(
					get_post_thumbnail_id()
				);

				$meta .= sprintf( '<meta property="http://ogp.me/ns#image" content="%s">', esc_url( $image[0] ) );
				$meta .= sprintf( '<meta property="http://ogp.me/ns#image:width" content="%s">', esc_attr( $image[1] ) );
				$meta .= sprintf( '<meta property="http://ogp.me/ns#image:height" content="%s">', esc_attr( $image[2] ) );

				// Google+
				$meta .= sprintf( '<meta itemprop="image" content="%s">', esc_url( $image[0] ) ) ;
			}
		}

		if ( ! has_post_thumbnail() || is_home() || is_archive() || is_search() ) {
			$meta .= sprintf( '<meta property="http://ogp.me/ns#image" content="%s">', esc_url( get_stylesheet_directory_uri() . '/img/wpgrafie-logo.png' ) );
			$meta .= sprintf( '<meta property="http://ogp.me/ns#image:width" content="%s">', '200' );
			$meta .= sprintf( '<meta property="http://ogp.me/ns#image:height" content="%s">', '200' );

			// Google+
			$meta .= sprintf( '<meta itemprop="image" content="%s">',  esc_url( get_stylesheet_directory_uri() . '/img/wpgrafie-logo.png' ) );
		}

		$meta .= sprintf( '<meta property="http://ogp.me/ns/fb#app_id" content="%s">', '177681565672418' );

		if ( ! is_singular( 'post', 'schnipsel' ) )
			$meta .= '<link rel="author" href="https://plus.google.com/101675293278434581718/">';

		echo $meta;
	}

	public static function remove_hentry_microformat( $classes ) {
		return array_diff( $classes, array( 'hentry' ) );
	}

	/**
	 * [unautop_for_images description]
	 * Thx Frank Bueltge. http://bueltge.de/p-tag-bei-bilden-in-wordpress-ersetzen/1306/
	 * @return [type] [description]
	 */
	public static function unautop_for_images( $content ) {
		if ( is_feed() ) return $content;

		$content = preg_replace(
			'/<p>\\s*?(<a rel=\"attachment.*?><img.*?><\\/a>|<img.*?>)?\\s*<\\/p>/s',
			'<figure>$1</figure>',
			$content
		);

		return $content;
	}

	/**
	 * Add comment status as a class.
	 *
	 * @access public
	 * @static
	 * @param mixed $classes
	 * @param mixed $c (default: null)
	 * @param mixed $comment_id (default: null)
	 * @return void
	 */
	public static function add_custom_comment_class( $classes , $c = null , $comment_id = null) {
		if ( is_admin() )
			return $classes;

		$status = wp_get_comment_status( $comment_id );
		$status ? ( $classes[] = 'comment-' . $status ) : '';

		return $classes;
	}

	/**
	 * Remove rel="nofollow" from comment author link.
	 *
	 * @access public
	 * @static
	 * @param mixed $html
	 * @return void
	 */
	public static function remove_nofollow_from_comment_author_link( $html ) {
		$html = str_replace( ' nofollow', '', $html );

		return $html;
	}

	/**
	 * [search_query description]
	 * @param  [type] $query [description]
	 * @return [type]        [description]
	 */
	public static function search_query( $query ) {
		if ( ! $query->is_search() || is_admin() )
			return $query;

		$query->set( 'post_type', array( 'post', 'schnipsel' ) );
		$query->set( 'posts_per_page', -1 );
		$query->set( 'no_found_rows', 1 );

		return $query;
	}

	/**
	 * [include_post_type_in_feed description]
	 * @param  [type] $query [description]
	 * @return [type]        [description]
	 */
	public static function include_post_type_in_feed( $query ) {
		if ( $query->is_feed( get_default_feed() ) )
			$query->set( 'post_type', array( 'post', 'schnipsel' ) );

		return $query;
	}

	public function image_send_to_editor( $html, $id, $caption, $title, $align, $url, $size, $alt ) {
		// Not used.
		return '<figure>' . $html . '</figure>';
	}

	public function img_caption_shortcode( $null, $attr, $content ) {
		extract( shortcode_atts( array(
			'id'      => '',
			'align'   => 'alignnone',
			'width'   => '',
			'caption' => ''
		), $attr ) );

		if ( 1 > (int) $width || empty( $caption ) )
			return $content;

		if ( $id )
			$id = 'id="' . esc_attr( $id ) . '" ';

		return '<figure ' . $id . 'class="wp-caption ' . esc_attr( $align ) . '" style="width: ' . (int) $width . 'px">' . do_shortcode( $content ) . '<figcaption class="wp-caption-text">' . $caption . '</figcaption></figure>';
	}

	/**
	 * Autoload function to load the classes.
	 *
	 * @since 1.0
	 *
	 * @param  string $class Class name.
	 * @return void
	 */
	static function autoload( $class ) {
		if ( in_array( $class, self::$classes ) ) {
			require_once(
				sprintf(
					'%s/classes/class-%s.php',
					get_stylesheet_directory(),
					str_replace( '_', '-', strtolower( $class ) )
				)
			);
		}
	}

	/**
	 * Initialize custom menus.
	 *
	 * @since 1.0
	 *
	 * @return void
	 */
	public static function init_menus() {
		register_nav_menus(
			array(
				'menu_top'    => 'Header Navigation',
				//'menu_bottom' => 'Footer Navigation'
			)
		);

		add_filter( 'wp_nav_menu_items', array( __CLASS__, 'add_custom_menu_items' ), 10, 2 );
	}

	/**
	 * Add custom menu items to header navigation.
	 *
	 * @since 1.0
	 *
	 * @param string $items Menu items in HTML
	 * @param object $args  Menu arguments
	 * @return string Updated menu items in HTML
	 */
	public static function add_custom_menu_items( $items, $args ) {
		if ( $args->theme_location != 'menu_top' )
			return $items;

		// Add RSS feed icon.
		$items .=
			'<li id="menu-item-feed" class="menu-item menu-item-feed"><a data-icon="r" class="icon" href="/feed/">Feed</a></li>';

		return $items;
	}


	public static function breadcrumb() {
		if ( is_home() )
			return;

		$templates = array(
			'before' => '<nav id="breadcrumb-navigation" class="width-960"><ul data-icon="/" class="icon">',
			'after' => '</ul></nav>',
			'standard' => '<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb">%s<span class="sep">&nbsp;/&nbsp;</span></li>',
			'current' => '<li class="current">%s</li>',
			'link' => '<a href="%s" itemprop="url"><span itemprop="title">%s</span></a>'
		);
		$options = array(
			'show_htfpt' => true,
			'separator' => ''
		);
		$breadcrumb = new DS_WP_Breadcrumb( $templates, $options, array(), false );
		$breadcrumb->generate();
		//if ( ! is_search() )
		//	$breadcrumb->breadcrumb = array_slice( $breadcrumb->breadcrumb, 0, -1 );
		if ( ! empty( $breadcrumb->breadcrumb ) )
			echo $breadcrumb->output();
	}

	/**
	 * Artikelbilder
	 *
	 * @since 0.1
	 *
	 * @return void
	 */
	public static function init_post_thumbnails() {
		add_theme_support( 'post-thumbnails' );

		add_image_size( 'article-image-big', 960, 192, true );
		add_image_size( 'article-image-middle', 635, 127, true );
		add_image_size( 'article-image-small', 310, 62, true );
	}

	/**
	 * Paging bar.
	 * Thx Sergej Müller.
	 *
	 * @access public
	 * @static
	 * @param int $range (default: 4)
	 * @return void
	 */
	public static function paging_bar( $range = 4 ) {
		/* Init */
		$count = @$GLOBALS['wp_query']->max_num_pages;
		$page = (int) get_query_var( 'paged' );
		$ceil = ceil( $range / 2 );

		/* Kein Paging? */
		if ( $count <= 1 )
			return false;

		/* Erste Seite? */
		if ( ! $page )
			$page = 1;

		/* Limit errechnen */
		if ( $count > $range ) {
			if ( $page <= $range ) {
				$min = 1;
				$max = $range + 1;
			} else if ( $page >= ( $count - $ceil ) ) {
				$min = $count - $range;
				$max = $count;
			} else if ( $page >= $range && $page < ( $count - $ceil ) ) {
				$min = $page - $ceil;
				$max = $page + $ceil;
			}
		} else {
			$min = 1;
			$max = $count;
		}

		/* Ausgabe der Links */
		$links = '';
		if ( ! empty( $min ) && ! empty( $max ) )
			for($i = $min; $i <= $max; $i++)
				$links .= ( $i == $page ) ? sprintf( '<span class="current">%d</span>', $i ) : sprintf( '<a href="%s">%d</a>', get_pagenum_link( $i ), $i );

		echo $links;
	}

	public static function user_contactmethods( $user_contactmethods ) {
		$user_contactmethods['googleplus']	= 'Google+';
		$user_contactmethods['twitter']		= 'Twitter';
		$user_contactmethods['facebook']	= 'Facebook';

		unset( $user_contactmethods['yim'] );
		unset( $user_contactmethods['jabber'] );
		unset( $user_contactmethods['aim'] );

		return $user_contactmethods;
	}
}

add_action( 'after_setup_theme', array( 'DS_wpGrafie_Theme', 'init' ), 1 );
