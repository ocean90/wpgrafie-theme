<?php
// Some recommendations
$recoms = array(
	array(
		'title' => 'WordPress SEO ist die Kernkompetenz von wpSEO, dem zugkräftigen Plugin für die Suchmaschinenoptimierung unter WordPress.',
		'image' => get_stylesheet_directory_uri() . '/img/empfehlungen/wpseo.png',
		'link'  => 'http://wpseo.de',
		'height' => 118,
		'width' => 306
	)
);

foreach ( $recoms as $recom ) {
	printf(
		'<p><a href="%s" title="%s" class="no-link-style"><img src="%s" alt="%s" height="%s" width="%s"/></a></P>',
		esc_url( $recom['link'] ),
		esc_attr( $recom['title'] ),
		esc_url( $recom['image'] ),
		esc_attr( $recom['title'] ),
		esc_attr( $recom['height'] ),
		esc_attr( $recom['width'] )
	);
}
