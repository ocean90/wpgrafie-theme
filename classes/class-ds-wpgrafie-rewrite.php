<?php
class DS_wpGrafie_Rewrite extends DS_wpGrafie_Theme {
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
	
}
