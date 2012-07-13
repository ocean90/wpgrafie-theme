<?php

echo '<div class="row" id="my-topics"><ul>';
$links = wp_list_categories( array(
	'title_li' => false,
	'exclude'  => '4',
	'echo'     => 0
) );

echo preg_replace( '/href/', 'rel="nofollow" href', $links );
echo '</ul></div>';
