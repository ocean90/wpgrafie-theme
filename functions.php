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
	 * Main script version.
	 *
	 * @since 2.0
	 *
	 * @var string
	 */
	private static $script_version = '0.1';

	/**
	 * Main stylesheet version.
	 *
	 * @since 2.0
	 *
	 * @var string
	 */
	private static $style_version = '0.1';

	/**
	 * Additional classes which will be autoloaded.
	 *
	 * @since 2.0
	 *
	 * @var array
	 */
	private static $classes = array(
		'DS_wpGrafie_Theme_Minify',
		'DS_wpGrafie_Theme_Schnipsel',
		'DS_wpGrafie_Theme_Widgets',
		'DS_wpGrafie_Comments',
		'DS_WP_Breadcrumb',
		'DS_wpGrafie_WP_Planet_Feed',
		'DS_wpGrafie_Plugin'
	);

	/**
	 * Initialize the main class.
	 *
	 * @since 0.1
	 *
	 * @return void
	 */
	public static function init() {
		self::$dev = defined( 'WP_DEBUG' ) && WP_DEBUG;

		spl_autoload_register( array( __CLASS__, 'autoload' ) );

		/*add_action( 'after_setup_theme', array( __CLASS__, 'init_hooks' ), 3 );

		add_action( 'after_setup_theme', array( __CLASS__, 'init_post_formats' ), 5 );
		add_action( 'after_setup_theme', array( 'wpgrafie_Widgets', 'init' ), 12 );



		add_action( 'init', array( 'wpgrafie_Schnipsel', 'init' ), 1 );

		add_action( 'init', array( __CLASS__, 'set_pagination_base' ) );*/
		add_action( 'init', array( __CLASS__, 'set_pagination_base' ) );
		add_action( 'init', array( 'DS_wpGrafie_Theme_Schnipsel', 'init' ), 1 );
		add_action( 'template_redirect', array( __CLASS__, 'init_scripts' ), 1 );
		add_action( 'template_redirect', array( __CLASS__, 'init_styles' ), 1 );
		add_action( 'after_setup_theme', array( __CLASS__, 'init_menus' ), 5 );
		add_action( 'after_setup_theme', array( __CLASS__, 'init_post_thumbnails' ), 7 );

		add_action( 'init', array( 'DS_wpGrafie_WP_Planet_Feed', 'init') );

		add_action( 'pre_get_posts', array( __CLASS__, 'filter_queries' ) );
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
	 * Setzt die Seitenbasis auf /seite/. (Standard: /page/)
	 *
	 * @since 0.1
	 *
	 * @return void
	 */
	public static function set_pagination_base() {
	 	global $wp_rewrite;

		$wp_rewrite->pagination_base = 'seite';
	}

	/**
	 * Initialize scripts.
	 *
	 * @since 0.1
	 *
	 * @return void
	 */
	public static function init_scripts() {
		global $post;

		$dev = self::$dev || is_user_logged_in() ? '' : '.min';

		wp_register_script(
			self::$themeslug ,
			sprintf(
				'%s/js/jquery.%s%s.js',
				get_stylesheet_directory_uri(),
				self::$themeslug,
				$dev
			),
			array( 'jquery' ),
			self::$script_version,
			true
		);

		wp_enqueue_script( self::$themeslug );

		if ( ! empty( $post ) && is_singular() && get_comments_number( $post->ID ) > 0 )
			wp_enqueue_script( 'comment-reply' );
	}

	/**
	 * Initialize styles.
	 *
	 * @access public
	 * @static
	 * @return void
	 */
	public static function init_styles() {
		$dev = self::$dev || is_user_logged_in() ? '' : '.min';

		wp_register_style(
			self::$themeslug . '-webfont',
			'http://fonts.googleapis.com/css?family=Bitter:400,700,400italic',
			false,
			'1.0'
		);

		wp_register_style(
			self::$themeslug,
			sprintf(
				'%s/css/style%s.css',
				get_stylesheet_directory_uri(),
				$dev
			),
			array( self::$themeslug . '-webfont' ),
			self::$style_version
		);

		wp_enqueue_style( self::$themeslug );
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

		if( is_home() )
			return;

		$templates = array(
			'before' => '<nav id="breadcrumb-navigation" class="width-960"><ul data-icon="l" class="icon">',
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

	function filter_queries($query) {
		// Bail if $posts_query is not an object or of incorrect class
		if ( !is_object( $query ) || ( 'WP_Query' != get_class( $query ) ) )
			return;

		// Bail if filters are suppressed on this query
		if ( true == $query->get( 'suppress_filters' ) )
			return;

	}
}

add_action( 'after_setup_theme', array( 'DS_wpGrafie_Theme', 'init' ), 1 );
