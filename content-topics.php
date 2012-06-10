<?php

echo '<div class="row" id="my-topics"><ul>';
wp_list_categories( array(
	'title_li' => false,
	'exclude'  => '4'
) );
echo '</ul></div>';
