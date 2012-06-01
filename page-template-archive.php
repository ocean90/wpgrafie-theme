<?php
/**
 * Template Name: Archive
 */

get_header();

query_posts( array(
	'posts_per_page' => -1,
//	'paged' => get_query_var( 'paged' ),
	'post_type' => array( 'post', 'schnipsel' )
) );


function ds_the_date( $d ) {
	global $ds_prev_date;

	if ( ! empty( $ds_prev_date[$d]) && $ds_prev_date[$d] == get_the_date( $d ) )
		return false;

	$date = get_the_date( $d );
	$ds_prev_date[$d] = $date;

	return $date;
}
?>
<div class="row big-image" id="archive-top-img">
	<div class="vignette"></div>
	<img width="960" height="192" src="<?php echo get_stylesheet_directory_uri() ?>/img/banner/archiv.png" />
</div>
<div class="row top-desc" id="schnipsel-top-desc">
	<p>Alle Artikel und Codeschnipsel nach Jahr und Monat aufgelistet. Viel Spaß beim stöbern!</p>
</div>
<div id="archive-page" class="row">
<?php
$open = false;
while (have_posts()) :
	the_post();
	$year = ds_the_date( 'Y' );
	$month = ds_the_date( 'F' );

	if ( $year ) {
		if ( ! $open ) {
			echo '<div class="year-section"><ul class="archive-titles">';
			$open = true;
		} else {
			echo '</ul></div><div class="year-section"><ul class="archive-titles">';
		}

		printf(
			'<li class="archive-year">%s</li>',
			$year
		);
	}

	if ( $month ) {
		printf(
			'<li class="archive-month">%s</li>',
			$month
		);
	}

	?>

		<li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>

	<?php


endwhile;
echo '</ul></div></div>';
DS_wpGrafie_Theme::paging_bar();

get_footer();
