<?php get_header(); ?>
<div class="row big-image" id="schnipsel-top-img">
	<div class="vignette"></div>
	<img width="960" height="192" src="<?php echo get_stylesheet_directory_uri() ?>/img/banner/schnipsel.png" />
</div>
<div class="row top-desc" id="schnipsel-top-desc">
	<p>Verschiedene Codeschnipsel für WordPress. Sei es eine Erweiterung für das Backend oder für das eigene Theme.<br/> Schnipsel vom Profi mit Sinn und Verstand.</p>
</div>
<div class="row">
	<div class="span8" id="schnipsel-posts">
		<h2 class="section-title"><span>Die letzten Codeschnipsel</span></h2>
		<?php
		get_template_part( 'content', 'last-schnipsel' );
		?>
	</div>
	<div class="span4 sidebar" id="schnipsel-sidebar">
		<?php
		get_sidebar( 'schnipsel' );
		?>
	</div>
</div>
<?php
get_footer();
