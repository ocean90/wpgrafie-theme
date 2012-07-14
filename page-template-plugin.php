<?php
/**
 * Template Name: Plugin
 */

get_header();

$plugin = new DS_wpGrafie_Plugin();
$plugin_data = $plugin->plugin_data;

while (have_posts()) :
	the_post();
?>
	<article id="article" <?php post_class(); ?> itemscope itemtype="http://schema.org/Product">
		<header id="article-header">
			<div class="row big-image" id="article-image">
				<div class="vignette"></div>
				<?php the_post_thumbnail( 'article-image-big' ); ?>
			</div>
			<div class="row" id="article-title">
				<h2 itemprop="name"><?php the_title(); ?></h2>
			</div>
		</header>
		<div class="row">
			<div class="span8" id="article-content">
				<?php
				$plugin->switch_message();
				?>
				<p class="description" itemprop="description"><?php do_action('wpseo_the_desc'); ?></p>
				<?php the_content(); ?>
			</div>
			<aside class="span4" id="article-sidebar">
				<?php
				if( ! empty( $plugin_data ) ) :
				?>
				<div class="plugin-download" itemprop="offers" itemscope itemtype="http://schema.org/Offer">
					<a href="<?php echo esc_url( $plugin_data->download->link ); ?>">Download<br/><small>Version <?php echo $plugin_data->version; ?></small></a>
					<meta itemprop="price" content="0.00" />
					<meta itemprop="priceCurrency" content="EUR" />
				</div>
				<div class="plugin-info">
					<div id="plugin-downloads" itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating" class="row">
						<h2 class="section-title"><span>Downloads</span></h2>
						<p>Das Plugin wurde schon <?php echo $plugin_data->download->count; ?> mal heruntergeladen.<br/>
							Dabei bekam es <?php echo $plugin_data->rating->feedback; ?> positives Feedback, basierend auf <?php echo $plugin_data->rating->count; ?> Wertungen im Plugin Verzeichnis von <a href="http://wordpress.org/extend/plugins/<?php echo $plugin_data->slug; ?>">WordPress.org</a>.</p>
						<meta itemprop="ratingValue" content="<?php echo esc_attr( $plugin_data->rating->rounded ); ?>" />
						<meta itemprop="ratingCount" content="<?php echo esc_attr( $plugin_data->rating->count ); ?>" />
					</div>
					<div id="plugin-compatibility" class="row">
						<h2 class="section-title"><span>WordPress Unterstützung</span></h2>
						<p>Das Plugin benötigt mindestens WordPress <?php echo $plugin_data->compat->requires; ?> und wurde bis WordPress <?php echo $plugin_data->compat->tested; ?> getestet.</p>
					</div>
					<div id="plugin-changelog" class="row">
						<h2 class="section-title"><span>Änderungen</span></h2>
						<p>Zuletzt aktualisiert am <?php echo $plugin_data->log->last_update; ?>.</p>
						<?php echo $plugin_data->log->changes; ?>
					</div>
				</div>
				<?php endif; ?>
			</aside>
		</div>
		<footer id="article-footer">
			<?php
			get_template_part( 'social' );
			?>
		</footer>
	</article>
	<?php
	comments_template( '', true );
endwhile;

get_footer();
