<?php
class DS_wpGrafie_Theme_Script_Styles extends DS_wpGrafie_Theme {
	/**
	 * Main script version.
	 *
	 * @since 2.0
	 *
	 * @var string
	 */
	private static $script_version = '0.2.1';

	/**
	 * Main stylesheet version.
	 *
	 * @since 2.0
	 *
	 * @var string
	 */
	private static $style_version = '0.3.3';

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
}
?>
