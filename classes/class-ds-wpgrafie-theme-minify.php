<?php
class DS_wpGrafie_Theme_Minify extends DS_wpGrafie_Theme {
	/**
	 * Placeholder to store <pre></pre> code.
	 *
	 * @var array
	 */
	private static $_pre_placeholders = array();

	/**
	 * Placeholder to store <ul></ul> code.
	 *
	 * @var array
	 */
	private static $_ul_placeholders = array();

	/**
	 * (Re)store <pre></pre> code and replace it with a placeholder.
	 *
	 * @since 0.1
	 *
	 * @param  string  $data    HTML
	 * @param  boolean $restore True if code should be restored, false if not. Default: false
	 * @return string HTML
	 */
	private static function save_space( $data, $type, $restore = false ) {
		switch( $type ) {
			case 'pre' :
				if ( ! $restore ) {
					return preg_replace_callback(
						'/<pre\b[^>]*?>(.*?)<\/pre>/is',
						array( __CLASS__, 'pre_cb' ),
						$data
					);
				} else {
					return str_replace(
						array_keys( self::$_pre_placeholders ),
						array_values( self::$_pre_placeholders ),
						$data
					);
				}
				break;
			case 'ul' :
				if ( ! $restore ) {
					return preg_replace_callback(
						'/<ul\b[^>]*?class="bubbles"[^>]*?>(.*?)<\/ul>/is',
						array( __CLASS__, 'ul_cb' ),
						$data
					);
				} else {
					return str_replace(
						array_keys( self::$_ul_placeholders ),
						array_values( self::$_ul_placeholders ),
						$data
					);
				}
				break;
			default:
		}
	}

	/**
	 * Callback function for save_space().
	 *
	 * @since 0.1
	 *
	 * @param  string $match <pre></pre> Code
	 * @return string Placeholder key
	 */
	private static function pre_cb( $match ) {
		$placeholder = '%WPGRAFIESAVEPRE' . count( self::$_pre_placeholders ) . '%';
		self::$_pre_placeholders[$placeholder] = $match[0];
		return $placeholder;
	}

	/**
	 * Callback function for save_space().
	 *
	 * @since 0.1
	 *
	 * @param  string $match <ul></ul> Code
	 * @return string Placeholder key
	 */
	private static function ul_cb( $match ) {
		$placeholder = '%WPGRAFIESAVEUL' . count( self::$_ul_placeholders ) . '%';
		self::$_ul_placeholders[$placeholder] = $match[0];
		return $placeholder;
	}

	/**
	 * Replace HTML comments, whitespaces and domain.
	 * Props Sergej Müller.
	 *
	 * @since 0.1
	 *
	 * @param string $data HTML
	 * @return string HTML without HTML comments and domain. Less whitespaces.
	 */
	private static function compressor( $data ) {
		return preg_replace(
			array(
				'/\<!--\s.+?--\>/s',
				'/\>(\s)+(\S)/s',
				'/\>[^\S ]+/s',
				'/[^\S ]+\</s',
				'/\>(\s)+/s',
				'/(\s)+\</s',
				'/\>\s+\</s',
				'/<script/',
				"/ type=('|\")text\/javascript('|\")/",
				'/http:\/\/wpgrafie.de/'
			),
			array(
				'',
				'>\\1\\2',
				'>',
				'<',
				'>\\1',
				'\\1<',
				'><',
				'<script defer="defer"',
				'',
				''
			),
			$data
		);
	}

	/**
	 * Compress HTML output.
	 *
	 * @since 0.1
	 *
	 * @param string $data HTML
	 * @return string Minified HTML
	 */
	public static function compress_html( $data ) {
		if ( self::$dev )
			return $data;

		$data = self::save_space( $data, 'pre' );
		$data = self::save_space( $data, 'ul' );
		$data = self::compressor( $data );
		$data = self::save_space( $data, 'pre', true );
		$data = self::save_space( $data, 'ul', true );

		return (string) $data;
	}
}
