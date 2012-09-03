<?php
/**
 * Template Name: Donate
 */

get_header();

while (have_posts()) :
	the_post();
?>
	<article id="article" <?php post_class( 'full-width' ); ?>>
		<header id="article-header">
			<div class="row" id="article-title">
				<h2><?php the_title(); ?></h2>
			</div>
		</header>
		<div class="row">
			<div class="span12" id="article-content">
				<?php the_content(); ?>
				<div class="row thank-row ">
					<div class="span6">
						<div id="uberspace" class="thank-box uberspace">
							<p>Du bist auch Ubernaut? Dann kannst du etwas Guthaben mit mir teilen.<br/>
								Mein Benutzername: <strong>wpplugin</strong></p>
							<a class="no-link-style" href="https://uberspace.de/dashboard/accounting">Guthaben umbuchen</a>
							<small><a class="no-link-style" href="https://uberspace.de/dokuwiki/start:payment">Kein Ubernaut? Alternative.</a></small>
						</div>
					</div>
					<div class="span6">
						<div id="paypal" class="thank-box paypal">
						<p>Paypal sollte bekannt sein. Schnell, sicherererer und kostenlos mir ein paar Groschen überlassen.</p>
						<a class="no-link-style" href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=VR8YU922B7K46">Spenden</a>
						</div>
					</div>
				</div>
				<div class="row thank-row">
					<div class="span6">
						<div id="amazon" class="thank-box amazon">
							<p class="grey">Wie viele Wünsche hab ich frei? Mein Wunschzettel ist lang!</p>
							<a class="no-link-style" href="http://www.amazon.de/registry/wishlist/ZO8E0WLQQKVW">Amazon Wunschzettel</a>
						</div>
					</div>
					<div class="span6">
						<div id="flattr" class="thank-box flattr">
							<p class="grey">Du magst mich schmeicheln und hast einen Account bei Flattr?</p>
							<a class="no-link-style" href="https://flattr.com/profile/ocean90">Flattr @ocean90</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</article>
	<?php
endwhile;

get_footer();
