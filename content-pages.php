<?php

echo '<ul id="page-list">';
wp_list_pages( array(
	'depth' => 2,
	'title_li' => false
));
echo '</ul>';
