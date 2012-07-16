<?php get_header(); ?>
<div class="row" id="taxarchive-title">
	<h2>Archiv: <?php single_term_title(); ?></h2>
</div>
<div class="row">
	<div class="span8" id="category-posts">
		<h2 class="section-title"><span>Die Artikel aus dem Archiv</span></h2>
		<?php
		get_template_part( 'content', 'archive' );
		?>
	</div>
	<div class="span4 sidebar" id="category-sidebar">
		<?php
		get_sidebar( 'index' );
		?>
	</div>
</div>
<?php
get_footer();
