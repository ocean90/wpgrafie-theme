<?php
get_header();
?>
<div id="about-me" class="row">
	<div class="span6 about-text">
		<p class="first">WordPress Core Contributer,</p>
		<p class="second">Plugin Entwickler und ein bisschen mehr.</p>
		<p class="third">Mein Name ist Dominik, ich liebe <span class="word">Word</span><span class="press">Press</span><br/> und teile gerne mein Wissen darüber.</p>
	</div>
	<div class="span6">
		<img width="460" height="345" src="<?php echo get_stylesheet_directory_uri() ?>/img/about.png" alt="" />
	</div>
</div>

<div class="row">
	<div class="span8" id="index-last-posts">
		<h2 class="section-title"><span>Die letzten Artikel</span></h2>
		<?php
		get_template_part( 'content', 'last-posts' );
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
