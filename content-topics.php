<?php

echo '<div class="row" id="my-topics"><ul>';
wp_list_categories( array(
	'title_li' => false
) );
echo '</ul></div>';
