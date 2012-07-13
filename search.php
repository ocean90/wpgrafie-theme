<?php
get_header();
?>

<div class="row">
	<div class="span8" id="index-last-posts">
		<h2 class="section-title"><span>Deine Suchergebnisse</span></h2>
		<?php
		get_template_part( 'content', 'search' );
		?>
	</div>
	<div class="span4 sidebar" id="index-sidebar">
		<?php
		get_sidebar( 'index' );
		?>
	</div>
</div>
<?php
get_footer();
