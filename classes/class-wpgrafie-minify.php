<?php
class DS_wpGrafie_Theme_Minify extends DS_wpGrafie_Theme {
	/**
	 * Placeholder to store <pre></pre> code.
	 *
	 * @var array
	 */
	private static $_placeholders = array();

	/**
	 * (Re)store <pre></pre> code and replace it with a placeholder.
	 *
	 * @since 0.1
	 *
	 * @param  string  $data    HTML
	 * @param  boolean $restore True if code should be restored, false if not. Default: false
	 * @return string HTML
	 */
	private static function save_pre( $data, $restore = false ) {
		if ( ! $restore ) {
			return preg_replace_callback(
				'/<pre\b[^>]*?>(.*?)<\/pre>/is',
				array( __CLASS__, 'pre_cb' ),
				$data
			);
		} else {
			return str_replace(
				array_keys( self::$_placeholders ),
				array_values( self::$_placeholders ),
				$data
			);
		}
	}

	/**
	 * Callback function for save_pre().
	 *
	 * @since 0.1
	 *
	 * @param  string $match <pre></pre> Code
	 * @return string Placeholder key
	 */
	private static function pre_cb( $match ) {
		$placeholder = '%WPGRAFIESAVEPRE' . count( self::$_placeholders ) . '%';
		self::$_placeholders[$placeholder] = $match[0];
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
			//	'/<script/',
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
			//	'<script defer',
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

		$data = self::save_pre( $data );
		$data = self::compressor( $data );
		$data = self::save_pre( $data, true );

		return (string) $data;
	}
}
