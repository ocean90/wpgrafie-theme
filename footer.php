	</section>
	</div>
	<footer id="footer">
		<div class="width-960">
			<div class="row">
				<div class="span4">
					<h2 class="section-title"><span>Schlagwörter</span></h2>
					<?php
					get_template_part( 'content', 'all-terms' );
					?>
				</div>
				<div class="span4">
					<h2 class="section-title"><span>Suche</span></h2>
					<?php
					get_search_form();
					?>
				</div>
				<div class="span4">
					<h2 class="section-title"><span>Sitemap</span></h2>
					<?php
					get_template_part( 'content', 'pages' );
					?>
				</div>
			</div>
			<div class="row">
				<?php
				get_template_part( 'content', 'imprint' );
				?>
			</div>
		</div>
	</footer>
	<?php wp_footer(); ?>
</body>
</html>
