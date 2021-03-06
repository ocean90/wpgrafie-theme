<?php
get_header();
?>
<div id="about-me" class="row">
		<p class="first">Hey, ich bin Dominik – <span class="word">Word</span><span class="press">Press</span> Contributing Developer,</p>
		<p class="second">(Web) Developer und ein bisschen mehr.</p>
		<p class="third">WordPress ist meine Leidenschaft. Hier beschreibe ich die Kunst und Technik von WordPress.</p>
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
